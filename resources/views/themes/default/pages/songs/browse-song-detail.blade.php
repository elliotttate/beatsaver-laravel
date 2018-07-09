@extends('themes.default.layout')
@section('title', '- Song Detail')

@section('og-meta')
    @component('components.og-meta')
        @slot('ogTitle', $song['version'][$song['key']]['songName'] .' '. $song['version'][$song['key']]['songSubName'])
        @slot('ogImageUrl', $song['version'][$song['key']]['coverUrl'])
        @slot('ogDescription', $song['name'] .': '. $song['description'])
        @slot('ogUrl', $song['version'][$song['key']]['linkUrl'])
    @endcomponent
@endsection

@section('content')
    @include('themes.default.pages.songs.partial-detail')
@endsection