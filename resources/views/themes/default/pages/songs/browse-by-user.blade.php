@extends('themes.default.layout')
@section('title', '- Songs by '.$username)

@section('content')
    @each('themes.default.pages.songs.partial-preview',$songs,'song')
    @include('themes.default.pages.songs.partial-paging-user')
@endsection