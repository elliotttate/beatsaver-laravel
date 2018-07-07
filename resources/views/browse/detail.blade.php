@extends('layout')
@section('title', '- Song Detail')

@section('og-meta')
    @component('component-og-meta')
        @slot('ogTitle', $song['songName'] .' '. $song['songSubName'])
        @slot('ogImageUrl', $song['coverUrl'])
        @slot('ogDescription', $song['name'] .': '. $song['description'])
        @slot('ogUrl', $song['linkUrl'])
    @endcomponent
@endsection

@section('content')
    @include('browse.song-detail')
@endsection