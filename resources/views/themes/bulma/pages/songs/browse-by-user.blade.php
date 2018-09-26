@extends('themes.bulma.layout')
@section('title', '- Songs by '.$username)

@section('content')
    @each('themes.bulma.pages.songs.partial-preview',$songs,'song')
    @include('themes.bulma.pages.songs.partial-paging-user')
@endsection