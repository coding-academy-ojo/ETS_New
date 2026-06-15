@extends('layouts.app')

@section('content')
    <style>
        /* --- BRANDED ODS VARIABLES --- */
        :root {
            --ods-orange-100: #FF7900;
            --ods-orange-200: #F16E00;
            --ods-white:      #FFFFFF;
            --ods-black:      #000000;
            --ods-gray-100:   #FAFAFA;
            --ods-gray-200:   #F6F6F6;
            --ods-gray-300:   #EEEEEE;
            --ods-gray-400:   #DDDDDD;
            --ods-gray-500:   #CCCCCC;
            --ods-gray-600:   #999999;
            --ods-gray-700:   #666666;
            --ods-gray-800:   #595959;
            --ods-gray-900:   #333333;
            --ods-p-bg:       #F9FAFB;
            --ods-border:     var(--ods-gray-400);
            --ods-success:    #228722;
            --ods-danger:     #CD3C14;
        }

        .container-fluid-custom {
            padding: 1.5rem; /* Reduced for laptop screens */
            background-color: var(--ods-p-bg);
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        @media (max-width: 1400px) {
            .container-fluid-custom {
                padding: 1.25rem;
            }
        }

        .page-header {
            margin-bottom: 2.5rem;
            border-left: 5px solid var(--ods-orange-100);
            padding-left: 1.5rem;
        }

        .search-card {
            background: var(--ods-white);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--ods-border);
            margin-bottom: 1.5rem;
        }

        .search-input {
            height: 42px;
            border-radius: 8px;
            border: 1px solid var(--ods-border);
            padding: 0 0.75rem;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: var(--ods-white);
            font-weight: 500;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--ods-orange-100);
            box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.1);
        }

        .filter-label {
            font-size: 0.75rem; 
            font-weight: 800;
            text-transform: uppercase;
            color: var(--ods-gray-700);
            margin-bottom: 2px;
            display: block;
            letter-spacing: 0.02em;
        }

        .table-container {
            background: var(--ods-white);
            border-radius: 16px;
            border: 1px solid var(--ods-border);
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .table-custom thead th {
            background: var(--ods-black);
            color: var(--ods-white);
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1.25rem 1.5rem;
            border: none;
        }

        .table-custom tbody td {
            padding: 1.1rem 1.5rem;
            font-size: .95rem;
            border-bottom: 1px solid var(--ods-gray-300);
            vertical-align: middle;
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .table-custom tbody tr:hover {
            background-color: var(--ods-gray-100);
        }

        .deactive-row {
            background-color: #FFF5F5 !important;
            opacity: 0.8;
        }

        .co-logo {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--ods-border);
            padding: 2px;
            background: white;
        }

        .btn-icon {
            width: 38px;
            height: 38px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .btn-ods-primary {
            background-color: var(--ods-orange-100);
            border-color: var(--ods-orange-100);
            color: white;
            font-weight: 700;
        }
        .btn-ods-primary:hover {
            background-color: var(--ods-orange-200);
            border-color: var(--ods-orange-200);
            color: white;
        }
    </style>

    <div class="container-fluid container-fluid-custom">

        <header class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold m-0">Companies Management</h2>
                <p class="text-muted small mb-0 mt-1 uppercase fw-bold" style="letter-spacing: 0.05em;">Organization list & deal statuses</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('companies.showAll') }}" class="btn btn-outline-dark fw-bold">
                    <i class="fas fa-users-cog me-2"></i>Manage All Employers
                </a>
                <a href="{{ route('addCompany') }}" class="btn btn-dark fw-bold">
                    <i class="fas fa-plus me-2"></i>Add Company
                </a>
            </div>
        </header>

        <div class="search-card">
            <div class="row g-4 align-items-end">
                <div class="col-md-4">
                    <label class="filter-label">Search Company</label>
                    <div class="position-relative">
                        <input type="text" id="custom-search" class="form-control search-input w-100" placeholder="Type name...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="filter-label">Status Filter</label>
                    <select id="status-filter" class="form-select search-input">
                        <option value="">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="filter-label">Import Data</label>
                    <form action="{{ route('import.companies') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                        @csrf
                        <input type="file" name="file" class="form-control search-input pt-2" style="font-size: 0.8rem;" accept=".xlsx,.xls,.csv" required>
                        <button type="submit" class="btn btn-dark btn-icon" title="Import">
                            <i class="fas fa-upload"></i>
                        </button>
                    </form>
                </div>
                <div class="col-md-3">
                    <label class="filter-label">Export Data</label>
                    <a href="{{ route('export.companies') }}" class="btn btn-outline-dark fw-bold w-100 d-flex align-items-center justify-content-center" style="height: 42px; border-radius: 8px;">
                        <i class="fas fa-file-excel me-2"></i>Export Companies
                    </a>
                </div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-responsive">
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
                                        $imageUrl = (isset($company->company_img) && !empty($company->company_img) && $company->company_img !== 'null' && File::exists($imagePath))
                                                    ? asset('assets/co_icon/' . $company->company_img)
                                                    : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRbt4ZISe4q1wf5oxPp0TsOTqMm3fVvw-QvLGoGqNWOxevAyWplBqVcrbHuqc7IQj5I3d8&usqp=CAU';
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="Logo" class="co-logo">
                                </td>
                                <td data-filter="{{ $company->type_of_deal == 1 ? 'Active' : 'Inactive' }}">
                                    <div id="typeOfDealForm-{{ $company->id }}">
                                        <select name="type_of_deal" class="form-select border-0 bg-transparent fw-bold" 
                                                style="color: {{ $company->type_of_deal == 1 ? 'var(--ods-success)' : 'var(--ods-danger)' }}; cursor: pointer;" 
                                                onchange="submitFormAndChangeColor({{ $company->id }})">
                                            <option value="1" {{ $company->type_of_deal == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $company->type_of_deal == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('companies.show', ['id' => $company->id]) }}"
                                           class="btn btn-outline-dark btn-icon"
                                           title="Show Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('companies.edit', ['id' => $company->id]) }}"
                                           class="btn btn-dark btn-icon"
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
                        select.style.color = 'var(--ods-danger)';
                    } else {
                        row.classList.remove('deactive-row');
                        select.style.color = 'var(--ods-success)';
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
