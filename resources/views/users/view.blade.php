@extends('layouts.main', [
'title' => 'User : '. $user->email,
])

@section('header')
<div class="top-view-actions">
    <nav class="left-buttons">
        <ul>
            <li>
                <a href="{{ session()->get('bookmarks.users.view', route('users.list')) }}" class="button primary">&lt; Back</a>
            </li>
        </ul>
    </nav>
</div>
@endSection

@section('content')
<table>
    <colgroup>
        <col style="width: 150px;" />
        <col />
    </colgroup>

    <tbody>
        <tr>
            <th>Email</th>
            <td class="sp"><span class="app-cl-code">{{ $user->email }}</span>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Role</th>
            <td><span>{{ $user->role }}</span></td>
        </tr>
    </tbody>
</table>

@can('update', App\Models\User::class)
<div class="action-buttons-footer">

    <a href="{{ route('users.update-form', [ 'user' => $user->email, ]) }}" class="button primary">Edit</a>

</div>
@endcan
@endsection