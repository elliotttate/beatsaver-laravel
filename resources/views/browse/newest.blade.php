@extends('layout')
@section('title', '- Newest')

@section('content')
    @each('browse.song-partial',$songs,'song')
@endsection