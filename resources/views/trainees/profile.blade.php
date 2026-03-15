@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Inter', sans-serif;
        }

        .profile-page-wrapper {
            padding-top: 20px;
            padding-bottom: 40px;
        }

        .profile-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: auto;
            overflow: hidden;
            border: 1px solid #eee;
        }

        .profile-hero {
            background: linear-gradient(to right, #007bff, #0056b3);
            padding: 40px 20px 30px; /* Reduced from 80px */
            text-align: center;
            color: white;
            position: relative;
        }

        .profile-hero-img {
            width: 120px; /* Reduced from 150px */
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            background-color: #f0f2f5;
            display: block;
            margin: 0 auto 10px;
        }

        .profile-hero h3 {
            font-size: 1.8rem; /* Reduced from 2.5rem */
            font-weight: 700;
            margin-top: 10px;
        }

        .profile-hero p {
            font-size: 1.2rem;
            opacity: 0.95;
            margin-bottom: 0;
        }

        .profile-info-section {
            padding: 30px;
            background-color: #fff;
        }

        .info-card {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            display: flex;
            flex-direction: column;
        }

        .info-card-header {
            background-color: #f7f7f7;
            padding: 18px 25px;
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-card-body {
            padding: 25px;
            flex-grow: 1;
        }

        .info-card-body p {
            margin-bottom: 12px;
            font-size: 1rem;
            color: #444;
            line-height: 1.4;
        }

        .info-card-body p strong {
            display: inline-block;
            min-width: 130px;
            color: #222;
            font-weight: 600;
        }

        .social-links {
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: auto;
        }

        .profile-social-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            margin-right: 20px;
        }

        .profile-social-link .fa-brands {
            font-size: 1.5em;
            margin-right: 8px;
            color: #666;
        }

        .profile-social-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .btn-action {
            padding: 10px 30px;
            font-weight: 500;
            border-radius: 50px;
            margin: 10px 5px;
            display: inline-block;
        }
    </style>

    <div class="container profile-page-wrapper">
        <div class="profile-container">
            {{-- Profile Header --}}
            <div class="profile-hero">
                @php
                    $isExternal = filter_var($trainee->id_img, FILTER_VALIDATE_URL);
                    $imagePath = $isExternal ? $trainee->id_img : asset('images/' . $trainee->id_img);
                @endphp
                <img src="{{ $imagePath }}" alt="Trainee Profile" class="profile-hero-img">
                <h3>
                    {{ $trainee->first_name }}
                    {{
                        $trainee->last_name !== 'null' ? $trainee->last_name :
                        ($trainee->second_name !== 'null' ? $trainee->second_name : $trainee->third_name)
                    }}
                </h3>
                <p>{{ $trainee->certificat_type ?? 'Trainee' }}</p>
            </div>

            {{-- Main Info --}}
            <div class="profile-info-section">
                <div class="row">
                    {{-- Basic Information --}}
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-card-header">Basic Information</div>
                            <div class="info-card-body">
                                <p><strong>Email:</strong> {{ $trainee->email ?? 'N/A' }}</p>
                                <p><strong>Mobile:</strong> {{ $trainee->mobile ?? 'N/A' }}</p>
                                <p><strong>Country:</strong> {{ $trainee->country ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Educational Background --}}
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-card-header">Educational Background</div>
                            <div class="info-card-body">
                                @php
                                    $education = $trainee->education ?? '';
                                    $educationalStatus = $trainee->educational_status ?? '';
                                    $field = $trainee->field ?? '';
                                    $graduated = $trainee->graduated ?? '';
                                @endphp

                                <p><strong>Higher Education:</strong>
                                    @if(in_array($education, ['IT', 'Non-IT']))
                                        {{ $educationalStatus ?: 'N/A' }}
                                    @elseif(in_array($educationalStatus, ['Graduated', 'Non-Graduated']))
                                        {{ $field ?: 'N/A' }}
                                    @elseif(!$field)
                                        {{ $trainee->certificat_type ?? 'N/A' }}
                                    @else
                                        {{ $field }}
                                    @endif
                                </p>

                                {{-- Always show Graduated status --}}
                                <p><strong>Graduated:</strong> {{ $graduated ?: 'N/A' }}</p>

                                {{-- Always show Certificate --}}
                                <p><strong>Certificate:</strong> {{ $trainee->certificat_type ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Social Media --}}
                <div class="row">
                    <div class="col-12">
                        <div class="info-card">
                            <div class="info-card-header">Social Media</div>
                            <div class="info-card-body">
                                <div class="social-links d-flex flex-wrap align-items-center">
                                    @if($trainee->linkedin)
                                        <a href="{{ $trainee->linkedin }}" target="_blank" class="profile-social-link">
                                            <i class="fa-brands fa-linkedin"></i> LinkedIn
                                        </a>
                                    @endif
                                    @if($trainee->git_hub)
                                        <a href="{{ $trainee->git_hub }}" target="_blank" class="profile-social-link">
                                            <i class="fa-brands fa-github"></i> GitHub
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
