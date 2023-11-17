<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <!-- <link rel="dns-prefetch" href="https://fonts.gstatic.com"> -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css"> -->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/font-awsome/css/all.min.css') }}">
    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/emoji-picker/lib/css/emoji.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/multi-select-search/jquery.dropdown.css') }}">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-lightgray navbar-laravel p-0" style="height: 80px;">
            <div class="custom_container ml-0">
                <a class="navbar-brand" style="padding-left: 3px;" href="{{ url('/') }}">
                    <img src="{{asset('assets/images/logo.png')}}" alt="app-logo" class="header-logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
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
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->fullName() }} <span class="caret"></span>
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
                    </ul>
                </div>
            </div>
        </nav>
        <div class="d-flex main-content-area">
        <div class="nav-wrapper" id="sidebar-menu">
            <ul class="left_nav_contant p-0 nav side-menu">
                <li><a href="{{ route('users.index') }}"><i class="fas fa-user mr-2"></i>Users</a></li>
                <li>
                    <a class="d-flex justify-content-between align-items-center"><span><i class="fas fa-map mr-2"></i>Locality</span> <i class="fas fa-angle-down"></i></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('countries.index') }}"><i class="fas fa-flag mr-2"></i>Countries</a></li>
                        <li><a href="{{ route('locations.index') }}"><i class="fas fa-map-marked-alt"></i> Areas</a></li>
                        <li><a href="{{ route('stores.index') }}"><i class="fas fa-store mr-2"></i>Stores</a></li>
                        <li><a href="{{ route('delivery-companies.index') }}"><i class="fas fa-shipping-fast"></i> Delivery Companies</a></li>
                    </ul>
                </li>
                <li>
                    <a class="d-flex justify-content-between align-items-center"><span><i class="fas fa-warehouse mr-2"></i>Inventory</span> <i class="fas fa-angle-down"></i></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('products.index') }}"><i class="fab fa-product-hunt mr-2"></i>Products</a></li>
                        <li><a href="{{ route('products.utility') }}"><i class="fab fa-product-hunt mr-2"></i>Test Utility</a></li>
                    </ul>
                </li>
                <li>
                    <a class="d-flex justify-content-between align-items-center"><span><i class="fas fa-check-circle mr-2"></i>Discounting System</span> <i class="fas fa-angle-down"></i></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('promotions.index') }}"><i class="fas fa-ad mr-2"></i>Promotions</a></li>
                        <li><a href="{{ route('coupons.index') }}"><i class="fab fa-cuttlefish mr-2"></i>Coupons</a></li>
                    </ul>
                </li>
                <li>
                    <a class="d-flex justify-content-between align-items-center"><span><i class="fab fa-adversal mr-2"></i>Sales</span> <i class="fas fa-angle-down"></i></a>
                    <ul style="display: none;">
                    <li><a href="{{ route('orders.index') }}"><i class="fas fa-dolly"></i> Orders</a></li>
                    </ul>
                </li>
                <li>
                    <a class="d-flex justify-content-between align-items-center"><span><i class="fas fa-tasks mr-2"></i>Management</span> <i class="fas fa-angle-down"></i></a>
                    <ul style="display: none;">
                        <li><a href="{{ route('notifications.index') }}"><i class="fas fa-bell mr-2"></i>Notification Center</a></li>
                        <li><a href="{{ route('customercares.index') }}"><i class="fab fa-intercom mr-2"></i>Customer Care</a></li>
                        <li><a href="{{ route('apps.index') }}"><img style="max-width: 20px;" src="{{ asset('assets/images/plugin.svg') }}"> App Integration</a></li>
                    </ul>
                </li>
                <li id="subMenu">
                    <a class="d-flex justify-content-between align-items-center"><span><i class="fas fa-cogs mr-2"></i>Settings</span> <i class="fas fa-angle-down"></i></a>
                    <ul class="subcategory">
                        <li><a href="{{ route('redeemInfos.index') }}">RedeemInfo Setting</a></li>
                        <li><a href="{{ route('mlsSettings.index') }}">MLS Settings</a></li>
                        <li><a href="{{ route('orderSettings.index') }}">Order Settings</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <main class="py-4" style="padding: 20px; flex: 1;margin-top: 50px;min-height: 100%;position: relative;">
            <div class="mb-5">
                @yield('admin-content')
            </div>
            <footer>
                <div class="container py-3">
                    <p class="m-0">2019 All right reserved &copy;</p>
                </div>
            </footer>
        </main>
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/ckeditor5/ckeditor.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <!-- for emoji-picker -->
    <script src="{{ asset('plugins/emoji-picker/lib/js/config.js') }}"></script>
    <script src="{{ asset('plugins/emoji-picker/lib/js/util.js') }}"></script>
    <script src="{{ asset('plugins/emoji-picker/lib/js/jquery.emojiarea.js') }}"></script>
    <script src="{{ asset('plugins/emoji-picker/lib/js/emoji-picker.js') }}"></script>
    <script src="{{ asset('plugins/multi-select-search/jquery.dropdown.js') }}"></script>
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    @yield('scripts')
</body>
</html>
