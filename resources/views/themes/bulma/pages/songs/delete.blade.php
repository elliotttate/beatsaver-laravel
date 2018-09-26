@extends('themes.bulma.layout')
@section('title', '- Song Delete')


@section('content')
    @component('themes.bulma.components.song-delete')
        @slot('id', $song['id'])
        @slot('key', $song['key'])
        @slot('name', $song['name'])
    @endcomponent
@endsection