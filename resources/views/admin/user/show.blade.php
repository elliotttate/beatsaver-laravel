@extends('admin.layout.app')

@section('content')
    <section class="content-header">
        <h1>
            {{ $user->name }}
            <small>User page</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">User info</h3>

                    </div>
                    <div class="box-body">
                        <form method="post" action="{{ route('admin.users.update', ['user' => $user]) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group {{ !$errors->has('name') ?: 'has-error' }}">
                                @foreach($errors->get('name') as $message)
                                    <label class="control-label" for="name">{{ $message }}</label><br>
                                @endforeach
                                <label for="name">Name</label>
                                <input name="name" id="name" value="{{ old('name') ? old('name') : $user->name }}" type="text" placeholder="Name" class="form-control">
                            </div>
                            <div class="form-group {{ !$errors->has('email') ?: 'has-error' }}">
                                @foreach($errors->get('email') as $message)
                                    <label class="control-label" for="email">{{ $message }}</label><br>
                                @endforeach
                                <label for="email">Email</label>
                                <input name="email" id="email" value="{{ old('email') ? old('email') : $user->email }}" type="email" placeholder="Email" class="form-control">
                            </div>
                            <div class="form-group {{ !$errors->has('password') ?: 'has-error' }}">
                                @foreach($errors->get('password') as $message)
                                    <label class="control-label" for="password">{{ $message }}</label><br>
                                @endforeach
                                <label for="password">Password</label>
                                <input name="password" id="password" value="{{ old('password') ? old('password') : '' }}" type="password" placeholder="Leave empty for current password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label id="admin">
                                    <input name="admin" id="admin" value="1" type="checkbox" {{ !$user->admin ?: 'checked' }}>
                                    Administrator
                                </label>
                            </div>
                            <div class="form-group">
                                <label id="banned">
                                    <input name="banned" id="banned" value="1" type="checkbox" {{ !$user->deleted_at ?: 'checked' }}>
                                    Banned
                                </label>
                            </div>
                            <button type="submit" class="btn btn-success pull-right">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
