<ul class="nav navbar-nav navbar-right">
    @guest
        <li><a href="{{ route('login') }}">@lang('blade_templates.global.login')</a></li>
        <li><a href="{{ route('register') }}">@lang('blade_templates.global.register')</a></li>
    @else
        <li>
            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="{{ Auth::user()->getAvatar() }}" alt="..." >
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('profile') }}"> @lang('blade_templates.global.profile')</a></li>
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        @lang('blade_templates.global.logout')
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
         </li>
    @endguest
</ul>