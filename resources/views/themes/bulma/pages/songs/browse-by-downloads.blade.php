@extends('themes.bulma.layout')
@section('title', '- '.$title)

@section('content')
    @each('themes.bulma.pages.songs.partial-preview',$songs,'song')
    @include('themes.bulma.pages.songs.partial-paging')
@endsection