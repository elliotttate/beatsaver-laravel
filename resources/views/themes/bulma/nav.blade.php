<div class="navbar-start">
    <a class="navbar-item" href="{{ route('browse.top.newest') }}">Newest</a>
    <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">Sort</a>
        <div class="navbar-dropdown">
            <a class="navbar-item" href="{{ route('browse.top.downloads') }}">Top Downloads</a>
            <a class="navbar-item" href="{{ route('browse.top.played') }}">Top Played</a>
            <!-- <a class="navbar-item" href=" route('browse.top.rated') ">Top Rated</a> -->
        </div>
    </div>
    <a class="navbar-item" href="{{ route('search') }}">Search</a>
    <div class="navbar-item" style="user-select: none;">|</div>
    <a href="https://scoresaber.com/" class="navbar-item">ScoreSaber</a>
    <a href="https://bsaber.com/" class="navbar-item">BeastSaber</a>
    <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">Modding</a>
        <div class="navbar-dropdown">
            <a class="navbar-item" href="https://bsmg.wiki/beginners-guide">Install Mods</a>
            <a class="navbar-item" href="https://discord.gg/beatsabermods">Modding Discord</a>
            <a class="navbar-item" href="https://bsmg.wiki/">Community Wiki</a>
        </div>
    </div>
</div>
<div class="navbar-end">
    @auth
        <a class="navbar-item" href="{{ route('upload.form') }}">Upload</a>
        <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">{{ auth()->user()->name }}</a>
            <div class="navbar-dropdown">
                <a class="navbar-item" href="{{ route('browse.user',['id' => auth()->id()]) }}">My Songs</a>
                <hr class="navbar-divider" />
                <a class="navbar-item" href="{{ route('profile') }}">Profile</a>
                <a class="navbar-item" href="{{ route('profile.token') }}">Access Tokens</a>
                <hr class="navbar-divider" />
                <a class="navbar-item" href="{{ route('logout') }}">Logout</a>
            </div>
        </div>

    @else
        <a class="navbar-item" href="{{ route('login.form') }}">Login / Register</a>
    @endauth

</div>
