<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="has-navbar-fixed-top">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1024">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@yield('og-meta')
<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bulma@0.7.1/css/bulma.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <title>Beat Saver @yield('title')</title>
</head>
<body>

<!-- Fixed navbar -->
<nav class="navbar has-shadow is-dark is-fixed-top">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-item" href="{{ route('home') }}"><img src="{{ asset('/themes/default/img/beat_saver_logo_white.png') }}"></a>
        </div>
        <div class="navbar-menu">
            @include('themes.default.nav')
        </div>
    </div>
</nav>

<div class="container">
    <hr>
    @if($errors->isNotEmpty())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('status-error'))
        <br/>
        <div class="alert alert-danger" role="alert">{{ session('status-error') }}</div>
    @endif
    @if(session('status-warning'))
        <br/>
        <div class="alert alert-warning" role="alert">{{ session('status-warning') }}</div>
    @endif
    @if(session('status-success'))
        <br/>
        <div class="alert alert-success" role="alert">{{ session('status-success') }}</div>
    @endif
    <div class="row">
        @yield('content')
    </div>
    <hr>
</div> <!-- /container -->
<footer>
    <div class="content has-text-centered">
        <b><a href="{{ route('legal.dmca') }}">DMCA Copyright Form</a> || <a href="{{ route('legal.privacy') }}">Privacy</a> || <a href="{{ config('beatsaver.githubUrl') }}">GitHub</a></b>
    </div>
    @if( App::environment() == 'production' && config('beatsaver.tracking'))
        <script type="text/javascript">
            var _paq = _paq || [];
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            _paq.push(['setRequestMethod', 'POST']);
            _paq.push(["setRequestMethod", "POST"]);
            (function () {
                var u = "//beatsaver.com/track/";
                _paq.push(['setRequestMethod', 'POST']);
                _paq.push(['setTrackerUrl', u + 'console.php']);
                _paq.push(['setSiteId', '1']);
                var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
                g.type = 'text/javascript';
                g.async = true;
                g.defer = true;
                g.src = u + 'console.js';
                s.parentNode.insertBefore(g, s);
            })();
        </script>
    @endif
</footer>

</body>
</html>
