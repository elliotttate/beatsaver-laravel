<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1024">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">


    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
          crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <title>Beat Saver @yield('title')</title>
</head>
<body>

<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php"><img src="{{ asset('/img/beat_saver_logo_white.png') }}" height="35em" style="margin-top: -7px;"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            @include('nav')
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-1"><br><br><br><br></div>
    </div>
    @if($errors->isNotEmpty())
        <div class="row">
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        @yield('content')
    </div>
    <hr>
</div> <!-- /container -->
<footer>
    <center>
        <p>
            <b><a href="{{ route('legal.dmca') }}">DMCA Copyright Form</a> || <a href="{{ route('legal.privacy') }}">Privacy</a> || <a href="{{ config('beatsaver.githubUrl') }}">Github</a></b>
        </p>
    </center>
</footer>

</body>
</html>