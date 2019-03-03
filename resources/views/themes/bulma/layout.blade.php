<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="has-navbar-fixed-top">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@yield('og-meta')

@section('feeds')
    <link href="{{ route('feeds.newest') }}" rel="alternate" title="Beat Saver - Newest" type="application/atom+xml">
@show

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <!-- Navbar Burger Styles -->
    <style>a.navbar-burger { color: white; } a.navbar-burger:hover { color: rgb(220, 220, 220); }</style>

    <!-- Babel and Polyfills -->
    <script src="https://unpkg.com/@babel/polyfill/dist/polyfill.min.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

    <!-- OneClick Installs -->
    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    <script type="text/babel" src="{{ asset('/js/oneclick.js') }}"></script>

    <!-- Preview JS -->
    <script src="{{ asset('/js/zip/zip.js') }}"></script>
    <script type="text/babel" src="{{ asset('/js/preview.js') }}"></script>
    <script type="text/babel">
        zip.workerScriptsPath = '/js/zip/'

        const preview = new PreviewPlayer(0.15)
        preview.onEnd = () => {
            for (const btn of document.getElementsByClassName('for-playing')) {
                btn.dataset.playing = false
                btn.innerHTML = 'Preview'
            }
        }

        const previewSong = async (button, url) => {
            const playing = button.dataset.playing === 'true'
            
            if (playing) {
                preview.stop()
                button.innerHTML = 'Preview'
            } else {
                button.classList.toggle('is-loading')
                await preview.play(url)

                button.classList.toggle('is-loading')

                for (const btn of document.getElementsByClassName('for-playing')) {
                    btn.dataset.playing = false
                    btn.innerHTML = 'Preview'
                }

                button.innerHTML = 'Stop Preview'
            }

            button.dataset.playing = !playing
        }

        window.preview = preview
        window.previewSong = previewSong
    </script>

    <title>Beat Saver @yield('title')</title>
</head>
<body>

<!-- Fixed navbar -->
<nav class="navbar has-shadow is-dark is-fixed-top">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-item" href="{{ route('home') }}"><img src="{{ asset('/themes/default/img/beat_saver_logo_white.png') }}"></a>

            <a role="button" class="navbar-burger" data-target="navMenu" aria-label="menu" aria-expanded="false">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>
        <div class="navbar-menu" id="navMenu">
            @include('themes.bulma.nav')
        </div>
    </div>
</nav>

<div class="container" style="padding: 0 15px">
    <hr>
    @if($errors->isNotEmpty())
        <article class="message is-danger">
            <div class="message-body">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        </article>
    @endif
    @if(session('status-error'))
        <article class="message is-danger">
            <div class="message-body">
                {{ session('status-error') }}
            </div>
        </article>
    @endif
    @if(session('status-warning'))
        <article class="message is-warning">
            <div class="message-body">
                {{ session('status-warning') }}
            </div>
        </article>
    @endif
    @if(session('status-success'))
        <article class="message is-success">
            <div class="message-body">
                {{ session('status-success') }}
            </div>
        </article>
    @endif
    
    @yield('content')
    <hr>
</div> <!-- /container -->
<footer style="margin-bottom: 25px;">
    <div class="content has-text-centered">
        <ul class="table-spacing" style="font-weight: bold; margin: 0;">
            <li><a href="{{ route('legal.dmca') }}">DMCA Copyright Form</a></li>
            <li><a href="{{ route('legal.privacy') }}">Privacy</a></li>
            <li><a href="{{ config('beatsaver.githubUrl') }}">GitHub</a></li>
        </ul>
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

<script type="text/babel">
    const burgers = document.getElementsByClassName('navbar-burger')

    for (const burger of burgers) {
        burger.addEventListener('click', () => {
            const target = document.getElementById(burger.dataset.target)

            burger.classList.toggle('is-active')
            target.classList.toggle('is-active')
        })
    }
</script>

</body>
</html>
