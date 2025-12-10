@extends('layouts.main', ['title' => 'Edit Appointment #'.$appointment->appointment_code])
 
@section('content')
    <form action="{{ route('appointments.update', ['appointment' => $appointment->appointment_id]) }}" method="post" class="dentist-form">
        @csrf
 
        <div>
            {{-- Appointment Code (Readonly) --}}
            <div>
                <label for="inp-code">Appointment Code
                    <input type="text" id="inp-code" name="appointment_code" value="{{ old('appointment_code', $appointment->appointment_code) }}" readonly style="background-color: #eee;" />
                </label>
            </div>
 
            {{-- Dentist Selection --}}
            <div>
                <label for="inp-dentist">Dentist
                    <select id="inp-dentist" name="dentist_id" required>
                        <option value="">-- Please select a dentist --</option>
                        @foreach($dentists as $dentist)
                            <option value="{{ $dentist->dentist_id }}"
                                @selected($dentist->dentist_id === old('dentist_id', $appointment->dentist_id))>
                                {{ $dentist->dentist_name }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>
 
            {{-- Patient Selection --}}
            <div>
                <label for="inp-patient">Patient
                    <select id="inp-patient" name="patient_id" required>
                        <option value="">-- Please select a patient --</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->patient_id }}"
                                @selected($patient->patient_id === old('patient_id', $appointment->patient_id))>
                                ({{ $patient->patient_code }}){{ $patient->patient_name }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>
 
            {{-- Appointment Date --}}
            <div>
                <label for="inp-date">Date
                    <input type="date" id="inp-date" name="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date) }}" required />
                </label>
            </div>
 
            {{-- Appointment Time --}}
            <div>
                <label for="inp-time">Time (Duration: 2 Hours)
                    <select id="inp-time" name="appointment_time" required>
                        @for ($h = 10; $h <= 20; $h+=2)
                            @php $hour = str_pad($h, 2, '0', STR_PAD_LEFT); @endphp
                            <option value="{{ $hour }}:00:00" @selected(old('appointment_time', $appointment->appointment_time) == $hour.':00:00')>{{ $hour }}:00</option>
                        @endfor
                    </select>
                </label>
            </div>
 
            {{-- Description --}}
            <div>
                <label for="inp-description">Description
                    <textarea id="inp-description" name="description" cols="80" rows="5" required>{{ old('description', $appointment->description) }}</textarea>
                </label>
            </div>
        </div>
 
        <div class="form-buttons">
 
            <a href="{{ route('appointments.view', ['appointment' => $appointment->appointment_id,]) }}">
                <button type="button" class="button cancel-button">Cancel</button>
            </a>
 
            <button type="submit" class="button">Edit</button>
        </div>
    </form>
@endsection