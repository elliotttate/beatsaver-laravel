@extends('themes.bulma-dark.layout')
@section('title', '- Song Delete')


@section('content')
    @component('themes.bulma-dark.components.song-delete')
        @slot('id', $song['id'])
        @slot('key', $song['key'])
        @slot('name', $song['name'])
    @endcomponent
@endsection