@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="display-5 fw-bold mb-0">Company Profile</h1>
                <p class="text-muted">Viewing details for {{ $company->company_name }}</p>
            </div>
            <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-body text-center py-4">
                        @php
                            $imagePath = public_path('assets/co_icon/' . $company->company_img);
                            $imageUrl = (isset($company->company_img) && !empty($company->company_img) && $company->company_img !== 'null' && File::exists($imagePath))
                                        ? asset('assets/co_icon/' . $company->company_img)
                                        : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRbt4ZISe4q1wf5oxPp0TsOTqMm3fVvw-QvLGoGqNWOxevAyWplBqVcrbHuqc7IQj5I3d8&usqp=CAU';
                        @endphp
                        <img src="{{ $imageUrl }}" alt="Logo" class="rounded-circle mb-3 shadow-sm" width="120" height="120" style="object-fit: cover; border: 3px solid #f8f9fa;">

                        <h3 class="fw-bold mb-1">{{ $company->company_name }}</h3>
                        <p class="text-muted mb-3">{{ $company->company_email }}</p>

                        <div class="d-grid gap-2 col-10 mx-auto">
                            <span class="badge py-2 {{ $company->type_of_deal == 1 ? 'bg-success' : 'bg-danger' }}">
                                <i class="fas {{ $company->type_of_deal == 1 ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                {{ $company->type_of_deal == 1 ? 'Active Deal' : 'Inactive Deal' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-users me-2"></i>Associated Employers
                        </h5>
                        <a href="{{ route('employer.addEmployer', ['company_id' => $company->id]) }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                            <i class="fas fa-user-plus me-1"></i> Add Employer
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="width: 100%; table-layout: fixed;">
                                <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 30%;">Name</th>
                                    <th style="width: 40%;">Email</th>
                                    <th class="text-center" style="width: 30%;">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($company->employers as $employer)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark text-truncate">{{ $employer->name }}</td>
                                        <td class="text-truncate">{{ $employer->email }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('com_employer.edit', $employer->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>


                                                    <form action="{{ route('employees.destroy', ['id' => $employer->id]) }}" method="POST"
                                                          style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-user-slash fa-3x mb-3 opacity-25"></i>
                                                <p class="mb-0">No employers found for this company.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
