@extends('layouts.app')
@section('content')
    <h1>Edit trainee List</h1>
    
    <form action="{{ route('employment-status.update', $trainee->id) }}" method="POST">
      @csrf
      @method('PATCH')

                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="inputFirstName" class="is-required">First_Name</label>
                      <input type="text" class="form-control" id="inputFirstName" placeholder="First_Name" name="first_name" value="{{ $trainee->first_name }}" required>
                    </div>
                    <div class="form-group">
                      <label for="inputLastName" class="is-required">Last_Name</label>
                      <input type="text" class="form-control" id="inputLastName" placeholder="Last_Name" name="last_name" value="{{ $trainee->last_name }}" required>
                    </div>


          <div class="form-group">
            <label for="inputMobile" class="is-required">Mobile</label>
            <input type="Phone" class="form-control" id="inputMobile" placeholder="XXX-XXX-XXXX" name="mobile" value="{{ $trainee->mobile }}" required>
          </div>
          <div class="form-group">
            <label for="inputEmail" class="is-required">Email</label>
            <input type="email" class="form-control" id="inputEmail" placeholder="email@example.com" name="email" value="{{ $trainee->email }}" required>
          </div>
          <div class="form-group">
    <label for="inputGraduated" class="is-required">Graduated</label>
    <select class="form-control" id="inputGraduated" name="graduated" required>
        <option value="Yes" {{ $trainee->graduated === 'Yes' ? 'selected' : '' }}>Yes</option>
        <option value="No" {{ $trainee->graduated === 'No' ? 'selected' : '' }}>No</option>
    </select>
</div>


<div class="form-group">
    <label for="inputCertificatType" class="is-required">Certificat Type</label>
    <select class="form-control" id="inputCertificatType" name="certificat_type" required>
        <option value="Attendance" {{ $trainee->certificat_type === 'Attendance' ? 'selected' : '' }}>Attendance</option>
        <option value="Partial_Front_End" {{ $trainee->certificat_type === 'Partial_Front_End' ? 'selected' : '' }}>Partial Front-End</option>
        <option value="Partial_Back_End" {{ $trainee->certificat_type === 'Partial_Back_End' ? 'selected' : '' }}>Partial Back-End</option>
        <option value="Full-Stack" {{ $trainee->certificat_type === 'Full-Stack' ? 'selected' : '' }}>Full-Stack</option>
        <option value="None" {{ $trainee->certificat_type === 'None' ? 'selected' : '' }}>None</option>
    </select>
</div>

          <div class="form-group">
            <label for="inputNationality" class="is-required">Nationality</label>
            <input type="text" class="form-control" id="inputNationality" placeholder="Nationality" name="nationality" value="{{ $trainee->nationality }}" required>
          </div>

          <div class="form-group">
            <label for="inputCountry" class="is-required">Country</label>
            <input type="text" class="form-control" id="inputCountry" placeholder="Country" name="country" value="{{ $trainee->Country }}" required>
          </div>


          <div class="form-group">
    <label for="inputNationalId" class="is-required">National ID</label>
    <input type="number" class="form-control" id="inputNationalId" placeholder="National ID" name="national_id" value="{{ $trainee->national_id }}" required>
</div>

<div class="form-group">
    <label for="inputBirthdate" class="is-required">Birthdate</label>
    <input type="text" class="form-control" id="inputBirthdate" placeholder="Birthdate" name="birthdate" value="{{ $trainee->birthdate }}" required>
</div>

<div class="form-group">
    <label for="inputGender" class="is-required">Gender</label>
    <select class="form-control" id="inputGender" name="gender" required>
        <option value="Male" {{ $trainee->gender === 'Male' ? 'selected' : '' }}>Male</option>
        <option value="Female" {{ $trainee->gender === 'Female' ? 'selected' : '' }}>Female</option>
    </select>
</div>

<div class="form-group">
    <label for="inputMartialStatus" class="is-required">Marital Status</label>
    <select class="form-control" id="inputMartialStatus" name="martial_status" required>
        <option value="Single" {{ $trainee->martial_status === 'Single' ? 'selected' : '' }}>Single</option>
        <option value="Married" {{ $trainee->martial_status === 'Married' ? 'selected' : '' }}>Married</option>
    </select>
</div>

<div class="form-group">
    <label for="inputEducation" class="is-required">Education</label>
    <input type="text" class="form-control" id="inputEducation" placeholder="Education" name="education" value="{{ $trainee->education }}" required>
</div>

<div class="form-group">
    <label for="inputEducationalStatus" class="is-required">Educational Status</label>
    <input type="text" class="form-control" id="inputEducationalStatus" placeholder="Educational Status" name="educational_status" value="{{ $trainee->educational_status }}" required>
</div>

<div class="form-group">
    <label for="inputField" class="is-required">Field</label>
    <input type="text" class="form-control" id="inputField" placeholder="Field" name="field" value="{{ $trainee->field }}" required>
</div>

<div class="form-group">
    <label for="inputEducationalBackground" class="is-required">Educational Background</label>
    <input type="text" class="form-control" id="inputEducationalBackground" placeholder="Educational Background" name="educational_background" value="{{ $trainee->educational_background }}" required>
</div>

<div class="form-group">
    <label for="inputCity" class="is-required">City</label>
    <input type="text" class="form-control" id="inputCity" placeholder="City" name="city" value="{{ $trainee->city }}" required>
</div>

<div class="form-group">
    <label for="inputAddress" class="is-required">Address</label>
    <input type="text" class="form-control" id="inputAddress" placeholder="Address" name="address" value="{{ $trainee->address }}" required>
</div>


<div class="form-group">
    <label for="inputAddress" class="is-required">Address</label>
    <input type="text" class="form-control" id="inputAddress" placeholder="Address" name="address" value="{{ $trainee->address }}" required>
</div>


        </div>

        <!-- Add other form elements as needed -->

      </div>

      <button type="submit" class="btn btn-primary">Update Trainee</button>
    </form>
@endsection
