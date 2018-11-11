@extends('themes.bulma-dark.layout')
@section('title', '- '.$title)

@section('content')
    @each('themes.bulma-dark.pages.songs.partial-preview',$songs,'song')
    @include('themes.bulma-dark.pages.songs.partial-paging')
@endsection