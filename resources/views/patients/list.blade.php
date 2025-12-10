@extends('layouts.main',
['title' => 'Patients'])

@section('header')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<div class="search-add">
    <search>
        <form action="{{ route('patients.list') }}" method="get" class="search-form">
            <label>
                <b>Search</b>
                <input type="text" name="term" value="{{ $criteria['term'] }}" />
            </label>
            <br />

            <button type="submit" class="buttonsearch">Search</button>
            <a href="{{ route('patients.list') }}">
                <button type="button" class="accentcancel">X</button>
            </a>
        </form>
    </search>

    <div class="pagination-group">

        @php
        session()->put('bookmarks.patients.addPatient-form', url()->full());
        @endphp

        @can('add', App\Models\Patient::class)
        <a href="{{ route('patients.addPatient-form') }}" class="button primary">Add Patient</a>
        @endcan

        <div class="pagination">
            {{ $patients->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@section('content')
<table>
    <thead>
        <tr>
            <th>Patient Code</th>
            <th>Name</th>
            <th>Phone Number</th>
        </tr>
    </thead>

    <tbody>
        @php
        session()->put('bookmarks.patients.view', url()->full());
        @endphp

        @foreach ($patients as $patient)
        <tr>

            <td>{{ $patient->patient_code }}</td>

            <td>
                <a href="{{ route('patients.view', ['patient' => $patient->patient_id,]) }}">
                    {{ $patient->patient_name }}
                </a>
            </td>

            <td>{{ $patient->phone_number }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection