@extends('layout')
@section('title', '- Top Downloads')

@section('content')
    <br /><br />
    @each('browse.song-data-frontpage',$songs,'song')
@endsection