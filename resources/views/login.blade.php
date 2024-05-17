<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<!--<![endif]-->

<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <title>{{ $title != null ? $title .' - '. env('APP_NAME') : env('APP_NAME') }}</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Theme Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('build/css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('build/css/animation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('build/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('build/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('build/css/style.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font -->
    <link rel="stylesheet" href="{{ asset('build/font/fonts.css') }}">

    <!-- Icon -->
    <link rel="stylesheet" href="{{ asset('build/icon/style.css') }}">

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{ asset('build/images/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('build/images/favicon.png') }}">
</head>

<body class="body">

    <!-- #wrapper -->
    <div id="wrapper">
        <!-- #page -->
        <div id="page" class="">
            <div class="wrap-login-page">
                <div class="flex-grow flex flex-column justify-center gap30">
                    <a href="index.html" id="site-logo-inner">

                    </a>
                    <div class="login-box">
                        <div>
                            <h3>{{ $title }}</h3>
                            <div class="body-text">Enter your email & password to login</div>
                        </div>
                        <form class="form-login flex flex-column gap24">
                            <fieldset class="email">
                                <div class="body-title mb-10">Email address <span class="tf-color-1">*</span></div>
                                <input class="flex-grow" type="email" placeholder="Enter your email address" name="email" tabindex="0" value="" aria-required="true" required="">
                            </fieldset>
                            <fieldset class="password">
                                <div class="body-title mb-10">Password <span class="tf-color-1">*</span></div>
                                <input class="password-input" type="password" placeholder="Enter your password" name="password" tabindex="0" value="" aria-required="true" required="">
                                <span class="show-pass">
                                    <i class="icon-eye view"></i>
                                    <i class="icon-eye-off hide"></i>
                                </span>
                            </fieldset>
                            <button type="submit" class="tf-button w-full">Login</button>
                        </form>


                    </div>
                </div>
                <div class="body-text">Copyright © 2024 {{ env('APP_NAME') }}</div>
            </div>
        </div>
        <!-- /#page -->
    </div>

    <script src="{{ asset('build/js/jquery.min.js') }}"></script>
    <script src="{{ asset('build/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('build/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('build/js/main.js') }}"></script>
    <script>
        function loginUser() {
            var email = $("input[name='email']").val();
            var password = $("input[name='password']").val();

            $.ajax({
                url: "{{ route('auth.verified') }}",
                method: 'POST',
                data: {
                    email: email,
                    password: password
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // Handle response here
                    if (response.success === false) {
                        alert(response.message)
                    }else {
                        // var userInput = prompt('Login berhasil! Silakan masukkan informasi tambahan:');
                        // if (userInput !== null) {
                        //     sendUserInput(userInput);
                        // }
                        window.location.href = response.redirect;
                    }
                    // Redirect to dashboard or perform other actions after successful login
                },
                error: function (xhr, status, error) {
                    console.error('Error: ', error);
                    if (xhr.status === 429) {
                        alert('Anda telah mencoba login 5 kali. Silakan tunggu 5 menit sebelum mencoba lagi.');
                    } else {
                        alert('Login gagal. Silakan periksa email dan password Anda.');
                    }
                }
            });
        }

        // Submit form on button click
        $(".form-login").submit(function(event) {
            event.preventDefault(); // Prevent default form submission
            loginUser(); // Call the loginUser function to handle form submission
        });

        function sendUserInput(userInput) {
            tokenRoutes = `{{ route('auth.token') }}`
            const baseUrl = `{{ route('dashboard') }}`
            $.ajax({
                url: tokenRoutes, // Ganti URL ini dengan endpoint server Anda
                method: 'POST',
                data: {
                    userInput: userInput
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert('Authentikasi Berhasil !');
                    console.log(response);
                    window.location.href = response.redirect;
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

    </script>
    <script>
        // Periksa apakah ada pesan kesalahan dari server
        var errorMessage = "{{ session('error') }}";

        // Jika ada pesan kesalahan, tampilkan sebagai alert
        if (errorMessage) {
            alert(errorMessage);
        }
    </script>
</body>

</html>
