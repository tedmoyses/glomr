<html>
  <head>
    <title>Custom site - @yield('title')</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Oswald|Roboto+Condensed" rel="stylesheet">
    @assets($assets)
    <style>
      @yield('pagecss')
    </style>
  </head>
  <body>
    @include('nav')
    <div class="container">
      @yield('content')
    </div>
  </body>
</html>
