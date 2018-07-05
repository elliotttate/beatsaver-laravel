@extends('layout')
@section('title', '- Top Played')

@section('content')
    <br /><br />
    @each('browse.song-data-frontpage',$songs,'song')

    @if($start >= 0 && count($songs) > 0)
        <a href="{{ route('browse.top.played',['start' => $start + $steps]) }}" class="pull-right">
            <button type="button" class="btn btn-default">
                Next Page ({{(floor($start / $steps)?: 1) +1 }}) <span class="glyphicon glyphicon-forward" aria-hidden="true"></span>
            </button>
        </a>
    @endif

    @if($start >= 0 && ($start - $steps) >= 0)
        <a href="{{ route('browse.top.played',['start' => $start - $steps]) }}" class="pull-left">
            <button type="button" class="btn btn-default">
                <span class="glyphicon glyphicon-backwards" aria-hidden="true"></span> Previous Page ({{ (floor($start / $steps) ?: 1) }})
            </button>
        </a>
    @endif
@endsection