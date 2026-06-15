@extends('layouts.app')

@section('content')
    @php
        use Illuminate\Support\Facades\File;

        // Determine the image URL or fallback to placeholder
        $imagePath = public_path('assets/co_icon/' . $company->company_img);
        $imageUrl = (isset($company->company_img) && $company->company_img !== 'null' && File::exists($imagePath))
                    ? asset('assets/co_icon/' . $company->company_img)
                    : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRbt4ZISe4q1wf5oxPp0TsOTqMm3fVvw-QvLGoGqNWOxevAyWplBqVcrbHuqc7IQj5I3d8&usqp=CAU';
    @endphp

    <div class="container">
        <h1>Edit Company</h1>

        <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $company->company_name }}">
            </div>

            <div class="form-group">
                <label for="company_email">Company Email</label>
                <input type="email" class="form-control" id="company_email" name="company_email" value="{{ $company->company_email }}">
            </div>

            <div class="form-group">
                <label for="company_img" class="form-label">Company Icon</label>
                <div class="d-flex align-items-center gap-3">
                    <input
                        type="file"
                        accept="image/png, image/jpeg, image/gif"
                        name="company_img"
                        id="company_img"
                        class="form-control"
                    >
                    <!-- Preview image -->
                    <img id="preview_img"
                         src="{{ $imageUrl }}"
                         alt="Preview"
                         width="50" height="50"
                         class="rounded"
                         style="object-fit: cover;">
                </div>
                <small class="form-text text-muted">
                    Please upload an image file in PNG, JPEG, or GIF format.
                </small>
                <small id="file_error" class="text-danger" style="display: none;">
                    Invalid file type. Please upload an image with PNG, JPEG, or GIF extension.
                </small>
            </div>

            <button type="submit" id="updateBtn" class="btn btn-primary">
                Update Company
            </button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fileInput = document.getElementById('company_img');
            const preview = document.getElementById('preview_img');
            const error = document.getElementById('file_error');
            const updateBtn = document.getElementById('updateBtn');

            // Initially enable button
            updateBtn.disabled = false;

            fileInput.addEventListener('change', function() {
                const file = this.files[0];

                if (!file) {
                    // No file selected, keep preview as is, enable button
                    preview.style.display = 'block';
                    error.style.display = 'none';
                    updateBtn.disabled = false;
                    return;
                }

                const allowedExtensions = ['image/png', 'image/jpeg', 'image/gif'];

                if (!allowedExtensions.includes(file.type)) {
                    error.style.display = 'block';
                    preview.style.display = 'none';
                    this.value = '';
                    updateBtn.disabled = true; // disable button if file invalid
                    return;
                }

                error.style.display = 'none';
                updateBtn.disabled = false; // enable button
                // Show new preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
