@extends('layout')
@section('title', '- Top Downloads')

@section('content')
    @each('browse.song-partial',$songs,'song')
@endsection