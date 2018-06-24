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
            <ul class="nav navbar-nav">
                <li class="@if(Route::currentRouteName() == 'home')active @endif"><a href="{{ route('home') }}">Home</a></li>
                <li class="@if(Route::currentRouteName() == 'browse.top.downloads')active @endif"><a href="{{ route('browse.top.downloads') }}">Top Downloads</a></li>
                <li class="@if(Route::currentRouteName() == 'browse.top.played')active @endif"><a href="{{ route('browse.top.played') }}">Top Played</a></li>
                <li class="@if(Route::currentRouteName() == 'browse.top.newest')active @endif"><a href="{{ route('browse.top.newest') }}">Newest</a></li>
                <li><a href="https://discord.gg/ZY8T8ky">Mod Discord</a></li>
                <li class="@if(Route::currentRouteName() == 'search.form')active @endif"><a href="{{ route('search.form') }}">Search</a></li>
                <li><a href="https://scoresaber.com/">ScoreSaber</a></li>
                <li><a href="https://github.com/Umbranoxio/BeatSaberModInstaller/releases">Mod Installer</a></li>
                @auth
                    <li class="@if(Route::currentRouteName() == 'upload.form')active @endif"><a href="upload.php">Upload</a></li>
                    <li class="@if(Route::currentRouteName() == 'profile')active @endif"><a href="{{ route('profile') }}">{{ auth()->user()->name }}</a></li>
                    <li><a href="{{ route('logout') }}">Logout</a></li>
                @else
                    <li class="@if(Route::currentRouteName() == 'login.form' || Route::currentRouteName() == 'register.form' || Route::currentRouteName() == 'forgotpw.form')active @endif"><a
                                href="{{ route('login.form') }}">Login / Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-1"><br><br><br><br></div>
    </div>
    <div class="row">
        @yield('content')
    </div>
    <hr>
</div> <!-- /container -->
<footer>
    <center><p><b><a href="{{ route('legal.dmca') }}">DMCA Copyright Form</a> || <a href="{{ route('legal.privacy') }}">Privacy</a> ||<a href="https://dev.beatsaver.com/index.html#/">New Alpha Interface</a> || <a
                        href="https://github.com/beatsaver/beatsaver">Github</a></b>
        </p></center>
</footer>

</body>
</html>