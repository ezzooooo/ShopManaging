<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        Cookie::queue('today', date('Y-m-d', strtotime(now())));
    @endphp

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!--
    <script src="/js/reservation.js"></script>
    <script src="/js/timetable.js"></script> 
    -->
    <script src="/js/manifest.js"></script>
    <script src="/js/vendor.js"></script>
    <script src="/js/app.js"></script>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet">

</head>
<body>
    <div id="app">
        <div class="container main-container">
        <nav class="nav d-flex justify-content-center navbar-laravel">
            <div class="menubar">
                @guest
                <a class="title-text col-md-2" href="{{ url('/') }}">
                    <strong>{{ config('app.name', 'Secredule') }}</strong>
                </a>
                <!--
                <a class="navbar-light col-md-2" style="flex: 0 0 20%; max-width: 20%;" href="{{ route('reservations.create') }}">
                    예약문의
                </a>
                -->
                @else
                <a class="title-text col-md-2" href="{{ url('/') }}">
                    <strong>{{ config('app.name', 'Laravel') }}</strong>
                </a>
                <a class="navbar-light col-md-1" href="{{ route('timetable.index') }}">
                    타임테이블
                </a>
                 <a class="navbar-light col-md-1" href="{{ route('reservations.index') }}">
                    예약 리스트
                </a>
                <a class="navbar-light col-md-2" style="flex: 0 0 20%; max-width: 20%;" href="{{ route('reservations.create') }}">
                    예약추가
                </a>
                <a class="navbar-light col-md-1" href="{{ route('customer.index') }}">
                    고객관리
                </a>
                <a class="navbar-light col-md-2" href="{{ route('sales.index') }}">
                    매출관리
                </a>
                <a class="navbar-light col-md-2" href="{{ route('purchase.index') }}">
                    매입관리
                </a>
                <a class="navbar-light col-md-2" href="{{ route('purchaseofsales.index') }}">
                    매출매입정보
                </a>
                <a class="navbar-light col-md-1" href="{{ route('notice.index') }}">
                    공지사항
                </a>
                <a class="navbar-light col-md-1" href="{{ route('product.index') }}">
                    상품관리
                </a>
                <a class="navbar-light col-md-1" href="{{ route('user.index') }}">
                    내정보
                </a>
                @endguest
                @guest
                @else
                <button class="navbar-light logout-button" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @endguest
                <!--
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent"> -->
                    <!-- Left Side Of Navbar 
                    <ul class="navbar-nav mr-auto">

                    </ul>
                    -->
                    <!-- Right Side Of Navbar 
                    <ul class="navbar-nav ml-auto"> -->
                        <!-- Authentication Links 
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
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
                        -->
                        <!--
                    </ul>
                </div>
                -->
            </div>
        </nav>

        <div class="main-header">
            <div class="row">
                <div class="main-logo col-md-4" style="float:left;">
                    <img src="/css/images/logo.png" width="315" height="113">
                </div>
                <div class="main-menubar col-md-7" style="float:left;">
                    <div class="menu-title menu-title-home d-inline"><a class="menu-content" href="#"> Home </a></div>
                    <div class="menu-title menu-title-reserve d-inline"><a class="menu-content" href="#">예약</a></div>
                    <div class="menu-title menu-title-reserve-customer d-inline"><a class="menu-content" href="#">예약 손님 현황</a></div>
                    <div class="menu-title menu-title-customer d-inline"><a class="menu-content" href="#">고객 관리 페이지</a></div>
                    <div class="menu-title menu-title-memo d-inline"><a class="menu-content" href="#">MEMO</a></div>
                    <img class="menu-title d-inline" src="/css/images/mag_glass.png">
                </div>
            </div>
        </div>
        
        <main class="py-4 main-content container main-container">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
