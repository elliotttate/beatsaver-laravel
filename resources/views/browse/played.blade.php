@extends('layout')
@section('title', '- Top Played')

@section('content')
    @each('browse.song-partial',$songs,'song')
@endsection