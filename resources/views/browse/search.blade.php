@extends('layout')
@section('title', '- Search results')

@section('content')

    <div class="container">

        <form class="form-signin" method="post" action="{{ route('search.submit',['type' => 'all']) }}">
            {{ csrf_field() }}
            <input type="text" id="inputSearchKey" class="form-control" placeholder="Name / Song Name / Author" name="key" minlength="3" value="{{ $key }}" required autofocus>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Search</button>
            <br>
        </form>
    </div>

    @each('browse.song-data-frontpage',$songs,'song')
@endsection