<a href="{{ route('admin.dashboard') }}" class="logo">
    <span class="logo-mini"><b>BS</b></span>
    <span class="logo-lg"><b>{{ config('app.name') }}</b></span>
</a>
<nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li class="user user-menu">
                <a href="#">
                    <span class="hidden-xs">{{ Auth::user()->name }}</span>
                </a>
            </li>
            <li class="user user-menu">
                <a id="logoutButton">
                    <span class="">Logout</span>
                </a>
            </li>
            <form id="logoutForm" method="post" action="{{ route('logout') }}">
                @csrf
            </form>
        </ul>
    </div>
</nav>

@push('scripts')
    <script type="application/javascript">
        $('#logoutButton').click(function () {
            submitLogoutRequest()
        });

        function submitLogoutRequest() {
            $('#logoutForm').submit();
        }
    </script>
@endpush
