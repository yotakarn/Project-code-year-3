@extends('appointments.main', ['title' => 'Appointment'])

@section('header')
<div class="search-add">
    <search>
        <form action="{{ route('appointments.list') }}" method="get" class="appointment-search-form">
            <label class="search-term-label">
                <b>Search</b>
                <input type="text" name="term" value="{{ $criteria['term'] ?? '' }}" placeholder="Search Patient, Dentist..." />
            </label>

            <label class="search-date-label">
                <input type="date" name="date" value="{{ request('date') }}">
            </label>

            <button type="submit" class="buttonsearch">Search</button>

            <a href="{{ route('appointments.list') }}">
                <button type="button" class="accentcancel">X</button>
            </a>
        </form>
    </search>

    <div class="pagination-group">
            @php
            session()->put('bookmarks.appointments.create-form', url()->full());
            @endphp

            @can('create', App\Models\Appointment::class)
                <a href="{{ route('appointments.create-form') }}" class="button primary">Create Appointment</a>
            @endcan
        <div class="pagination">
        {!! $paginatedAppointments->withQueryString()->links() !!}
        </div>
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