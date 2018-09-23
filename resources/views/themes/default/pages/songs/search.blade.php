@extends('themes.default.layout')
@section('title', '- '.$title)

@section('content')
    <div class="row">
        <form class="form-signin" method="post" action="{{ route('search.submit') }}">
            {{ csrf_field() }}
            <div class="col-md-6 col-md-offset-3">
                <div class="input-group">
                    <input type="text" id="inputSearchKey" class="form-control" placeholder="Search for... Beat Name / Song Name / Author / Username" name="key" minlength="3" value="{{ $key }}"
                           required autofocus>
                    <span class="input-group-btn">
                    <button class="btn btn-primary btn-block" type="submit">Search</button>
                </span>
                </div>
            </div>
        </form>
    </div>
    @each('themes.default.pages.songs.partial-preview',$songs,'song')
    @include('themes.default.pages.songs.partial-paging')
@endsection