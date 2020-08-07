<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name',$CompanyInfo->company_name  ) }}</title>
   




    @stack('css')
    <!-- Font Awesome css -->
    <link href="{{ asset('public/assets/css/all.min.css') }}" rel="stylesheet">
    <!-- Bootstrap css -->
    <link href="{{ asset('public/assets/css/icheck-bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap css -->
    <link href="{{ asset('public/assets/css/bootstrap.css') }}" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('public/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/css/login.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{  $CompanyInfo->company_name }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto main-menu">
                        <!-- Authentication Links -->
                        @guest
                       
                            <li class="nav-item">
                            <a class="nav-link" href="{{route('admin.login') }}">Admin Login</a>
                            
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{route('employee.login') }}">Employee Login</a></li>
                            
                        @else
        
                            <li class="nav-item">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <a class="nav-link" href="{{ route('logout') }}" 
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"> <i class="fa fa-sign-out"></i>
                                        {{ __('Logout') }}
                                </a>
                            </form>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
        <!-- jqeury -->
        <script src="{{ asset('public/assets/js/jquery.js') }}" defer></script>
        <!-- Bootstrap js -->
       <script src="{{ asset('public/assets/js/bootstrap.min.js') }}" defer></script>
       @stack('js')
</body>
</html>