@extends('layouts.main',
['title' => $patient->patient_name. "'s Dentists" ])

@section('header')
<div class="search-add">
<search>
    <form action="{{ route('patients.view-dentists', [ 'patient' => $patient->patient_id, ]) }}" method="get" class="search-form">
        @csrf
        <label>
            <b>Search</b>
            <input type="text" name="term" value="{{ $criteria['term'] }}" />
        </label>
        <br />
        <button type="submit" class="buttonsearch">Search</button>

        <a href="{{ route('patients.view-dentists', [ 'patient' => $patient->patient_id, ]) }}">
            <button type="button" class="accentcancel">X</button>
        </a>

    </form>
</search>
</div>

<div class="top-view-actions">
    <nav class="left-buttons">
        <ul class="app-cmp-links">
            <li><a href="{{ session()->get('bookmarks.patients.view-dentists', route('patients.view', [ 'patient' => $patient->patient_id,])) }}"
            class="button primary" >&lt; Back</a>
            </li>

        </ul>
    </nav>
    <div class="pagination">
    {{ $dentists->withQueryString()->links() }}
    </div>
</div>
@endsection

@section('content')
<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Dentist Code</th>
            <th>Name</th>
            <th>Department</th>
            <th>Phone Number</th>
        </tr>
    </thead>

    <tbody>
        @php
        session()->put('bookmarks.dentists.view', url()->full());
        @endphp

        @foreach ($dentists as $dentist)
        <tr>
            <td>{{ $dentist->dentist_id }}</td>

            <td>{{ $dentist->dentist_code }}</td>

            <td>
                <a href="{{ route('dentists.view', ['dentist' => $dentist->dentist_id,]) }}">
                    {{ $dentist->dentist_name }}
                </a>
            </td>

            <td>
                {{ $dentist->dentist_department }}
            </td>

            <td>
                {{ $dentist->phone_number }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection