@extends('admin.layout.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3 col-md-12 content-header">
                <h1>
                    Create new user
                </h1>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3 col-md-12">
                <div class="box">
                    <div class="box-body">
                        <form method="post" action="{{ route('admin.users.store') }}">
                            @csrf
                            <div class="form-group {{ !$errors->has('name') ?: 'has-error' }}">
                                @foreach($errors->get('name') as $message)
                                    <label class="control-label" for="name">{{ $message }}</label><br>
                                @endforeach
                                <label for="name">Name</label>
                                <input name="name" id="name" value="{{ old('name') }}" type="text" placeholder="Name" class="form-control">
                            </div>
                            <div class="form-group {{ !$errors->has('email') ?: 'has-error' }}">
                                @foreach($errors->get('email') as $message)
                                    <label class="control-label" for="email">{{ $message }}</label><br>
                                @endforeach
                                <label for="email">Email</label>
                                <input name="email" id="email" value="{{ old('email') }}" type="email" placeholder="Email" class="form-control">
                            </div>
                            <div class="form-group {{ !$errors->has('password') ?: 'has-error' }}">
                                @foreach($errors->get('password') as $message)
                                    <label class="control-label" for="password">{{ $message }}</label><br>
                                @endforeach
                                <label for="password">Password</label>
                                <input name="password" id="password" value="{{ old('password') }}" type="password" placeholder="Password" class="form-control">
                            </div>
                            <div class="form-group {{ !$errors->has('password_confirmation') ?: 'has-error' }}">
                                @foreach($errors->get('password_confirmation') as $message)
                                    <label class="control-label" for="password_confirmation">{{ $message }}</label><br>
                                @endforeach
                                <label for="password_confirmation">Password Confirmation</label>
                                <input name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" type="password" placeholder="Password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label id="admin">
                                    <input name="admin" id="admin" value="1" type="checkbox" {{ !old('admin') ?: 'checked' }}>
                                    Administrator
                                </label>
                            </div>
                            <button type="submit" class="btn btn-success pull-right">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
