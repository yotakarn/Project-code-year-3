@extends('layouts.main',
['title' => $dentist->dentist_name. "'s Patients" ])

@section('header')
<div class="search-add">
    <search>
        <form action="{{ route('dentists.view-patients', [ 'dentist' => $dentist->dentist_id, ]) }}" method="get" class="search-form">
            @csrf
            <label>
                <b>Search</b>
                <input type="text" name="term" value="{{ $criteria['term'] }}" />
            </label>
            <br />

            <button type="submit" class="buttonsearch">Search</button>

            <a href="{{ route('dentists.view-patients', [ 'dentist' => $dentist->dentist_id, ]) }}">
                <button type="button" class="accentcancel">X</button>
            </a>

        </form>
    </search>
</div>

<div class="top-view-actions">
    <nav class="left-buttons">
        <ul>
            <li><a href="{{ session()->get('bookmarks.dentists.view-patients', route('dentists.view', [ 'dentist' => $dentist->dentist_id,])) }}" class="button primary">&lt; Back</a>
            </li>

        </ul>
    </nav>
    <div class="pagination">
        {{ $patients->withQueryString()->links() }}
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