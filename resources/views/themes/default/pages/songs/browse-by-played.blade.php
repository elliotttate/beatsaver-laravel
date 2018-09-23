@extends('themes.default.layout')
@section('title', '- '.$title)

@section('content')
    @each('themes.default.pages.songs.partial-preview',$songs,'song')
    @include('themes.default.pages.songs.partial-paging')
@endsection