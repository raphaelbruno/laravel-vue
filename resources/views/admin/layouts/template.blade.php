<?php
    $avatar = isset(Auth::user()->profile) && isset(Auth::user()->profile->avatar)
        ? empty(parse_url(Auth::user()->profile->avatar)['scheme'])
            ? asset(Storage::url(Auth::user()->profile->avatar))
            : Auth::user()->profile->avatar
        : asset('img/avatar.jpg');
?>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" />

    <!-- Scripts -->
    <script defer src="{{ asset('js/admin.js') }}"></script>
    <script defer src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script defer src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
    <script defer src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script defer src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script defer src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script defer src="{{ asset('plugins/jquery-mask/jquery.mask.min.js') }}"></script>
    <script defer src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script defer src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script defer src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script defer src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script defer src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script defer src="{{ asset('plugins/cryptojs/sha1.js') }}"></script>

    <script defer src="{{ asset('plugins/adminlte/js/adminlte.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('plugins/adminlte/css/adminlte.min.css') }}">
    

    <!-- Admin Assets -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .ajaxloader { border-top-color: {{ app('config')->get('template')['theme'] }}; }
    </style>

    <script defer>
        top.URL_SITE = "{{ url('/') }}";
        top.URL_ADMIN = "{{ route('admin:dashboard') }}";
        top.LOCALE = '{{ Config::get('app.locale') }}';
        top.DATE_FORMAT = '{{ app('config')->get('template')['dateformat'] }}';

        window.translation = <?php
            $languageFiles = File::files(resource_path() . '/lang/' . App::getLocale());
            $translation = [];
            foreach ($languageFiles as $f) {
                $filename = pathinfo($f)['filename'];
                $translation[$filename] = trans($filename);
            }
            echo json_encode($translation);
        ?>;
    </script>
    <script defer src="{{ asset('js/multiple-upload.js') }}"></script>
    <script defer src="{{ asset('js/admin-actions.js') }}"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed {{ app('config')->get('template')['dark-mode'] ? 'dark-mode' : '' }}">
    <div id="app" class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark bg-{{ app('config')->get('template')['theme'] }}">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/') }}" class="nav-link">@lang('admin.site')</a>
                </li>
            </ul>


            <ul class="navbar-nav ml-auto">
                <li class="dropdown user user-menu open">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                        <i class="nav-icon fas fa-user mr-1"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <li class="user-header bg-{{ app('config')->get('template')['theme'] }}">
                        <img src="{{ $avatar }}" class="img-circle" alt="User Image">
                        <p>
                            <b>{{ Auth::user()->name }}</b>
                            <small>{{ Auth::user()->email }}</small>
                            <small>{{ Auth::user()->rolesToString() }}</small>
                        </p>
                    </li>
                    <li class="user-footer d-flex">
                        <div class="mr-auto">
                        <a href="{{ route('admin:profile') }}" class="btn btn-secondary">@lang('admin.profile')</a>
                        </div>
                        <div class="ml-auto">
                            <a class="btn btn-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                @lang('admin.logout')
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-{{ app('config')->get('template')['theme'] }} elevation-4">
            <!-- Brand Logo -->
            <a href="{{ url('/admin') }}" class="brand-link navbar-{{ app('config')->get('template')['theme'] }}">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img class="img-circle elevation-2" src="{{ $avatar }}" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="{{ route('admin:profile') }}" class="d-block">{{ Auth::user()->shortName() }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        @foreach(app('config')->get('template')['menu'] as $item)
                            @if(App\Helpers\TemplateHelper::displayMenu($item))
                            <li class="nav-item {{ isset($item['children']) ? 'has-treeview' : '' }}  {{ App\Helpers\TemplateHelper::isMenuActive($item) ? 'menu-open' : '' }}">
                                <a href="{{ isset($item['action']) ? route($item['action']) : 'javascript:void(0);' }}" class="nav-link {{ App\Helpers\TemplateHelper::isMenuActive($item) ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-{{ $item['icon'] }}"></i>
                                    <p>
                                        @lang($item['name'])
                                        @if(isset($item['children']))
                                        <i class="fas fa-angle-left right"></i>
                                        @endif
                                    </p>
                                </a>
                                @if(isset($item['children']))
                                <ul class="nav nav-treeview">
                                    @foreach($item['children'] as $subitem)
                                        @if(App\Helpers\TemplateHelper::displayMenu($subitem))
                                        <li class="nav-item">
                                            <a href="{{ isset($subitem['action']) ? route($subitem['action']) : 'javascript:void(0);' }}" class="nav-link {{ App\Helpers\TemplateHelper::isMenuActive($subitem) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-{{ $subitem['icon'] }}"></i>
                                                <p>@lang($subitem['name'])</p>
                                            </a>
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
        <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {!! $message !!}
                    </div>
                    @endif
                    
                    @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {!! $message !!}
                    </div>
                    @endif
                    
                    @if ($message = Session::get('warning'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {!! $message !!}
                    </div>
                    @endif
                    
                    @if ($message = Session::get('info'))
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {!! $message !!}
                    </div>
                    @endif
                    
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {!! $errors->first() !!}
                    </div>
                    @endif
                    
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">@yield('title')</h1>
                        </div>
                        
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('/admin') }}"><i class="fas fa-home"></i> @lang('admin.dashboard')</a></li>
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('main')
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->


        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}.</strong>
            @lang('admin.all-rights')
            <div class="float-right d-none d-sm-inline-block">
                <b>@lang('admin.version')</b> {{ app('config')->get('template')['version'] }}
            </div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        </aside>
    </div>
    <!-- ./wrapper -->
</body>
</html>
