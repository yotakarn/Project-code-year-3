@extends('layouts.main', [
    'title' => "Appointment: {$title}",
])

@section('title')
    <span @class($titleClasses ?? [])> {{ $title }}</span>
    @isset($subTitle)
        <span @class ($subTitleClasses ?? [])> {{ $subTitle }}</span>
    @endisset
@endsection