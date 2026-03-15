<?php

namespace App\Http\Controllers;

use App\Exports\CompaniesExport;
use App\Imports\CompaniesImport;
use App\Models\Company;
use App\Models\Employer;
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
            if ($company->company_img && File::exists($destinationPath . '/' . $company->company_img)) {
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
