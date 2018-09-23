@extends('themes.default.layout')
@section('title', '- Profile')

@section('content')
    <div class="container text-center">
        <h1 class="form-signin-heading text-center">API Access Tokens (max 4)</h1>
        @foreach($tokens as $token)
            <div class="row ">
                <div class="col-md-8 col-md-offset-2">
                    <form class="form-signin" method="post" action="{{ route('profile.token.submit') }}">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <span class="input-group-addon"><span
                                        class="glyphicon @if($token->type == \App\Models\AccessToken::TYPE_READ_WRITE) glyphicon-pencil @else glyphicon-lock @endif"></span></span>
                            <input type="text" class="form-control" value="{{ $token->token }}" readonly>
                            <span class="input-group-btn">
                            <button name="delete" value="{{ $token->id }}" class="btn btn-default btn-danger" type="submit">delete</button>
                      </span>
                        </div>
                    </form>
                </div>
            </div>
            <br/>
        @endforeach

        @if($tokens->count() < $max)
            <form class="form-signin" method="post" action="{{ route('profile.token.submit') }}">
                {{ csrf_field() }}
                <button name="new" value="0" class="btn btn-default btn-primary" type="submit">New Read Only Token</button>
                <button name="new" value="1" class="btn btn-default btn-warning" type="submit">New Read/Write Token</button>
            </form>
        @endif
    </div>
@endsection