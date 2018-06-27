@extends('layout')
@section('title', '- Top Played')

@section('content')
    <br /><br />
    @each('browse.song-data-frontpage',$songs,'song')
@endsection