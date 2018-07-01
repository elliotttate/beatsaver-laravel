@extends('layout')
@section('title', '- Search results')

@section('content')
    <br /><br />
    @each('browse.song-data-frontpage',$songs,'song')
@endsection