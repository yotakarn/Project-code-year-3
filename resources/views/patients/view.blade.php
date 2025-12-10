@extends('layouts.main',
['title' => 'Patient: '. $patient->patient_name ])

@section('header')
<div class="top-view-actions">
    <nav class="left-buttons">
        <ul>
            
            <li>
                <a href="{{ session()->get('bookmarks.patients.view', route('patients.list')) }}" class="button primary">&lt; Back</a>
            </li>
        </ul>
    </nav>

    <nav class="right-button">
        @php
            session()->put('bookmarks.patients.view-dentists', url()->full());
            session()->put('bookmarks.patients.view-appointments', url()->full());
        @endphp

        @can('update', App\Models\Patient::class)
        <ul>
            <li>
                <a href="{{ route('patients.view-dentists', [ 'patient' => $patient->patient_id, ]) }}" class="button primary">Dentists</a>
            </li>

            <li>
                <a href="{{ route('patients.view-appointments', [ 'patient' => $patient->patient_id, ]) }}" class="button primary">Appointments</a>
            </li>
        </ul>
        @endcan
    </nav>
</div>
@endsection

@section('content')
<table>
    <colgroup>
        <col style="width: 150px;" />
        <col />
    </colgroup>

    <tbody>
        <tr>
            <th>Patient Code</th>
            <td class="sp"><b>{{ $patient->patient_code }}</b></td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $patient->patient_name }}</td>
        </tr>
        <tr>
            <th>gender</th>
            <td>{{ $patient->gender }}</td>
        </tr>
        <tr>
            <th>Date of Birth</th>
            <td>{{ $patient->date_birth }}</td>
        </tr>
        <tr>
            <th>Age</th>
            <td>{{ $patient->age }}</td>
        </tr>
        <tr>
            <th>Identificatio number</th>
            <td>{{ $patient->identification_number }}</td>
        </tr>
        <tr>
            <th>Phone Number</th>
            <td>{{ $patient->phone_number }}</td>
        </tr>
        <tr>
            <th>Address</th>
            <td>{{ $patient->address }}</td>
        </tr>
        <tr>
            <th>Blood group</th>
            <td>{{ $patient->blood_group }}</td>
        </tr>
        <tr>
            <th>Medical condition</th>
            <td>{{ $patient->medical_condition }}</td>
        </tr>
        <tr>
            <th>Drug allergy</th>
            <td>{{ $patient->drug_allergy }}</td>
        </tr>
    </tbody>
</table>

@can('update', App\Models\Patient::class)
<div class="action-buttons-footer">
    <a href="{{ route('patients.update-form', [ 'patient' => $patient->patient_id, ]) }}" class="button">Edit</a>
</div>
@endcan
@endsection