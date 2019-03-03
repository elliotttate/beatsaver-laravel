@extends('themes.bulma.layout')
@section('title', '- Profile')

@section('content')
    <div class="content">
        <div style="display: flex;flex-direction: column;align-items: center;">
            <h1>API Access Tokens <i>(max 4)</i></h1>
            @foreach($tokens as $token)
                <form style="margin-bottom: 10px;width: 100%;max-width: 750px;" method="post" action="{{ route('profile.token.submit') }}">
                    {{ csrf_field() }}
                    <div class="field has-addons">
                        <div class="control">
                            <a class="button is-static">
                                <i class="fas @if($token->type == \App\Models\AccessToken::TYPE_READ_WRITE) fa-pencil-alt @else fa-lock @endif"></i>
                            </a>
                        </div>
                        <div class="control is-expanded">
                            <input type="text" class="input" value="{{ $token->token }}" readonly>
                        </div>
                        <div class="control">
                            <button name="delete" value="{{ $token->id }}" class="button is-danger" type="submit">Delete</button>
                        </div>
                    </div>
                </form>
            @endforeach

            @if($tokens->count() < $max)
                <form class="form-signin" method="post" action="{{ route('profile.token.submit') }}">
                    {{ csrf_field() }}
                    <button name="new" value="0" class="button is-primary" type="submit">New Read-Only Token</button>
                    <button name="new" value="1" class="button is-warning" type="submit">New Read/Write Token</button>
                </form>
            @endif
        </div>
    </div>
@endsection