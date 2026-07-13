<?php

namespace App\Http\Controllers;

use App\Exports\CompaniesExport;
use App\Imports\CompaniesImport;
use App\Models\Company;
use App\Models\Cohort;
use App\Models\Employer;
use App\Models\EmploymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::get();
        // dd($companies);
        return view('companies.index', compact('companies'));
    }

    public function statistics(Request $request)
    {
        $selectedCompany = $request->input('company');
        $selectedCohort = $request->input('cohort_id');
        $selectedStatus = $request->input('status');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Base queries for filter options
        $companies = Company::orderBy('company_name')->get();
        $cohorts = Cohort::orderBy('name')->get();
        $statuses = EmploymentLog::select('status')->distinct()->whereNotNull('status')->pluck('status');

        // Base query for stats computation on employment_logs
        $query = EmploymentLog::query()
            ->join('trainees', 'employment_logs.trainee_id', '=', 'trainees.id')
            ->leftJoin('companies', 'companies.company_name', '=', 'employment_logs.company');

        // Apply filters
        if ($selectedCompany) {
            $query->where('employment_logs.company', $selectedCompany);
        }
        if ($selectedCohort) {
            $query->where('employment_logs.cohort_id', $selectedCohort);
        }
        if ($selectedStatus) {
            if ($selectedStatus === 'job offer') {
                $query->whereIn('employment_logs.status', ['job offer', 'Job Offer']);
            } elseif ($selectedStatus === 'internship_for_employment') {
                $query->whereIn('employment_logs.status', ['internship for employment', 'internship_for_employment', 'Internship for Employment', 'internship_for_Employment']);
            } elseif ($selectedStatus === 'freelance') {
                $query->whereIn('employment_logs.status', ['freelance', 'Freelance']);
            } else {
                $query->where('employment_logs.status', $selectedStatus);
            }
        }
        if ($fromDate) {
            $query->whereDate('employment_logs.start_date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('employment_logs.start_date', '<=', $toDate);
        }

        // Clone query for KPI computations
        $totalCompaniesCount = (clone $query)->distinct('employment_logs.company')->count('employment_logs.company');
        
        $activeCompaniesCount = (clone $query)
            ->where('companies.type_of_deal', 1)
            ->distinct('employment_logs.company')
            ->count('employment_logs.company');

        $inactiveCompaniesCount = $totalCompaniesCount - $activeCompaniesCount;

        $totalHires = (clone $query)
            ->whereIn('employment_logs.status', ['job offer', 'internship for employment', 'freelance', 'internship_for_employment', 'Job Offer', 'Internship for Employment', 'Freelance'])
            ->distinct('employment_logs.trainee_id')
            ->count('employment_logs.trainee_id');

        $averageHiresPerCompany = $totalCompaniesCount > 0 ? round($totalHires / $totalCompaniesCount, 2) : 0;

        $firstHiringDate = (clone $query)
            ->whereIn('employment_logs.status', ['job offer', 'internship for employment', 'freelance', 'internship_for_employment', 'Job Offer', 'Internship for Employment', 'Freelance'])
            ->min('employment_logs.start_date');

        $latestHiringDate = (clone $query)
            ->whereIn('employment_logs.status', ['job offer', 'internship for employment', 'freelance', 'internship_for_employment', 'Job Offer', 'Internship for Employment', 'Freelance'])
            ->max('employment_logs.start_date');

        // Total employers count associated with filtered companies
        $filteredCompanies = (clone $query)->distinct('employment_logs.company')->pluck('employment_logs.company');
        $totalEmployers = Employer::whereIn('company_name', $filteredCompanies)->count();

        // 2. Top Hiring Partners
       $companyHires = (clone $query)
    ->select('employment_logs.company as company_name')
    ->selectRaw('COUNT(DISTINCT employment_logs.trainee_id) as trainees_count')
    ->whereIn('employment_logs.status', [
        'job offer',
        'internship for employment',
        'freelance',
    ])
    ->groupBy('employment_logs.company')
    ->orderByDesc('trainees_count')
    ->get();

        $totalHiresCount = $companyHires->sum('trainees_count');

        // 3. Placements by Academy
        $academyHires = (clone $query)
    ->join('academies', 'employment_logs.academy_id', '=', 'academies.id')
    ->select('academies.name as academy_name')
    ->selectRaw('COUNT(DISTINCT employment_logs.trainee_id) as hires_count')
    ->whereIn('employment_logs.status', [
        'job offer',
        'internship for employment',
        'freelance',
        'internship_for_employment',
        'Job Offer',
        'Internship for Employment',
        'Freelance',
    ])
    ->groupBy('academies.name')
    ->orderByDesc('hires_count')
    ->get();

        // 4. Placements by Gender
      $genderHires = (clone $query)
    ->select('trainees.gender')
    ->selectRaw('COUNT(DISTINCT employment_logs.trainee_id) as hires_count')
    ->whereIn('employment_logs.status', [
        'job offer',
        'internship for employment',
        'freelance',
        'internship_for_employment',
        'Job Offer',
        'Internship for Employment',
        'Freelance',
    ])
    ->groupBy('trainees.gender')
    ->get();

        // 5. Complete Companies List (Detailed Table data)
        $rawCompaniesList = (clone $query)
    ->select(
        'employment_logs.company as company_name',
        'companies.company_email',
        'companies.type_of_deal'
    )
    ->selectRaw('MAX(employment_logs.start_date) as last_placement_date')
    ->groupBy(
        'employment_logs.company',
        'companies.company_email',
        'companies.type_of_deal'
    )
    ->get();

        $companiesList = $rawCompaniesList->map(function ($item) use ($query) {
            // Count employers for this company
            $item->employers_count = Employer::where('company_name', $item->company_name)->count();

            // Count trainees hired for this company under the current query filters
            $item->trainees_hired = (clone $query)
                ->where('employment_logs.company', $item->company_name)
                ->whereIn('employment_logs.status', ['job offer', 'internship for employment', 'freelance'])
                ->distinct('employment_logs.trainee_id')
                ->count('employment_logs.trainee_id');

            return $item;
        })->sortByDesc('trainees_hired');

        // Monthly Trends
        $monthlyTrends = (clone $query)
    ->whereIn('employment_logs.status', [
        'job offer',
        'internship for employment',
        'freelance',
        'internship_for_employment',
        'Job Offer',
        'Internship for Employment',
        'Freelance',
    ])
    ->selectRaw("DATE_FORMAT(employment_logs.start_date, '%Y-%m') as formatted_month")
    ->selectRaw("COUNT(DISTINCT employment_logs.trainee_id) as count")
    ->groupBy('formatted_month')
    ->orderBy('formatted_month')
    ->get();

        // Yearly Trends
        $yearlyTrends = (clone $query)
    ->whereIn('employment_logs.status', [
        'job offer',
        'internship for employment',
        'freelance',
        'internship_for_employment',
        'Job Offer',
        'Internship for Employment',
        'Freelance',
    ])
    ->selectRaw("DATE_FORMAT(employment_logs.start_date, '%Y') as formatted_year")
    ->selectRaw("COUNT(DISTINCT employment_logs.trainee_id) as count")
    ->groupBy('formatted_year')
    ->orderBy('formatted_year')
    ->get();

        // Cohort Hires by Company (with Academies join)
        $cohortCompanyHires = (clone $query)
    ->join('cohorts', 'employment_logs.cohort_id', '=', 'cohorts.id')
    ->join('academies', 'employment_logs.academy_id', '=', 'academies.id')
    ->select(
        'employment_logs.company as company_name',
        'academies.name as academy_name',
        'cohorts.name as cohort_name'
    )
    ->selectRaw('COUNT(DISTINCT employment_logs.trainee_id) as hires_count')
    ->whereIn('employment_logs.status', [
        'job offer',
        'internship for employment',
        'freelance',
    ])
    ->groupBy(
        'employment_logs.company',
        'academies.name',
        'cohorts.name'
    )
    ->get();

        // Top Companies by Cohort Engagement
        $companyCohortEngagement = (clone $query)
    ->join('cohorts', 'employment_logs.cohort_id', '=', 'cohorts.id')
    ->select('employment_logs.company as company_name')
    ->selectRaw('COUNT(DISTINCT employment_logs.cohort_id) as cohorts_count')
    ->selectRaw('COUNT(DISTINCT employment_logs.trainee_id) as total_hires')
    ->whereIn('employment_logs.status', [
        'job offer',
        'internship for employment',
        'freelance',
        'internship_for_employment',
        'Job Offer',
        'Internship for Employment',
        'Freelance',
    ])
    ->groupBy('employment_logs.company')
    ->orderByDesc('cohorts_count')
    ->orderByDesc('total_hires')
    ->take(10)
    ->get();

        return view('companies.statistics', compact(
            'companies',
            'cohorts',
            'statuses',
            'selectedCompany',
            'selectedCohort',
            'selectedStatus',
            'fromDate',
            'toDate',
            'totalCompaniesCount',
            'activeCompaniesCount',
            'inactiveCompaniesCount',
            'totalEmployers',
            'totalHires',
            'companyHires',
            'totalHiresCount',
            'academyHires',
            'genderHires',
            'companiesList',
            'averageHiresPerCompany',
            'firstHiringDate',
            'latestHiringDate',
            'monthlyTrends',
            'yearlyTrends',
            'cohortCompanyHires',
            'companyCohortEngagement'
        ));
    }

    public function show($id)
    {
        $company = Company::with([
            'employers' => function ($query) {
                $query->where('status', 'active');  // or 1
            }
        ])->findOrFail($id);
        return view('companies.show', compact('company'));
    }

    public function addCompany()
    {
        return view('companies.addCompany');
    }

    public function store(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_img' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        ]);

        $validatedData['type_of_deal'] = 1;  // Default type

        try {
            // Handle file upload
            if ($request->hasFile('company_img')) {
                $image = $request->file('company_img');

                // Define the folder path (inside /public)
                $destinationPath = public_path('assets/co_icon');

                // Create folder if it doesn’t exist
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }

                // Generate a unique file name
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Move the image to /public/assets/co_icon
                $image->move($destinationPath, $imageName);

                // Store only file name in DB
                $validatedData['company_img'] = $imageName;
            } else {
                $validatedData['company_img'] = 'null';
            }

            // Create the company record
            Company::create($validatedData);

            return redirect()->route('companies.index')->with('success', 'Company created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create company. Please try again.');
        }
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        //        dd($request->all(), $request->file('company_img'));
        $company = Company::findOrFail($id);

        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|string|email|max:255',
            'type_of_deal' => 'nullable|boolean',
            'company_img' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        ]);

        // Convert checkbox to 1 or 0
        $validatedData['type_of_deal'] = $request->has('type_of_deal') ? 1 : 0;

        // ✅ Handle image update
        if ($request->hasFile('company_img')) {
            $image = $request->file('company_img');
            $destinationPath = public_path('assets/co_icon');

            // Create directory if not exists
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            // Delete old image if exists
            if ($company->company_img && $company->company_img !== 'null' && File::exists($destinationPath . '/' . $company->company_img)) {
                File::delete($destinationPath . '/' . $company->company_img);
            }

            // Save new image
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);

            $validatedData['company_img'] = $imageName;
        }

        // ✅ Update company
        $company->update($validatedData);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully!');
    }

    public function destroy($companyId)
    {
        // Find the company by its ID
        $company = Company::findOrFail($companyId);

        // Delete the company
        $company->delete();

        // Redirect with a success message
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

    public function export()
    {
        return Excel::download(new CompaniesExport, 'companies.xlsx');
    }

    public function import()
    {
        Excel::import(new CompaniesImport, request()->file('file'));

        return redirect()->back()->with('success', 'Companies imported successfully!');
    }

    public function showAllEmployees()
    {
        $employees = Employer::where('status', 'active')
            ->whereHas('company', function ($query) {
                $query->where('type_of_deal', 1);
            })
            ->with('company')
            ->get();

        return view('companies.showAll', compact('employees'));
    }

    // registration new employer
    public function addEmployer(Request $request)
    {
        //    dd($request->all());
        $selectedCompanyId = $request->query('company_id');

        // Fetch only the selected company
        $selectedCompany = Company::select('id', 'company_name')
            ->where('id', $selectedCompanyId)
            ->first();

        // Pass it to the view
        return view('companies.AddEmployer', compact('selectedCompany'));
    }

    // public function registration(){
    //     return view('companies.AddEmployer');
    // }
    public function storeEmployer(Request $request)
    {
        //     dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company_id' => 'required|exists:companies,id',  // validate that company exists
        ]);

        $company = Company::find($request->company_id);

        Employer::create([
            'name' => $request->name,
            'email' => $request->email,
            'company_name' => $company->company_name,
            'company_id' => $company->id,
        ]);

        return redirect()->back()->with('success', 'Employer created successfully!');
    }

    public function filter(Request $request)
    {
        $filter = $request->input('filter');

        if ($filter) {
            // Apply your filter logic here to fetch the filtered companies
            $companies = Company::where('company_name', 'like', "%$filter%")
                ->orWhere('company_email', 'like', "%$filter%")
                ->orWhere('type_of_deal', 'like', "%$filter%")
                ->paginate(10);
            // Adjust the number of items per page as needed
        } else {
            // Get all companies if no filter is present
            $companies = Company::paginate(100);  // Adjust the number of items per page as needed
        }

        return view('companies.index', compact('companies', 'filter'));
    }

    public function updateTypeOfDeal(Request $request, $id)
    {
        // dd($request);
        $company = Company::findOrFail($id);
        $company->type_of_deal = $request->input('type_of_deal');
        $company->save();

        // Update the visibility of employees based on type_of_deal
        $status = $company->type_of_deal == 1 ? 'active' : 'inactive';

        $company->employers()->update(['status' => $status]);

        session()->flash('success', 'Company status updated successfully!');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Company and employee status updated successfully!',
                'type_of_deal' => $company->type_of_deal
            ]);
        }

        return redirect()->back()->with('success', 'Company and employee status updated successfully!');
    }

    public function showCompaniesForm()
    {
        $companies = Company::select('company_name')->distinct()->orderBy('company_name')->get();
        return view('employment-status.create_log', compact('companies'));
    }

    public function searchCompanies(Request $request)
    {
        $query = $request->get('q');
        $companies = Company::where('company_name', 'LIKE', "%{$query}%")->get();

        return response()->json($companies);
    }
}
