<!doctype html>
<html lang="en">
<head>
  <title>Bootstrap site - @yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta charset="utf-8">
  @style(https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css)

</head>
<body>
  @include('nav')
  <div class="container">
    @yield('content')
  </div>
  @script(https://code.jquery.com/jquery-3.2.1.slim.min.js)
  @script(https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js)
  @script(https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js)
</body>
</html>
