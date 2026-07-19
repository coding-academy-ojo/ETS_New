@extends('layouts.app')

@section('content')
<style>
.card {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card-img-top {
    width: 100%;
    height: 300px;
    line-height: 300px;
    vertical-align: middle;
}

.card-body {
    flex-grow: 1;
}

.card-footer {
    flex-shrink: 0;
}

.a_style {
    width: 30%;
    height: 150%;
}
</style>

<div class="container mt-5">
    <h1 class="mb-4 text-center display-4">Trainees List[p-</h1>

    <div class="d-flex justify-content-between mb-4">
        @if ($academy)
        <form action="{{ route('export.trainees', ['academy_id' => $academy->id, 'cohort_id' => $cohort->id]) }}" method="GET">
            @csrf
            <input type="hidden" name="academy_id" value="{{ $academy->id }}">
            <input type="hidden" name="cohort_id" value="{{ $cohort->id }}">
            <button type="submit" class="btn btn-primary">Export All Trainees</button>
        </form>
        @endif

        <form action="{{ route('trainees.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
            @csrf
            <div class="input-group">
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv">
                <button type="submit" class="btn btn-primary">Import Trainees</button>
            </div>
        </form>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row row-cols-2 row-cols-md-4 g-4">
        @foreach($trainees as $trainee)
        <div class="col">
            <div class="card shadow border rounded mb-3">
                @php
                    $isExternal = filter_var($trainee->id_img, FILTER_VALIDATE_URL);
                @endphp

                <img src="{{ $isExternal ? $trainee->id_img : asset('images/' . $trainee->id_img) }}"
                     class="card-img-top rounded-top" alt="Trainee Image"
                     title="{{ $trainee->first_name }} {{ $trainee->last_name }}">

                <span class="badge
                    @if ($trainee->employment_status === 'employed') bg-success
                    @else bg-danger
                    @endif" style="font-size:17px">
                    {{ ucfirst($trainee->employment_status) }}
                </span>

                <div class="card-body text-dark">
                    <h5 class="card-title mb-3 font-weight-bold d-flex justify-content-between align-items-center">
                        {{ $trainee->first_name }}
                        {{
                            $trainee->last_name !== 'null' ? $trainee->last_name :
                            ($trainee->second_name !== 'null' ? $trainee->second_name : $trainee->third_name)
                        }}
                    </h5>

                    <p class="card-text" style="margin-bottom: 8px;"><i class="fa-solid fa-graduation-cap"></i>
                        {{ $trainee->graduated }}</p>

                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <i class="fa-solid fa-envelope" style="margin-right: 5px;"></i>
                        <span class="card-text" style="font-size:15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $trainee->email }}
                        </span>
                    </div>

                    <p class="card-text" style="margin-bottom: 0;"><i class="fa-solid fa-mobile"></i>
                        {{ $trainee->mobile }}</p>
                </div>

                <div class="card-footer bg-white border-0 d-flex justify-content-center gap-1">
                    {{-- View Logs Button --}}
                    <a href="{{ route('employment-status.logs', ['id' => $trainee->id]) }}" class="btn btn-primary btn-sm a_style" title="View Employment Logs">
                        <i class="fa-solid fa-list"></i>
                    </a>

                    {{-- Edit Employment Status Button --}}
                    <a href="{{ route('employment-status.edit', $trainee->id) }}" class="btn btn-dark btn-sm a_style" title="Edit Employment Status">
                        <i class="bi bi-pencil-fill"></i>
                    </a>

                    {{-- View Trainee Profile Button --}}
                    <a href="{{ route('trainees.profile', ['id' => $trainee->id]) }}" class="btn btn-info btn-sm a_style" title="View Trainee Profile">
                        <i class="bi bi-eye-fill"></i>
                    </a>

                    {{-- Download CV Button --}}
                    @php
                        $cvFilename = basename($trainee->trainee_cv);
                        $isExternalCV = filter_var($trainee->trainee_cv, FILTER_VALIDATE_URL);
                        $cvLink = $isExternalCV ? $trainee->trainee_cv : asset('cvs/' . $cvFilename);

                    @endphp

                    <a href="{{ $cvLink }}" class="btn btn-success btn-sm a_style" title="Download CV" target="_blank">
                        <i class="bi bi-file-earmark-person"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $trainees->links() }}
    </div>
</div>
@endsection

<style>
.container {
    max-width: 1200px;
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
    height: 100%;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.btn-close {
    float: right;
}
</style>
