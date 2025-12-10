@extends('layouts.main', [
'title' => "Edit Dentist - ". $dentist->dentist_name,
])

@section('content')
<form action="{{ route('dentists.update', [ 'dentist' => $dentist->dentist_id, ]) }}" method="post" class="dentist-form">
    @csrf

    <label>Dentist Code
        <input type="text" name="dentist_code" value="{{ old('dentist_code', $dentist->dentist_code) }}" required />
    </label>

    <label>Name
        <input type="text" name="dentist_name" value="{{ old('dentist_name',$dentist->dentist_name) }}" required />
    </label>

    <label>Department
        <input type="text" name="department" value="{{ old('dentist_department', $dentist->dentist_department) }}" />
    </label>

    <label>Phone Number
        <input type="text" name="phone_number" value="{{ old('phone_number', $dentist->phone_number) }}" />
    </label>

    <label>Description
        <textarea name="description">{{ old('description', $dentist->description) }}</textarea>
    </label>

    <div class="form-buttons">
        <a href="{{ route('dentists.view', ['dentist' => $dentist->dentist_id,]) }}">
            <button type="button" class="button cancel-button">Cancel</button>
        </a>

        <button type="submit" class="button">Edit</button>
    </div>
</form>
@endsection