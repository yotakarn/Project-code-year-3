 @extends('layouts.main', [
 'title' => "Edit: ". $user->email,
 ])

 @section('content')
 <form action="{{ route('users.update', [ 'user' => $user->email, ]) }}" method="post" class="dentist-form">
     @csrf

     <label>Email
         <span class="app-cl-code">{{ $user->email }}</span>
     </label>

     <label>Name
         <input type="text" name="name" value="{{ old('name', $user->name) }}" required />
     </label>

     <label>Role
         @if ($user->role === 'ADMIN')
         <span>{{ $user->role }}</span>
         @else
         <select name="role" required>
             <option value="ADMIN" @selected(old('role', $user->role) === 'ADMIN')>ADMIN</option>
             <option value="DENTIST" @selected(old('role', $user->role) === 'DENTIST')>DENTIST</option>
             <option value="PATIENT" @selected(old('role', $user->role) === 'PATIENT')>PATIENT</option>
         </select>
         @endif
     </label>

     <label>Password
         <input type="password" name="password" value="{{ old('password') }}" placeholder="Leave blank if you don't need to update" />
     </label>

     <div class="form-buttons">
         <a href="{{ route('users.view', ['user' => $user->email,]) }}">
             <button type="button" class="button cancel-button">Cancel</button>
         </a>
         <button type="submit" class="button">Edit</button>
     </div>
 </form>
 @endsection