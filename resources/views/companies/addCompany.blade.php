@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Company</h1>
        <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" name="company_name" id="company_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="company_img" class="form-label">Company Logo</label>
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
                         src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRbt4ZISe4q1wf5oxPp0TsOTqMm3fVvw-QvLGoGqNWOxevAyWplBqVcrbHuqc7IQj5I3d8&usqp=CAU"
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

            <button type="submit" id="createBtn" class="btn btn-primary">
                Create Company
            </button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const fileInput = document.getElementById('company_img');
            const errorText = document.getElementById('file_error');
            const createBtn = document.getElementById('createBtn');
            const previewImg = document.getElementById('preview_img');

            fileInput.addEventListener('change', function() {
                const file = this.files[0];

                if (!file) {
                    errorText.style.display = 'none';
                    createBtn.disabled = false;
                    // Reset to placeholder if no file selected
                    previewImg.src = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRbt4ZISe4q1wf5oxPp0TsOTqMm3fVvw-QvLGoGqNWOxevAyWplBqVcrbHuqc7IQj5I3d8&usqp=CAU";
                    return;
                }

                const fileName = file.name.toLowerCase();
                const allowedExtensions = /\.(png|jpe?g|gif)$/;
                const allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];

                if (!allowedExtensions.test(fileName) || !allowedTypes.includes(file.type)) {
                    errorText.style.display = 'block';
                    this.value = ''; // Clear invalid file
                    createBtn.disabled = true;
                    // Reset preview to placeholder
                    previewImg.src = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRbt4ZISe4q1wf5oxPp0TsOTqMm3fVvw-QvLGoGqNWOxevAyWplBqVcrbHuqc7IQj5I3d8&usqp=CAU";
                } else {
                    errorText.style.display = 'none';
                    createBtn.disabled = false;

                    // Show live preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
