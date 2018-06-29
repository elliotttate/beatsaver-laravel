@extends('layout')
@section('title', '- Song Detail')

@section('og-meta')
    @component('component-og-meta')
        @slot('ogTitle')
            {{ $song['songName'] }} | {{ $song['songSubName'] }}
        @endslot
        @slot('ogImageUrl')
            {{ asset("storage/songs/".$song['cover'].$song['coverMime']) }}
        @endslot
        @slot('ogDescription')
            {{ $song['name'] }} {{ $song['description'] }}
        @endslot
        @slot('ogUrl')
            {{ URL::current() }}
        @endslot
    @endcomponent
@endsection

@section('content')
    <br/><br/>
    @include('browse.song-data-detailpage')
@endsection