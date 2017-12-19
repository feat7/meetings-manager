<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Remainder') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/remainder.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/moment.min.js"></script>
    <script type="text/javascript" src="/js/datetime.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>

    <style>
      .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
      }

      @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
      }

      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
    </style>
</head>
<body>
    <div id="app">
        <div class="navbar navbar-default navbar-static-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-ex-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#"><span>Meetings Manager</span></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-ex-collapse">
              <ul class="nav navbar-nav navbar-right">
                <li class="active">
                  <a href="/home">Home</a>
                </li>
                <li>
                    <a href="{{ url('/logout') }}" 
                         onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                          Logout
                    </a>
                     <form id="logout-form" 
                            action="{{ url('/logout') }}" 
                        method="POST" 
                        style="display: none;">
                                    {{ csrf_field() }}
                      </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
        
        
        @yield('content')

        <footer class="section section-primary">
          <div class="container">
            <div class="row">
              <div class="col-sm-6">
                <h1>Meetings Manager</h1>
                <p>This website is brought to you by Vinay Khobragade</p>
              </div>
              <div class="col-sm-6">
                <p class="text-info text-right">
                  <br>
                  <br>
                </p>
                <div class="row">
                  <div class="col-md-12 hidden-lg hidden-md hidden-sm text-left">
                    <a href="http://linkedin.com/in/vinay-khobragade" style="color: #fff">Linked In</a>
                    <a href="http://fb.me/vinay.khobragade.37" style="color: #fff">Facebook</a>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 hidden-xs text-right">
                    <a href="http://linkedin.com/in/vinay-khobragade" style="color: #fff">Linked In</a>
                    <a href="http://fb.me/vinay.khobragade.37" style="color: #fff">Facebook</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </footer>
    </div>

    <!-- Scripts -->
</body>
</html>
