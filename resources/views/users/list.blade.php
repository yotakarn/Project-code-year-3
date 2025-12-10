@extends('layouts.main', [
'title' => 'Users',
])

@section('header')
<div class="search-add">
    <search>
        <form action="{{ route('users.list') }}" method="get" class="search-form">
            <label>
                <b>Search</b>
                <input type="text" name="term" value="{{ $criteria['term'] }}" />
            </label>
            <br />

            <button type="submit" class="buttonsearch">Search</button>
            <a href="{{ route('users.list') }}">
                <button type="button" class="accentcancel">X</button>
            </a>
        </form>
    </search>

    <div class="pagination-group">
        @php
        session()->put('bookmarks.users.create-form', url()->full());
        @endphp

        @can('create', App\Models\User::class)
        <a href="{{ route('users.create-form') }}" class="button primary">New User</a>
        @endcan

        <div class="pagination">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@section('content')
<table>
    <thead>
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Role</th>
        </tr>
    </thead>

    <tbody>
        @php
        session()->put('bookmarks.users.view', url()->full());
        @endphp
        @foreach ($users as $user)
        <tr>
            <td>
                <a href="{{ route('users.view', [
                            'user' => $user->email,
                        ]) }}"
                    class="app-cl-code">
                    {{ $user->email }}
                </a>
            </td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->role }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection