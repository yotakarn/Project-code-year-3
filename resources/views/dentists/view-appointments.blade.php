@extends('layouts.main',
['title' => $dentist->dentist_name. "'s Appointments" ])

@section('header')
<div class="search-add">
    <search>
        <form action="{{ route('dentists.view-appointments', [ 'dentist' => $dentist->dentist_id, ]) }}" method="get" class="search-form">
            @csrf
            <label>
                <b>Search</b>
                <input type="text" name="term" value="{{ $criteria['term'] }}" />
            </label>
            <br />
            <button type="submit" class="buttonsearch">Search</button>

            <a href="{{ route('dentists.view-appointments', [ 'dentist' => $dentist->dentist_id, ]) }}">
                <button type="button" class="accentcancel">X</button>
            </a>

        </form>
    </search>
</div>

<div class="top-view-actions">
    <nav class="left-buttons">
        <ul>
            <li><a href="{{ session()->get('bookmarks.dentists.view-appointments', route('dentists.view', [ 'dentist' => $dentist->dentist_id,])) }}" class="button primary">&lt; Back</a>
            </li>
        </ul>
    </nav>
    <div class="pagination">
    {{ $paginatedAppointments->withQueryString()->links() }}
    </div>
</div>
@endsection

@section('content')
<div class="appointments-wrapper">
    @php
    session()->put('bookmarks.appointments.view', url()->full());
    @endphp

    {{-- Group appointments by date --}}
    @foreach ($appointmentsByDate as $date => $appointments)
    <div class="appointment-date-group">
        <h3>{{ \Carbon\Carbon::parse($date)->format('D, j M Y') }}</h3>

        @forelse ($appointments as $appointment)
        <div class="appointment-card">
            <div class="appointment-header">
                <p>
                    <a href="{{ route('appointments.view', ['appointment' => $appointment->appointment_id]) }}">
                        Appointment #{{ $appointment->appointment_code ?? $appointment->appointment_id }}
                    </a>
                </p>
                <p>
                    <strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                </p>
            </div>
            <div class="appointment-body">
                <p><strong>Patient:</strong> {{ $appointment->patient->patient_name }}</p>
                <p><strong>Dentist:</strong> {{ $appointment->dentist->dentist_name }}</p>
                <p><strong>Description:</strong></p>
                <pre>{{ $appointment->description }}</pre>
            </div>
        </div>
        @empty
        <p>No appointments scheduled for this day.</p>
        @endforelse
    </div>
    @endforeach
</div>
@endsection