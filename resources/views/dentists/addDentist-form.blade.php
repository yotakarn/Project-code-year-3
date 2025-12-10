@extends('layouts.main',
['title' => 'Add Dentist'])

@section('content')
<form action="{{ route('dentists.add') }}" method="post" class="dentist-form">
    @csrf

    <label>Dentist Code
        <input type="text" name="dentist_code" value="{{ old('dentist_code') }}" required />
    </label>

    <label>Name
        <input type="text" name="dentist_name" value="{{ old('dentist_name') }}" required />
    </label>

    <label>
        Department
        <input type="text" name="dentist_department" value="{{ old('dentist_department') }}" />
    </label>

    <label>
        Phone Number
        <input type="text" name="phone_number" value="{{ old('phone_number') }}" />
    </label>

    <label>
        Description
        <textarea name="description" cols="80" rows="10" required>{{ old('description') }}</textarea>
    </label>

    <div class="form-buttons">

        <a href="{{ session()->get('bookmarks.dentists.addDentist-form', route('dentists.list'),) }}">
            <button type="button" class="button cancel-button">Cancel</button>
        </a>

        <button type="submit" class="button">Add</button>
    </div>
</form>
@endsection