@extends('themes.default.layout')
@section('title', '- Song Detail')

@section('og-meta')
    @component('components.og-meta')
	@php
	$diffs = "";
	foreach($song['version'][$song['key']]['difficulties'] as $diff => $data) {
		$diffs .= $data['difficulty'] . " ";
                $events = $data['stats']['events'] ? 'Yes':'No';
	}
        @endphp
        @slot('ogTitle', $song['version'][$song['key']]['songName'] .' '. $song['version'][$song['key']]['songSubName'])
        @slot('ogImageUrl', $song['version'][$song['key']]['coverUrl'])
        @slot('ogDescription', $song['name'] .': '. $song['description'] . " [ $diffs] [ " . $song['version'][$song['key']]['bpm'] . " bpm ]")
        @slot('ogUrl', $song['version'][$song['key']]['linkUrl'])
    @endcomponent
@endsection

@section('content')
    @include('themes.default.pages.songs.partial-detail')
@endsection
