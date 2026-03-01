<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use App\Models\Cohort;
use App\Models\Fund;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $funds = Fund::latest()->get();

        foreach ($funds as $fund) {
            $start = Carbon::parse($fund->start_date);

            if (!$fund->end_date) {
                $fund->period = 'Present';
                $fund->status = 'Active';
            } else {
                $end = Carbon::parse($fund->end_date);
                $fund->period = $start->diffForHumans($end, [
                    'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
                    'parts' => 2,
                    'join' => true,
                ]);
                // Remove prefix/suffix for a cleaner '1 year, 2 months' style
                $fund->period = str_replace([' before', ' after', ' ago', ' from now'], '', $fund->period);

                $fund->status = Carbon::now()->greaterThan($end) ? 'Expired' : 'Active';
            }
        }

        return view('Fund.show_funds', compact('funds'));
    }

    function create_fund()
    {
        return view('Fund.createFund');
    }

    function store_new_fund(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'fund_name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        // dd("qq");
        // Store fund in DB, set end_date to null if not provided

        // $table->string('fund_name');
        //     $table->timestamps();
        //     $table->date('start_date')->nullable();
        //     $table->date('end_date')->nullable();
        $qq = Fund::create([
            'fund_name' => $validated['fund_name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
        ]);

        return redirect()->route('fund.manageFund')->with('success', 'Fund entry created successfully');
    }

    function update_fund_data(Request $request, $id)
    {
        $fund_details = Fund::findOrFail($id);
        return view('Fund.update_fund', ['fund_details' => $fund_details]);
    }

    public function fund_store_data(Request $request, $id)
    {
        $fund_update_data = Fund::findOrFail($id);

        // Prepare updated data
        $fund_update_data->fund_name = $request->fund_name;
        $fund_update_data->start_date = $request->start_date;
        $fund_update_data->end_date = $request->has('has_end_date') ? $request->end_date : null;

        // Save changes to the database
        $fund_update_data->save();

        // Redirect with success message
        return redirect()
            ->route('fund.fund_update_info', $id)
            ->with('success', 'Fund entry updated successfully');
    }

    /**
     * // COOHORT FUND
     */
    function show_cohort_fund($id)
    {
        //        dd($id);
        $dataCohorts = Cohort::where('fund_id', $id)->get();
        $fund_cohort = Fund::findOrFail($id);
        //        dd($dataCohorts);
        return view('Fund.cohort', ['dataCohorts' => $dataCohorts, 'fund_cohort' => $fund_cohort]);
    }

    function delete_cohort_fund($id)
    {
        // dd($id);
        $cohort = Cohort::findOrfail($id);
        $cohort->technology = 0;
        $cohort->save();
        return back()->with('success', 'Delete cohort successfully');
    }

    function update_cohort_fund($id)
    {
        // dd("hello");
        $academies = Academy::all();
        $cohorts = Cohort::all();
        // Get the selected cohort from fund_id (example assumes fund has cohort_id)
        $selectedCohort = Cohort::where('id', $id)->first();
        // dd($selectedCohort);
        return view('Fund.editCohort', compact('academies', 'cohorts', 'selectedCohort'));
    }

    function store_update_cohort_fund(Request $request, $id)
    {
        $validated = $request->validate([
            'academy' => 'required|exists:academies,id',
            'cohort' => 'required|exists:cohorts,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        // Find the cohort to update
        $cohort = Cohort::findOrFail($id);

        // Update cohort data
        $cohort->academy_id = $validated['academy'];
        $cohort->start_date = $validated['start_date'];
        $cohort->end_date = $validated['end_date'] ?? null;

        // Fetch the selected cohort to get its slug
        $selectedCohort = Cohort::find($validated['cohort']);
        if ($selectedCohort) {
            $cohort->slug = $selectedCohort->slug;
        }

        $cohort->save();

        return back()->with('success', 'update cohort data successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    function create_cohort_fund($id)
    {
        // dd($id);
        $academies = Academy::all();
        $cohorts = Cohort::all();
        return view('Fund.create_new_cohort', ['academies_cohort' => $academies, 'fund_id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_new_cohort_fund(Request $request)
    {
        //     dd($request);
        $validated = $request->validate([
            'academy' => 'required|exists:academies,id',
            'fund_id' => 'required|exists:funds,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        // Step 1: Get the academy
        $academy = Academy::findOrFail($validated['academy']);

        // Step 2: Get all cohort names for this academy
        $cohorts = Cohort::where('academy_id', $academy->id)
            ->pluck('name')
            ->toArray();

        // Step 3: Extract numeric parts
        $maxNumber = 0;
        foreach ($cohorts as $name) {
            if (preg_match('/\d+$/', $name, $matches)) {
                $num = (int) $matches[0];
                if ($num > $maxNumber) {
                    $maxNumber = $num;
                }
            }
        }

        $nextNumber = $maxNumber + 1;

        // Step 4: Prepare new name
        $cohortName = 'cohort ' . $nextNumber;

        // Step 5: Prepare slug as academyname_number (lowercase)
        $slug = strtolower(str_replace(' ', '_', $academy->name)) . '_' . $nextNumber;

        // Step 6: Create the cohort
        Cohort::create([
            'number' => $nextNumber,
            'name' => $cohortName,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'academy_id' => $academy->id,
            'slug' => $slug,
            'fund_id' => $request->fund_id,
            'cohort_status' => 0
        ]);

        return back()->with('success', 'update cohort data successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
