<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Personal Finances Assistant') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        #imgNavbar {
            border-radius: 75%;
        }
        .py-4 div{
            margin-bottom: 15px;
        }
        div p{
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                        <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                                @if (empty(Auth::user()->profile_photo))
                                    <img id="imgNavbar" href="#" width="35" height="35"  src="{{ asset('storage/profiles/default.jpeg') }}">
                                @else
                                    <img id="imgNavbar" href="#" width="35" height="35"  src="{{ asset('storage/profiles/' . Auth::user()->profile_photo) }}">
                                @endif
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{route('home')}}">Home</a>
                                @if (Auth::user()->admin==1)
                                    <a class="dropdown-item" href="{{ route('users.list') }}">{{ __('User list') }}</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('users.profiles') }}">{{ __('Profiles') }}</a>
                                <a class="dropdown-item" href="{{ route('users.associates') }}">{{ __('My associates') }}</a>
                                <a class="dropdown-item" href="{{ route('users.associate_of') }}">{{ __('Associate Of') }}</a>
                                <a class="dropdown-item" href="{{route('accounts.users', Auth::user())}}">{{__('My accounts')}}</a>
                                <a class="dropdown-item" href="{{route('user.statistics', Auth::user())}}">{{__('Dashboard')}}</a>
                                <a class="dropdown-item" href="{{route('users.changePasswordForm')}}">{{__('Change Password')}}</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
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
</body>
</html>
