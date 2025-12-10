 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="{{ asset('css/style.css') }}">
     <title>{{ $title }}</title>
 </head>

 <body>
     <header class="main-header">
         <div class="logo">
             <img src="{{ asset('img/dentallogo.png') }}" alt="FunD Dentist Logo">
             <span class="brand-name">FunD Dentist</span>
         </div>

         <nav class="main-nav">
             <ul>
                 <!-- 1. เมนูหลัก -->
                 <li>
                     <a href="{{ route('home.view') }}">Home</a>
                 </li>

                 @auth
                 <li>
                     <a href="{{ route('appointments.list') }}">Appointment</a>
                 </li>
                 @endauth

                 @if (Auth::check() && Auth::user()->role === 'ADMIN')
                 <li>
                     <a href="{{ route('dentists.list') }}">Dentist</a>
                 </li>
                 <li>
                     <a href="{{ route('patients.list') }}">Patient</a>
                 </li>
                 <li>
                     <a href="{{ route('users.list') }}">User</a>
                 </li>
                 @endif

                 @guest
                 <li>
                     <a href="{{ route('login') }}">Login</a>
                 </li>
                 @endguest

                 @php
                 // จัดการ Bookmark สำหรับ Users Self View
                 if (!Route::is('users.selves.*')) {
                 session()->put('bookmarks.users.selves.view', url()->full());
                 }
                 @endphp

                 @auth
                 <!-- 2. ปุ่ม User Name + Logout (รวมเป็น LI เดียวและใช้ Flex ภายใน) -->
                 <li class="user-logout-tab">
                     <!-- ลิงก์ชื่อผู้ใช้ -->
                     <a href='{{ route("users.selves.view") }}' class="user-name-link">{{ \Auth::user()->name }}</a>

                     <!-- Form Logout (ปุ่ม Logout) -->
                     <form action="{{ route('logout') }}" method="post" class="logout-form">
                         @csrf
                         <button type="submit" class="button primary logout-button">Logout</button>
                     </form>
                 </li>
                 @endauth

             </ul>
         </nav>
     </header>

     <main>
         <header class="page-header">
             <h1>
                 @section('title')
                 <span>{{ $title }}</span>
                 @show
             </h1>

             <div class="status-message">
                 @session('status')
                 <div role="status">
                     {{ $value }}
                 </div>
                 @endsession

                 @error('alert')
                 <div role="alert">
                     {{ $message }}
                 </div>
                 @enderror
             </div>

             @yield('header')
         </header>

         @yield('content')
     </main>

     <footer class="main-footer">
         &#xA9; Copyright FunD Dentist.
     </footer>
 </body>

 </html>