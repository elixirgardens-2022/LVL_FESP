<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title')</title>

<link  rel="stylesheet" href="http://fonts.googleapis.com/css?family=Nunito">

<script src="/js/jquery-1.12.4.min.js"></script>

<link  rel="stylesheet" href="/css/style.css">

</head>
<body>
    
<header>
    <!-- Pagination show/hide needs to be conditional -->
    @include('includes/pagination')
    @yield('header_extras')
</header>

<div class="h40"></div>

@yield('content')

<div class="h40"></div>

</body>
</html>