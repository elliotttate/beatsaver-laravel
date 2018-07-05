@extends('layout')
@section('title', '- Song Detail')

@section('og-meta')
    @component('component-og-meta')
        @slot('ogTitle')
            {{ $song['songName'] }} | {{ $song['songSubName'] }}
        @endslot
        @slot('ogImageUrl')
            {{ $song['coverUrl'] }}
        @endslot
        @slot('ogDescription')
            {{ $song['name'] }} {{ $song['description'] }}
        @endslot
        @slot('ogUrl')
            {{ $song['linkUrl'] }}
        @endslot
    @endcomponent
@endsection

@section('content')
    <br/><br/>
    @include('browse.song-data-detailpage')
@endsection