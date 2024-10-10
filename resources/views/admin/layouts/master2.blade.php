<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content=" الغرف التجارية  ">
    <meta name="Keywords" content=""/>
    @include('admin.layouts.head')
</head>

<body class="main-body bg-primary-transparent">
<!-- Loader -->
<div id="global-loader">
    <img src="{{URL::asset('assets/admin/img/loader.svg')}}" class="loader-img" alt="Loader">
</div>
<!-- /Loader -->
@yield('content')
@include('admin.layouts.footer-scripts')
</body>
</html>
