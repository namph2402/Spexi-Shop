<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="{{ $data['Name'] }}" name="keywords">
    <meta content="{{ $data['Name'] }}" name="description">
    <title>{{ $data['Name'] }} -
        @yield('title')
    </title>
    <link href="{{ asset('assets/img/private/favicon.ico') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>
    @if (session('msg_success'))
        <div class="alert alert-success alert-dismissible fade show toast-msg">
            <strong>Thành công!</strong> {{ session('msg_success') }}
        </div>
    @endif
    @if (session('msg_error'))
        <div class="alert alert-danger alert-dismissible fade show toast-msg">
            <strong>Thất bại!</strong> {{ session('msg_error') }}
        </div>
    @endif
    @include('components.header')
    @yield('content')
    @include('components.footer')
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/checkout.js') }}"></script>
    @yield('scripts')
</body>

</html>
