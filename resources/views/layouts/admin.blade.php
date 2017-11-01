<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@lang('page_titles.' . Route::currentRouteName())</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('pictures/favicon.png') }}"/>
    
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @stack('stylesheets')
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="#" class="site_title"> <span>@lang('blade_templates.admin.sidebar.title')</span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile">
                        <div class="profile_pic">
                            <img src="{{ asset('pictures/user.png') }}" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">

                            <span>@lang('blade_templates.admin.sidebar.welcome')</span>
                            <h2>{{ Auth::user()->name }}</h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>@lang('blade_templates.admin.sidebar.general')</h3>
                            <ul class="nav side-menu">
                                <li><a href="{{ route('admin.home') }}"><i class="fa fa-home"></i> @lang('blade_templates.admin.sidebar.home') </a></li>
                                <li><a><i class="fa fa-edit"></i> @lang('blade_templates.admin.sidebar.items') <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('tags.index') }}">@lang('blade_templates.admin.sidebar.tags')</a></li>
                                        <li><a href="{{ route('articles.index') }}">@lang('blade_templates.admin.sidebar.articles')</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('users.index') }}"><i class="fa fa-users"></i> @lang('blade_templates.admin.sidebar.users') </a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a id="goFS" data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a href="{{ route('home') }}" data-toggle="tooltip" data-placement="top" title="Home">
                            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        @include('blocks.profile')
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                @include('blocks.alerts')
                @yield('content')
            </div>
            <!-- /page content -->

            <!-- footer content -->
            <footer>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
        </div>
    </div>

    
    <script src="{{ asset('js/admin.js') }}"></script>
    @stack('scripts')
</body>
</html>