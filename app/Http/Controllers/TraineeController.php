<?php
namespace App\Http\Controllers;

use App\Exports\AllExport;
use App\Exports\ExportLog;
use App\Exports\ExportTrainee;
use App\Imports\TraineeImport;
use App\Models\Academy;
use App\Models\Cohort;
use App\Models\Company;
use App\Models\EmploymentLog;
use App\Models\Trainee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TraineeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportTrainees(Request $request, $academy)
    {
        // Get cohort_id from the request
        $cohort_id = $request->cohort_id;
        $academy_id = $academy;

        // Validate if cohort exists
        $cohort = Cohort::findOrFail($cohort_id);
        $academy = Academy::findOrFail($academy_id);
        $filename = \Str::slug($academy->name) . '_' . now()->format('YmdHis') . '.xlsx';
        return Excel::download(new ExportTrainee($academy_id, $cohort_id), $filename);
    }

    public function exportLogs($traineeId)
    {
        $trainee = Trainee::findOrFail($traineeId);

        $logs = $trainee->employment_logs;
        // Check if logs exist
        if ($logs && $logs->isNotEmpty()) {
            $filename = \Str::slug($trainee->first_name) . '_logs_' . now()->format('YmdHis') . '.xlsx';
            return Excel::download(new ExportLog($logs), $filename);
        } else {
            // Handle the case where no logs are found
            return redirect()->back()->with('error', 'No logs found for this trainee.');
        }
    }

    public function index(Academy $academy, Cohort $cohort)
    {
        $trainees = Trainee::where('cohort_id', $cohort->id)
            ->where('academy_id', $academy->id)
            ->orderByRaw("employment_status = 'unemployed' DESC")
            ->paginate(60);
        // dd($trainees);
        return view('trainees.index', compact('trainees', 'academy', 'cohort'));
    }

    public function allProfiles()
    {
        $trainees = Trainee::paginate(60);  // Fetch all trainees

        return view('trainees.allprofiles', ['trainees' => $trainees]);
    }

    public function traineeLog(Academy $academy, Cohort $cohort)
    {
        // Fetch academy and cohort info
        $academis = Academy::where('id', $academy->id)->get('location');
        $cohort_nams = Cohort::where('id', $cohort->id)->get('slug');

        // Get trainees with logs
        $trainees = Trainee::where('academy_id', $academy->id)
            ->where('cohort_id', $cohort->id)
            ->with([
                'employment_logs' => function ($query) use ($academy, $cohort) {
                    $query
                        ->where('academy_id', $academy->id)
                        ->where('cohort_id', $cohort->id)
                        ->select('id', 'trainee_id', 'company', 'status', 'position', 'academy_id', 'cohort_id', 'start_date', 'end_date')
                        ->orderByDesc('start_date');
                },
                'academy:id,location',
            ])
            ->get();

        // ========= OVERALL STATS =========
        $totalGraduates = Trainee::where('academy_id', $academy->id)
            ->where('cohort_id', $cohort->id)
            ->count();

        $employedGraduates = Trainee::where('academy_id', $academy->id)
            ->where('cohort_id', $cohort->id)
            ->whereHas('employment_logs', function ($q) {
                $q->whereIn('status', ['job offer', 'internship for employment', 'freelance']);
            })
            ->count();

        // New available calculation: graduated AND unemployed
        $available = Trainee::where('academy_id', $academy->id)
            ->where('cohort_id', $cohort->id)
            ->where('graduated', 'yes')
            ->where('employment_status', 'unemployed')
            ->count();

        $employmentRate = ($totalGraduates > 0)
            ? ceil(($employedGraduates / $totalGraduates) * 100)
            : 0;

        $trainees_number = $totalGraduates;

        // ========= GENDER STATS =========
        $genderStats = [];
        foreach (['male', 'female'] as $gender) {
            $total = Trainee::where('academy_id', $academy->id)
                ->where('cohort_id', $cohort->id)
                ->where('gender', $gender)
                ->count();

            $employed = Trainee::where('academy_id', $academy->id)
                ->where('cohort_id', $cohort->id)
                ->where('gender', $gender)
                ->whereHas('employment_logs', function ($q) {
                    $q->whereIn('status', ['job offer', 'internship for employment', 'freelance']);
                })
                ->count();

            $availableGender = Trainee::where('academy_id', $academy->id)
                ->where('cohort_id', $cohort->id)
                ->where('gender', $gender)
                ->where('graduated', 'yes')
                ->where('employment_status', 'unemployed')
                ->count();

            $rate = ($total > 0) ? round(($employed / $total) * 100, 2) : 0;

            $genderStats[$gender] = [
                'trainees_number' => $total,
                'totalGraduates' => $total,
                'employedGraduates' => $employed,
                'available' => $availableGender,
                'employmentRate' => $rate,
            ];
        }

        // ========= TRAINEES PER COMPANY (only companies hiring > 2 trainees) =========
        $companyStats = EmploymentLog::where('academy_id', $academy->id)
            ->where('cohort_id', $cohort->id)
            ->where('company', '!=', 'N/A')
            ->select('company')
            ->get()
            ->groupBy('company')
            ->map(fn($logs) => count($logs))
            ->sortDesc();
        $totalCompanies = $companyStats->count();

        $companyLabels = $companyStats->keys()->toArray();
        $companyData = $companyStats->values()->toArray();

        // ========= CARBON CALCULATIONS =========
        $endDateRaw = $cohort->end_date;

        try {
            if (strpos($endDateRaw, '/') !== false) {
                $cohortEnd = Carbon::createFromFormat('d/m/Y', $endDateRaw);
            } elseif (strpos($endDateRaw, '-') !== false && strlen($endDateRaw) === 10) {
                $cohortEnd = Carbon::createFromFormat('Y-m-d', $endDateRaw);
            } else {
                $cohortEnd = Carbon::parse($endDateRaw);
            }
        } catch (\Exception $e) {
            $cohortEnd = Carbon::today();
        }

        $startDate = $cohortEnd->copy()->addDay();
        $sixMonthsAfter = $startDate->copy()->addMonths(6);

        $employmentByMonth = EmploymentLog::where('academy_id', $academy->id)
            ->where('cohort_id', $cohort->id)
            ->whereIn('status', ['job offer', 'internship for employment', 'freelance'])
            ->whereBetween('start_date', [$startDate, $sixMonthsAfter])
            ->select('start_date')
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->start_date)->format('M Y'))
            ->map(fn($logs) => count($logs));

        $monthsRange = collect(range(1, 6))->map(fn($i) => $startDate->copy()->addMonths($i - 1)->format('M Y'));

        $employmentByMonth = $monthsRange->mapWithKeys(fn($month) => [
            $month => $employmentByMonth->get($month, 0)
        ]);

        $employmentMonths = $employmentByMonth->keys()->toArray();
        $employmentCounts = $employmentByMonth->values()->toArray();

        $totalTrains = $trainees->count();

        return view('employment-status.employment_log_status', compact(
            'trainees',
            'academis',
            'cohort_nams',
            'totalGraduates',
            'employedGraduates',
            'available',
            'employmentRate',
            'genderStats',
            'companyLabels',
            'companyData',
            'employmentMonths',
            'employmentCounts',
            'totalCompanies',
            'totalTrains'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trainee = Trainee::findOrFail($id);
        $cohortId = $trainee->cohort->id;

        return view('trainees.show', compact('trainee', 'cohortId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trainee = Trainee::findOrFail($id);
        // dd($trainee);
        return view('employment-status.edit', compact('trainee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {  // Custom error messages
        $messages = [
            'personal_img.image' => 'The selected file must be an image.',
            'personal_img.mimes' => 'Only JPEG, PNG, JPG, GIF formats are allowed for images.',
            'personal_img.max' => 'The image size should not exceed 4000 KB.',
            'trainee_cv.mimes' => 'Only PDF format is allowed for CV.',
            'trainee_cv.max' => 'The CV size should not exceed 10240 KB.',
        ];
        //           dd($request->all());
        // Validation with custom messages
        $validatedData = $request->validate([
            'personal_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            'trainee_cv' => 'nullable|mimes:pdf|max:10240',
        ], $messages);

        $trainee = Trainee::findOrFail($id);
        $oldImage = $trainee->personal_img;
        $oldCv = $trainee->trainee_cv;
        $academy = $trainee->academy;
        $cohort = $trainee->cohort;

        // Image upload handling
        if ($request->hasFile('personal_img')) {
            $image = $request->file('personal_img');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);

            if ($oldImage && file_exists(public_path('images/' . $oldImage))) {
                unlink(public_path('images/' . $oldImage));
            }

            $trainee->id_img = $imageName;
        }

        if ($request->hasFile('trainee_cv')) {
            $cv = $request->file('trainee_cv');
            $cvName = time() . '.' . $cv->extension();
            $cv->move(public_path('cvs'), $cvName);

            if ($oldCv && file_exists(public_path('cvs/' . $oldCv))) {
                unlink(public_path('cvs/' . $oldCv));
            }

            $trainee->trainee_cv = $cvName;
        }

        // Save everything
        $trainee->fill($request->except('personal_img', 'trainee_cv', 'employment_status'));
        $trainee->save();

        return redirect()
            ->route('trainees.index', ['academy' => $academy, 'cohort' => $cohort])
            ->with('success', 'Trainee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trainee = Trainee::findOrFail($id);

        $trainee->delete();

        return redirect()->back()->with('success', 'Trainee deleted successfully');
    }

    public function logs($id)
    {
        $logs = EmploymentLog::where('trainee_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        $trainee = Trainee::findOrFail($id);

        return view('employment-status.logs', compact('logs', 'trainee'));
    }

    public function createLog($id)
    {
        $trainee = Trainee::findOrFail($id);
        $names_companies = Company::select('company_name')->distinct()->where('type_of_deal', '1')->orderBy('company_name')->get();
        return view('employment-status.create_log', compact('trainee', 'names_companies'));
    }

    public function storeLog(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'company' => in_array($request->status, ['available', 'Dropped']) ? 'nullable|string' : 'required|string',
            'position' => in_array($request->status, ['available', 'Dropped']) ? 'nullable|string' : 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            //            'created_by' => 'required|string',
        ]);
        // Check against all existing start_dates for this trainee
        $conflict = EmploymentLog::where('trainee_id', $id)
            ->where('start_date', '>', $request->start_date)
            ->exists();
        // dd($conflict);

        if ($conflict) {
            return back()
                ->withErrors(['start_date' => 'There is a conflict: you already entered a later date for this trainee.'])
                ->withInput();  // keeps the form data
        }
        $trainee = Trainee::findOrFail($id);
        $logEntryData = $request->all();
        $logEntryData['trainee_id'] = $trainee->id;
        $logEntryData['academy_id'] = $trainee->academy_id;
        $logEntryData['cohort_id'] = $trainee->cohort_id;
        $logEntryData['end_date'] = $request->has('has_end_date') ? $request->end_date : null;
        $logEntryData['created_by'] = auth()->user()->name;
        //           dd($logEntryData);

        if (in_array($request->status, ['available', 'Dropped'])) {
            $logEntryData['company'] = $request->company ?? 'N/A';
            $logEntryData['position'] = $request->position ?? 'N/A';
        }

        EmploymentLog::create($logEntryData);

        $status = strtolower(trim($request->status));
        $trainee->employment_status = in_array($status, ['job offer', 'internship_for_employment', 'freelance'])
            ? 'employed'
            : 'unemployed';
        $trainee->save();
        $trainees = Trainee::findOrFail($id);

        return redirect()->route('employment-status.logs', $id)->with('success', 'Log entry created successfully');
    }

    public function destroyLog($traineeId, $logId)
    {
        $log = EmploymentLog::findOrFail($logId);
        $log->delete();

        // Update trainee status to 'unemployed'
        $trainee = Trainee::findOrFail($traineeId);
        $trainee->employment_status = 'unemployed';
        $trainee->save();
        return redirect()->back()->with('success', 'Trainee log deleted successfully, status set to unemployed.');
    }

    // TraineeController.php
    public function showProfile($id)
    {
        $trainee = Trainee::findOrFail($id);

        return view('trainees.profile', compact('trainee'));
    }

    public function getProfileLink($traineeId)
    {
        return route('trainees.profile', ['id' => $traineeId]);
    }

    public function showAll()
    {
        $trainees = Trainee::with(['academy', 'cohort'])
            ->get();
        //    dd($trainees);
        return view('trainees.showAll', compact('trainees'));
    }

    public function importTrainees(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);

        $file = $request->file('file');

        Excel::import(new TraineeImport, $file);

        return redirect()->back()->with('success', 'Trainees imported successfully.');
    }

    public function export()
    {
        return Excel::download(new AllExport, 'trainees.xlsx');
    }

    public function filter(Request $request)
    {
        $filter = $request->input('filter');

        // Apply your filter logic here to fetch the filtered trainees
        $filteredTrainees = Trainee::where('first_name', 'like', "%$filter%")
            ->orWhere('last_name', 'like', "%$filter%")
            ->orWhere('email', 'like', "%$filter%")
            ->orWhere('mobile', 'like', "%$filter%")
            ->orWhere('id', 'like', "%$filter%")
            ->orWhere('certificat_type', 'like', "%$filter%")
            ->get();
        $academy = null;
        if ($request->has('academy_id')) {
            $academy = Academy::find($request->input('academy_id'));
        }

        return view('trainees.index', ['trainees' => $filteredTrainees, 'academy' => $academy]);
    }

    public function show_update_page($traineeId, $logId)
    {
        $trainee_info = Trainee::where('id', $traineeId)->firstOrFail();
        $trainee_log_info = EmploymentLog::where('id', $logId)->firstOrFail();
        $names_companies = Company::select('company_name')->distinct()->orderBy('company_name')->get();
        return view('employment-status.update_selected_trainee_page', compact('trainee_info', 'trainee_log_info', 'names_companies'));
    }

    public function updateLog(Request $request, $logId)
    {
        $request->validate([
            'status' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            //        'created_by' => 'required|string',
        ]);

        $traineeLog = EmploymentLog::findOrFail($logId);
        $trainee = Trainee::findOrFail($request->trainee_id);

        $status = strtolower(trim($request->status));

        // Handle Available / Dropped
        if (in_array($status, ['available', 'dropped'])) {
            $trainee->employment_status = 'unemployed';
            $traineeLog->company = 'N/A';
            $traineeLog->position = 'N/A';
        } else {
            $trainee->employment_status = 'employed';
            $traineeLog->company = $request->input('company');
            $traineeLog->position = $request->input('position') ?: 'N/A';
        }

        $traineeLog->status = $request->status;
        $traineeLog->start_date = $request->start_date;
        $traineeLog->end_date = $request->has('has_end_date') ? $request->end_date : null;
        $traineeLog->created_by = auth()->user()->name;

        $trainee->save();
        $traineeLog->save();

        return redirect()
            ->route('employment-status.logs', $request->trainee_id)
            ->with('success', 'Log updated successfully.');
    }
}
