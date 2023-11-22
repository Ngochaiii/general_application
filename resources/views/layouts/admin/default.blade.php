<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nhựa Tiền Phong</title>
    @include('layouts.admin.default.header_css')
    @stack('hcss')
    @stack('hjs')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('layouts.admin.default.header')
        @include('layouts.admin.default.nav')
        @include('layouts.admin.default.sidebar')
        <div class="content-wrapper">
            @include('layouts.admin.default.breakcrum')
            @yield('content')
        </div>
    </div>
    @include('layouts.admin.default.footer')
    @include('layouts.admin.default.footer_js')
    @stack('fcss')
    @stack('fjs')
</body>

</html>
