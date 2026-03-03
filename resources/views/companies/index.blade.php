@extends('layouts.app')

@section('content')
    <style>
        .container-fluid-custom {
            padding: 20px 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .management-bar {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            border: 1px solid #eef0f2;
        }

        .table-custom {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: none;
        }

        .table-custom th {
            color: #000;
            border-bottom: 2px solid #0056b3;
            background-color: #f8f9fa;
            font-weight: 700;
            padding: 15px;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .table-custom td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .table-custom tbody tr:hover {
            background-color: #f1f7ff;
        }

        .deactive-row {
            background-color: #f8d7da !important;
        }

        .co-logo {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        .bar-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #6c757d;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
        }

        /* Icon Button Styling */
        .btn-icon {
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }
    </style>

    <div class="container-fluid container-fluid-custom">

        <div class="page-header">
            <div>
                <h1 class="fw-bold mb-0">Companies</h1>
                <p class="text-muted mb-0">Manage organization list and deal statuses</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('companies.showAll') }}" class="btn btn-outline-primary px-4 py-2 fw-bold shadow-sm">
                    <i class="fas fa-users-cog me-2"></i>Manage All Employers
                </a>

                <a href="{{ route('addCompany') }}" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i>Add Company
                </a>
            </div>
        </div>

        <div class="management-bar">
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <span class="bar-label">Search Company Name</span>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-primary"><i class="fas fa-search text-primary"></i></span>
                        <input type="text" id="custom-search" class="form-control border-primary" placeholder="Type company name to search...">
                    </div>
                </div>
                <div class="col-md-6">
                    <span class="bar-label">Status Filter</span>
                    <select id="status-filter" class="form-select border-primary shadow-sm">
                        <option value="">Show All Statuses</option>
                        <option value="Active">Active Only</option>
                        <option value="Inactive">Inactive Only</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <form action="{{ route('import.companies') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <span class="bar-label">Import Companies</span>
                        <div class="input-group shadow-sm">
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-upload me-2"></i>Import
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <span class="bar-label">Data Action</span>
                    <a href="{{ route('export.companies') }}" class="btn btn-danger w-100 shadow-sm">
                        <i class="fas fa-file-excel me-2"></i>Export Companies
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 10px;">
                <table id="company-table" class="table table-custom mb-0">
                    <thead>
                    <tr>
                        <th style="width: 30%;">Company Name</th>
                        <th style="width: 10%;" class="text-center">Logo</th>
                        <th style="width: 30%;">Type of Deal</th>
                        <th style="width: 30%;" class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($companies as $company)
                        <tr class="{{ $company->type_of_deal == 0 ? 'deactive-row' : '' }}">
                            <td class="fw-bold text-dark">{{ $company->company_name }}</td>
                            <td class="text-center">
                                @php
                                    $imagePath = public_path('assets/co_icon/' . $company->company_img);
                                    $imageUrl = (isset($company->company_img) && !empty($company->company_img) && File::exists($imagePath))
                                                ? asset('assets/co_icon/' . $company->company_img)
                                                : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRbt4ZISe4q1wf5oxPp0TsOTqMm3fVvw-QvLGoGqNWOxevAyWplBqVcrbHuqc7IQj5I3d8&usqp=CAU';
                                @endphp
                                <img src="{{ $imageUrl }}" alt="Logo" class="co-logo shadow-sm">
                            </td>
                            <td data-filter="{{ $company->type_of_deal == 1 ? 'Active' : 'Inactive' }}">
                                <div id="typeOfDealForm-{{ $company->id }}">
                                    <select name="type_of_deal" class="form-select fw-bold" style="color: {{ $company->type_of_deal == 1 ? 'green' : 'red' }};" onchange="submitFormAndChangeColor({{ $company->id }})">
                                        <option value="1" {{ $company->type_of_deal == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $company->type_of_deal == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('companies.show', ['id' => $company->id]) }}"
                                       class="btn btn-info btn-icon shadow-sm text-white"
                                       title="Show Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('companies.edit', ['id' => $company->id]) }}"
                                       class="btn btn-primary btn-icon shadow-sm"
                                       title="Edit Company">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#company-table').DataTable({
                dom: 't<"d-flex justify-content-between p-3"ip>',
                lengthChange: false,
                ordering: true,
                columnDefs: [
                    { targets: [1, 3], searchable: false, orderable: false }
                ]
            });

            $('#custom-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#status-filter').on('change', function() {
                var val = $(this).val();
                if (val === "") {
                    table.column(2).search('').draw();
                } else {
                    table.column(2).search('^' + val + '$', true, false).draw();
                }
            });
        });

        function submitFormAndChangeColor(companyId) {
            const select = document.querySelector(`#typeOfDealForm-${companyId} select`);
            const newValue = select.value;
            const row = select.closest('tr');
            const cell = select.closest('td');

            // Show loading state
            select.disabled = true;
            select.style.opacity = '0.5';

            fetch(`{{ route('companies.updateTypeOfDeal', ':id') }}`.replace(':id', companyId), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    type_of_deal: newValue
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update Row styling
                    if (newValue == "0") {
                        row.classList.add('deactive-row');
                        select.style.color = 'red';
                    } else {
                        row.classList.remove('deactive-row');
                        select.style.color = 'green';
                    }

                    // Update DataTables metadata for filtering
                    const api = $('#company-table').DataTable();
                    const dtRow = api.row(row);
                    cell.setAttribute('data-filter', newValue == "1" ? 'Active' : 'Inactive');
                    api.cell(cell).invalidate().draw(false);

                    // Optional: Show a subtle toast or message
                    console.log('Status updated successfully');
                } else {
                    alert('Error updating status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                select.disabled = false;
                select.style.opacity = '1';
            });
        }
    </script>
@endsection
