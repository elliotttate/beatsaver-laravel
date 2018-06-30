<ul class="nav navbar-nav">
    <li class="@if(Route::currentRouteName() == 'home')active @endif"><a href="{{ route('home') }}">Home</a></li>
    <li class="@if(Route::currentRouteName() == 'browse.top.downloads')active @endif"><a href="{{ route('browse.top.downloads') }}">Top Downloads</a></li>
    <li class="@if(Route::currentRouteName() == 'browse.top.played')active @endif"><a href="{{ route('browse.top.played') }}">Top Played</a></li>
    <li class="@if(Route::currentRouteName() == 'browse.top.newest')active @endif"><a href="{{ route('browse.top.newest') }}">Newest</a></li>
    <li><a href="https://discord.gg/ZY8T8ky">Mod Discord</a></li>
    <li class="@if(Route::currentRouteName() == 'search.form' || Route::currentRouteName() == 'browse.user' || Route::currentRouteName() == 'browse.detail' )active @endif"><a href="{{ route('search.form') }}">Search</a></li>
    <li><a href="https://scoresaber.com/">ScoreSaber</a></li>
    <li><a href="https://github.com/Umbranoxio/BeatSaberModInstaller/releases">Mod Installer</a></li>
    @auth
        <li class="@if(Route::currentRouteName() == 'upload.form')active @endif"><a href="{{ route('upload.form') }}">Upload</a></li>
        <li class="@if(Route::currentRouteName() == 'profile')active @endif"><a href="{{ route('profile') }}">{{ auth()->user()->name }}</a></li>
        <li><a href="{{ route('logout') }}">Logout</a></li>
    @else
        <li class="@if(Route::currentRouteName() == 'login.form' || Route::currentRouteName() == 'register.form'
        || Route::currentRouteName() == 'password.reset.request.form' || Route::currentRouteName() == 'password.reset.complete.form')active @endif"><a
                    href="{{ route('login.form') }}">Login / Register</a></li>
    @endauth
</ul>