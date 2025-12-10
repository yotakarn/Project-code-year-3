@extends('layouts.main', [
'title' => 'Profile: '. $user->name,
])

@section('header')
<div class="top-view-actions">
<nav class="left-buttons">
    <ul>
        <li>
            <a href="{{ session()->get('bookmarks.users.selves.view', route('users.list')) }}" class="button primary">&lt; Back</a>
        </li>

    </ul>
</nav>
</div>
@endSection

@section('content')
   @if($user->role === 'ADMIN')
    <table>
        <colgroup>
            <col style="width: 150px;" />
            <col />
        </colgroup>

         <tbody>
            <tr>
                <th>Email</th>
                <td class="sp">{{ $user->email }}</span>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $user->name }}</span>
            </tr>
        </tbody>
    </table>
    @elseif($user->role === 'DENTIST')
    <table>
        <colgroup>
            <col style="width: 150px;" />
            <col />
        </colgroup>

         <tbody>
            <tr>
                <th>Email</th>
                <td class="sp">{{ $user->email }}</span>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $user->name }}</span>
            </tr>
            <tr>
                <th>Department</th>
                <td>{{ $user->dentist->dentist_department }}</span>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $user->dentist->phone_number }}</span>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $user->dentist->description }}</span>
            </tr>
        </tbody>
    </table>
    @elseif($user->role === 'PATIENT')
    <table>
        <colgroup>
            <col style="width: 150px;" />
            <col />
        </colgroup>

        <tbody>
            <tr>
                <th>Email</th>
                <td class="sp">{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>gender</th>
                <td>{{ $user->patient->gender }}</td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td>{{ $user->patient->date_birth }}</td>
            </tr>
            <tr>
                <th>Age</th>
                <td>{{ $user->patient->age }}</td>
            </tr>
            <tr>
                <th>ID Number</th>
                <td>{{ $user->patient->identification_number }}</td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td>{{ $user->patient->phone_number }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{ $user->patient->address }}</td>
            </tr>
            <tr>
                <th>Blood group</th>
                <td>{{ $user->patient->blood_group }}</td>
            </tr>
            <tr>
                <th>Medical condition</th>
                <td>{{ $user->patient->medical_condition }}</td>
            </tr>
            <tr>
                <th>Drug allergy</th>
                <td>{{ $user->patient->drug_allergy }}</td>
            </tr>
        </tbody>
    </table>
    @endif
@endsection