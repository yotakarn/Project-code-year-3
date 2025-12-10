@extends('layouts.main',
['title' => 'Dentist: '. $dentist->dentist_name ])

@section('header')
<div class="top-view-actions">
    <nav class="left-buttons">
        <ul>
            <li>
                <a href="{{ session()->get('bookmarks.dentists.view', route('dentists.list')) }}" class="button primary">&lt; Back</a>
            </li>
        </ul>
    </nav>

    @can('update', App\Models\Dentist::class)
    @php
    session()->put('bookmarks.dentists.view-patients', url()->full());
    session()->put('bookmarks.dentists.view-appointments', url()->full());
    @endphp
    
    <nav class="right-button">
        <ul>
            <li>
                <a href="{{ route('dentists.view-patients', [ 'dentist' => $dentist->dentist_id, ]) }}" class="button primary">Patients</a>
            </li>

            <li>
                <a href="{{ route('dentists.view-appointments', [ 'dentist' => $dentist->dentist_id, ]) }}" class="button primary">Appointments</a>
            </li>
        </ul>
    </nav>
    @endcan
</div>
@endsection

@section('content')
<table>
    <colgroup>
        <col style="width: 150px;" />
        <col />
    </colgroup>

    <tbody>
        @if (Auth::user()->role === 'ADMIN' || Auth::user()->role === 'DENTIST')
        <tr>
            <th>Dentist Code</th>
            <td class="sp"><b>{{ $dentist->dentist_code }}</b></td>
        </tr>
        @endif
        <tr>
            <th>Name</th>
        
        @if (Auth::user()->role === 'PATIENT')
            <td class="sp"><b>{{ $dentist->dentist_name }}</b></td>
        @else
            <td>{{ $dentist->dentist_name }}</td>
        @endif
        
        </tr>
        <tr>
            <th>Department</th>
            <td>{{ $dentist->dentist_department }}</td>
        </tr>
        <tr>
            <th>Phone Number</th>
            <td>{{ $dentist->phone_number }}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $dentist->description }}</td>
        </tr>
    </tbody>
</table>

@can('update', App\Models\Dentist::class)
<div class="action-buttons-footer">

    <form action="{{ route('dentists.delete', [ 'dentist' => $dentist->dentist_id, ]) }}" method="post" id="app-form-delete">
        @csrf
    </form>

    <button type="submit" form="app-form-delete" class="button cancel-button">Delete</button>

    <a href="{{ route('dentists.update-form', [ 'dentist' => $dentist->dentist_id, ]) }}" class="button">Edit</a>

</div>
@endcan
@endsection