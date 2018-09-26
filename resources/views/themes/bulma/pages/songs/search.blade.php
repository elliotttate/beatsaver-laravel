@extends('themes.bulma.layout')
@section('title', '- '.$title)

@section('content')
    <div style="display: flex;flex-direction: column;align-items: center;">
        <form style="max-width: 600px;width: 100%;" method="post" action="{{ route('search.submit') }}">
            {{ csrf_field() }}

            <div class="field">
                <div class="control has-icons-left">
                    <input type="text" id="inputSearchKey" class="input" placeholder="Search for... Beat Name / Song Name / Author / Username" name="key" minlength="3" value="{{ $key }}"
                           required autofocus>
                    <span class="icon is-small is-left">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
            
            <button class="button is-link is-fullwidth" type="submit">Search</button>
        </form>
    </div>

    @each('themes.bulma.pages.songs.partial-preview',$songs,'song')
    @include('themes.bulma.pages.songs.partial-paging')
@endsection