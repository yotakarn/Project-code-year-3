@extends('layouts.main', [
'title' => "Edit Patient - ". $patient->patient_name,
])

@section('content')
<form action="{{ route('patients.update', [ 'patient' => $patient->patient_id, ]) }}" method="post" class="dentist-form">
    @csrf

    <label>Patient Code
        <input type="text" name="patient_code" value="{{ old('patient_code', $patient->patient_code) }}" required />
    </label>

    <label>Name
        <input type="text" name="patient_name" value="{{ old('patient_name',$patient->patient_name) }}" required />
    </label>

    <label>Gender
        <select name="gender">
            <option value="Male" @selected(old('gender', $patient->gender) == 'Male')>Male</option>
            <option value="Female" @selected(old('gender', $patient->gender) == 'Female')>Female</option>
        </select>
    </label>

    <label>Date of Birth
        <input type="date" id="inp-date" name="date_birth" value="{{ old('date_birth', $patient->date_birth ?? date('Y-m-d')) }}"required />
    </label>

    <label>Age
        <input type="text" name="age" value="{{ old('age', $patient->age) }}" />
    </label>

    <label>
        Identification Number
        <input type="text" name="identification_number" value="{{ old('identification_number', $patient->identification_number) }}" />
    </label>

    <label>
        Phone Number
        <input type="text" name="phone_number" value="{{ old('phone_number', $patient->phone_number) }}" />
    </label>

    <label>
        Address
        <textarea name="address">{{ old('address', $patient->address) }}</textarea>
    </label>

    <label>
        Blood Group
        <input type="text" name="blood_group" value="{{ old('blood_group', $patient->blood_group) }}" />
    </label>

    <label>
        Medical condition
        <textarea name="medical_condition">{{ old('medical_condition', $patient->medical_condition) }}</textarea>
    </label>

    <label>
        Drug allergy
        <textarea name="drug_allergy">{{ old('drug_allergy', $patient->drug_allergy) }}</textarea>
    </label>

    <div class="form-buttons">
        <a href="{{ route('patients.view', ['patient' => $patient->patient_id,]) }}">
            <button type="button" class="button cancel-button">Cancel</button>
        </a>

        <button type="submit" class="button">Edit</button>
    </div>
</form>
@endsection