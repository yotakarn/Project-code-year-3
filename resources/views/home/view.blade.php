@extends('layouts.main', ['title' => 'FunD Dentist'])

@section('content')
<div class="home-container">
    <section class="hero">
        <div class="hero-text">
            <h1>Welcome to <span>FunD Dentist</span></h1>
            <p>We care for your smile with our hearts, provided by a team of professional dentists with modern technology.</p>

            @guest
            <a href="{{ route('login') }}" class="button primary">
                Login
            </a>
            @endguest
            
        </div>
        <div class="hero-image">
            <img src="{{ asset('img/homedentist.jpg') }}" alt="Dental Care">
        </div>
    </section>

    <section class="services">
        <h2>Our services</h2>
        <div class="service-list">
            <div class="service-card">
                <img src="{{ asset('img/st.jpg') }}" alt="Scaling">
                <h3>Scaling</h3>
                <p>Cleans teeth and removes tartar for healthy gums.</p>
            </div>
            <div class="service-card">
                <img src="{{ asset('img/ft.jpg') }}" alt="whitening">
                <h3>Teeth whitening</h3>
                <p>Restore your smile with safe teeth whitening services.</p>
            </div>
            <div class="service-card">
                <img src="{{ asset('img/dt.jpg') }}" alt="Braces">
                <h3>Braces</h3>
                <p>Adjust the shape of your teeth and occlusion to be beautiful and correct according to dental principles.</p>
            </div>
        </div>
    </section>

    <section class="about">
        <h2>About us</h2>
        <p>
            FunD Dentist is a dental clinic focused on providing warm and safe services.
            Our team of expert dentists are ready to treat all oral problems with state-of-the-art technology.
            We also offer a friendly atmosphere for patients of all ages.
        </p>
    </section>
</div>
@endsection