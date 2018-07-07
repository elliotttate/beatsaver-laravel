@extends('layout')
@section('title', '- Song Delete')


@section('content')
    @component('edit.component-delete')
        @slot('id', $song['id'])
        @slot('key', $song['key'])
        @slot('name', $song['name'])
    @endcomponent
@endsection