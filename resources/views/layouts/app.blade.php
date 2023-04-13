<!doctype html>
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite('resources/css/app.css')
</head>
<body class="theme-light bg-page" id="body">
<div id="app">
    <nav class="bg-header">
        <div class="container mx-auto">
            <div class="flex justify-between items-center py-2">
                <h1>
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="logo.svg" alt="Birdboard">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </h1>
                <div>
                    <!-- Left Side Of Navbar -->


                    <!-- Right Side Of Navbar -->
                    <div class="flex items-center ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            @endif

                            @if (Route::has('register'))
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        @else
                            <div class="mr-8 flex items-center">
                                <button id="lightBtn"
                                        class="rounded-full w-4 h-4 bg-white border border-default mr-2"></button>
                                <button id="darkBtn"
                                        class="rounded-full w-4 h-4 bg-black border border-default mr-2"></button>
                            </div>
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
            <script>
                const lightBtn = document.getElementById('lightBtn')
                const darkBtn = document.getElementById('darkBtn')
                const body = document.getElementById('body')
                lightBtn.addEventListener('click', () => {
                    const classes = Array.from(body.classList)
                    const regex = new RegExp('theme-*');
                    const currentTheme = classes.find(item => regex.test(item))
                    body.classList.remove(currentTheme);
                    body.classList.add('theme-light');
                })
                darkBtn.addEventListener('click', () => {
                    const classes = Array.from(body.classList)
                    const regex = new RegExp('theme-*');
                    const currentTheme =classes.find(item => regex.test(item))
                    body.classList.remove(currentTheme);
                    body.classList.add('theme-dark');
                })
            </script>

        </div>
    </nav>

    <main class="container mx-auto py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
