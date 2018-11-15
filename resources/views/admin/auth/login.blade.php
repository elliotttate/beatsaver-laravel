<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('og-meta')
    <title>Beat Saver @yield('title')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
    <!-- AdminLTE Skin -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/skins/skin-blue.min.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/login.css') }}">
</head>
<body class="hold-transition skin-blue">
<div class="admin-login-container">
        <div class="col-lg-4 col-md-6 col-sm-10 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h1 class="box-title">Administration Login</h1>
                </div>
                <div class="box-body">
                    <form method="post" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
<!-- Jquery 3.3.1 -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<!-- Bootstrap JS 3.3.7 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
</body>
</html>
