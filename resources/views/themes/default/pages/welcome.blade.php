@extends('themes.default.layout')
@section('title', '- Home')

@section('content')
    <h1 class="text-center">Welcome to BeatSaver, the unofficial custom song download platform.</h1>
    @each('themes.default.pages.songs.partial-preview',$songs,'song')
    @include('themes.default.pages.songs.partial-paging')
@endsection
