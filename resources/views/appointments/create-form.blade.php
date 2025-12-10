@extends('appointments.main', [
    'title' => 'Create Appointment',
])
 
@section('content')
 
    <form action="{{ route('appointments.create') }}" method="post" class="dentist-form">
    @csrf
 
        <div>
            {{--Code --}}
            <div>
                <label for="app-inp-code">Code
                    <input type="text" id="app-inp-code" name="appointment_code" value="{{ old('appointment_code') }}" required />
                </label>
            </div>
 
            {{-- Dentist Selection --}}
            <div>
                <label for="inp-dentist">Dentist
                    <select id="inp-dentist" name="dentist_id" required>
                        <option value="">-- Please select a dentist --</option>
                        @foreach($dentists as $dentist)
                            <option value="{{ $dentist->dentist_id }}"
                                @selected($dentist->dentist_id == old('dentist_id'))>
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
                                @selected($patient->patient_id == old('patient_id'))>
                                ({{ $patient->patient_code }}){{ $patient->patient_name }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>
 
            {{-- Appointment Date --}}
            <div>
                <label for="inp-date">Date
                    <input type="date" id="inp-date" name="appointment_date" value="{{ old('appointment_date', today()->toDateString()) }}" min="{{ date('Y-m-d') }}" required />
                </label>
            </div>
 
            {{-- Appointment Time --}}
            <div>
                <label for="inp-time">Time (Duration: 2 Hours)
                    <select id="inp-time" name="appointment_time" required>
                        {{-- ใช้ $h += 2 → หมายถึงให้เพิ่มทีละ 2 ชั่วโมง --}}
                        @for ($h = 10; $h <= 20; $h+=2){{-- Loop until 16:00 for a 2-hour slot ending at 18:00 --}}
                            @php $hour = str_pad($h, 2, '0', STR_PAD_LEFT); @endphp
                            <option value="{{ $hour }}:00:00" @selected(old('appointment_time') == $hour.':00:00')>{{ $hour }}:00</option>
                        @endfor
                    </select>
                </label>
            </div>
 
            {{-- Description --}}
            <div>
                <label for="inp-description">Description
                    <textarea id="inp-description" name="description" cols="80" rows="5" required>{{ old('description') }}</textarea>
                </label>
            </div>
        </div>
 
    <div class="form-buttons">
       
        <a href="{{ session()->get('bookmarks.appointments.create-form', route('appointments.list')) }}">
            <button type="button" class="button cancel-button" >Cancel</button>
        </a>
       
        <button type="submit" class="button">Create</button>
 
    </div>
    </form>
@endsection
 