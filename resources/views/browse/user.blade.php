@extends('layout')
@section('title', '- Songs by '.$username)

@section('content')
    <br /><br />
    @each('browse.song-data-frontpage',$songs,'song')
@endsection