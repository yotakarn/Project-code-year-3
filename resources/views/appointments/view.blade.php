@extends('appointments.main', ['title' => 'Appointment: '.$appointment->appointment_code ])

@section('header')
<div class="top-view-actions">
    <nav class="left-buttons">
        <ul>
            @php
            session()->put('bookmarks.appointments.pdf', url()->full());
            @endphp

            <li>
                <a href="{{ session()->get('bookmarks.appointments.view', route('appointments.list')) }}" class="button primary">&lt; Back</a>
            </li>
            <li>
                <a href="{{ route('appointments.pdf', ['appointment' => $appointment->appointment_id]) }}" target="_blank" class="button primary">PDF
                </a>
            </li>
        </ul>
    </nav>
</div>
@endsection

@section('content')
@php
session()->put('bookmarks.patients.view', url()->full());
session()->put('bookmarks.dentists.view', url()->full());
@endphp

<div class="appointment-date-time">
    <p><strong>Date & Time </strong><span class="date-highlight">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('D, j M Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</span>
    </p>
</div>

<table>
    <colgroup>
        <col style="width: 150px;" />
        <col />
    </colgroup>

    <tbody>
        <tr>
            <th class="sp">Patient Details</th>
            <td></td>
        </tr>
        <tr>
            <th>Name</th>
            <td>
                <a href="{{ route('patients.view', ['patient' => $appointment->patient->patient_id,]) }}">
                    {{ $appointment->patient->patient_name }}</a>
            </td>
        </tr>
        <tr>
            <th>Code</th>
            <td>{{ $appointment->patient->patient_code }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{ $appointment->patient->phone_number}}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>
                <pre>{{ $appointment->description }}</pre>
            </td>
        </tr>
        <tr>
            <th class="sp">Dentist Details</th>
            <td></td>
        </tr>
        <tr>
            <th>Name</th>
            <td>
                <a href="{{ route('dentists.view', ['dentist' => $appointment->dentist->dentist_id,]) }}">
                    {{ $appointment->dentist->dentist_name}}
                </a>
            </td>
        </tr>
        <tr>
            <th>Department</th>
            <td>{{ $appointment->dentist->dentist_department }}</td>
        </tr>
</table>

<div class="action-buttons-footer">

    <form action="{{ route('appointments.delete', [ 'appointment' => $appointment->appointment_id, ]) }}" method="post" id="app-form-delete">
        @csrf
    </form>

    @can('update', App\Models\Appointment::class)
    <button type="submit" form="app-form-delete" class="button cancel-button">Delete</button>

    <a href="{{ route('appointments.update-form', [ 'appointment' => $appointment->appointment_id, ]) }}" class="button">Edit</a>
    
    @endcan

</div>
@endsection