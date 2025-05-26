@extends('layouts.app')

@section('content')
    <h1>Kategorijos</h1>
    <a href="{{ route('categories.create') }}">Pridėti naują kategoriją</a>

    <ul>
        @foreach($categories as $category)
            <li>{{ $category->name }} ({{ $category->type }})</li>
        @endforeach
    </ul>
@endsection
