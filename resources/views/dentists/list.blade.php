@extends('layouts.main',
['title' => 'Dentists'])

@section('header')
<div class="search-add">
    <search>
        <form action="{{ route('dentists.list') }}" method="get" class="search-form">
            <label>
                <b>Search</b>
                <input type="text" name="term" value="{{ $criteria['term'] }}" />
            </label>
            <br />

            <button type="submit" class="buttonsearch">Search</button>
            <a href="{{ route('dentists.list') }}">
                <button type="button" class="accentcancel">X</button>
            </a>
        </form>
    </search>

    <div class="pagination-group">
        @php
            session()->put('bookmarks.dentists.addDentist-form', url()->full());
        @endphp

        @can('add', App\Models\Dentist::class)
        <a href="{{ route('dentists.addDentist-form') }}" class="button primary">Add Dentist</a>
        @endcan

        <div class="pagination">
            {{ $dentists->withQueryString()->links() }}
        </div>
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