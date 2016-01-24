
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <!-- Branding Image -->
            @if(Auth::check())
                <a class="navbar-brand" href="{{ route('admin.users.index', $domain) }}">Queueless</a>
            @else
                <a class="navbar-brand" href="{{ route('pages.home') }}">Queueless</a>
            @endif
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            @if(Auth::guest())
                <ul class="nav navbar-nav">
                    <li><a href="{{ route('pages.home') }}">Home</a></li>
                    <li><a href="http://github.com/rajabishek/queueless" target="_blank">Source</a></li>
                </ul>
            @endif
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ route('auth.getRegister') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->fullname }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('auth.logout', $domain) }}">Logout</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">Nav header</li>
                            <li><a href="#">Separated link</a></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>