@extends('layouts.main', [
'title' => "Add Patient",
])

@section('content')
<form action="{{ route('patients.add') }}" method="post" class="dentist-form">
    @csrf

    <label>Patient Code
        <input type="text" name="patient_code" value="{{ old('patient_code') }}" required />
    </label>

    <label>Name
        <input type="text" name="patient_name" value="{{ old('patient_name') }}" required />
    </label>

    <label>Gender
        <select name="gender" required>
            <option value="" disabled selected>-- Please Select Gender --</option>
            <option value="Male" @selected(old('gender')=='Male' )>Male</option>
            <option value="Female" @selected(old('gender')=='Female' )>Female</option>
        </select>
    </label>

    <label>Date of Birth
        <input type="date" id="inp-date" name="date_birth" value="{{ old('date_birth', date('Y-m-d')) }}" placeholder="YYYY-MM-DD" required />
    </label>

    <label>Age
        <input type="text" name="age" value="{{ old('age') }}" required />
    </label>

    <label>
        Identification Number
        <input type="text" name="identification_number" value="{{ old('identification_number') }}" required />
    </label>

    <label>
        Phone Number
        <input type="text" name="phone_number" value="{{ old('phone_number') }}" required />
    </label>

    <label>
        Address
        <textarea name="address" required>{{ old('address') }}</textarea>
    </label>

    <label>
        Blood Group
        <input type="text" name="blood_group" value="{{ old('blood_group') }}" required />
    </label>

    <label>
        Medical condition
        <textarea name="medical_condition" required>{{ old('medical_condition') }}</textarea>
    </label>

    <label>
        Drug allergy
        <textarea name="drug_allergy" required>{{ old('drug_allergy') }}</textarea>
    </label>

    <div class="form-buttons">

        <a href="{{ session()->get('bookmarks.patients.addPatient-form', route('patients.list')) }}">
            <button type="button" class="button cancel-button">Cancel</button>
        </a>

        <button type="submit" class="button">Add</button>
    </div>
</form>
@endsection