@extends('themes.default.layout')
@section('title', '- Song Delete')


@section('content')
    @component('themes.default.components.song-delete')
        @slot('id', $song['id'])
        @slot('key', $song['key'])
        @slot('name', $song['name'])
    @endcomponent
@endsection