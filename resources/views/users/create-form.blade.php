 @extends('layouts.main', [
 'title' => "Add User",
 ])
@section('header')
<div class="top-view-actions">
    <nav class="left-buttons">
        <ul>
            <li>
                <a href="{{ session()->get('bookmarks.users.create-form', route('users.list')) }}" class="button primary">&lt; Back</a>
            </li>
        </ul>
    </nav>
</div>
@endSection

 @section('content')
 <form method="get" action="{{ route('users.create-form') }}" class="dentist-form">
     @csrf

     <label>
         Role
         <!-- select: ใช้สไตล์เดียวกับ input/select ใน dentist-form -->
         <select name="role" onchange="this.form.submit()">
             <option value="">--Select Role--</option>
             <option value="ADMIN" @selected($role=='ADMIN' )>ADMIN</option>
             <option value="DENTIST" @selected($role=='DENTIST' )>DENTIST</option>
             <option value="PATIENT" @selected($role=='PATIENT' )>PATIENT</option>
         </select>
     </label>
 </form>

@if(count($names) === 0)
    {{-- CASE 1: Role ไม่ถูกเลือก หรือ Role เป็น ADMIN --}}
    @if($role === null)
        {{-- นี่คือส่วนที่แสดงเมื่อไม่มีการเลือก role --}}
        <p class="role-message">Please select a role to add a user.</p>
    @elseif($role == 'ADMIN')
        <form action="{{ route('users.create') }}" method="post" class="dentist-form">
            @csrf
            <input type="hidden" name="role" value="{{ $role }}">
            <label>
                Name
                <input type="text" name="name" value="{{ old('name') }}" required />
            </label>

            <label>Email
                <input type="text" name="email" value="{{ old('email') }}" required />
            </label>

            <label>Password
                <input type="password" name="password" value="{{ old('password') }}" required />
            </label>

            <!-- จัดปุ่มให้อยู่ใน form-buttons -->
            <div class="form-buttons">
                <a href="{{ session()->get('bookmarks.users.create-form', route('users.list'),) }}">
                    <button type="button" class="button cancel-button">Cancel</button>
                </a>
                <button type="submit" class="button primary">Add</button>
            </div>
        </form>
    @else
        <p class="role-message">No new names found.</p>
    @endif

@else
    {{-- CASE 2: Role ถูกเลือก (DENTIST/PATIENT) และมี $names ให้เลือก --}}
    <form action="{{ route('users.create') }}" method="post" class="dentist-form">
        @csrf
        <input type="hidden" name="role" value="{{ $role }}">
        <label>
            Name
            <select name="name">
                @foreach($names as $id => $name)
                    <option value="{{ $id }}" @selected(old('name')==$id)>{{ $name }}</option>
                @endforeach
            </select>
        </label>

        <label>Email
            <input type="text" name="email" value="{{ old('email') }}" required />
        </label>

        <label>Password
            <input type="password" name="password" value="{{ old('password') }}" required />
        </label>

        <div class="form-buttons">
            <a href="{{ session()->get('bookmarks.users.create-form', route('users.list'),) }}">
                <button type="button" class="button cancel-button">Cancel</button>
            </a>
            <button type="submit" class="button primary">Add</button>
        </div>
    </form>
@endif
@endsection