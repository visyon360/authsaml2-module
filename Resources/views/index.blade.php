@extends('authsaml2::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('authsaml2.name') !!}
    </p>
@endsection
