@extends('layout')
@section('title', '- Newest')

@section('content')
    <br /><br />
    @each('browse.song-data-frontpage',$songs,'song')
@endsection