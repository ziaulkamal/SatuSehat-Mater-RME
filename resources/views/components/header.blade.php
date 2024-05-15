<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<!--<![endif]-->

<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <title>{{ $title ?? env('APP_NAME') }}</title>

    <meta name="author" content="themesflat.com">

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
    @yield('headerScript')
</head>

<body class="body">
    <div id="wrapper">
        <div id="page" class="">
           <div class="layout-wrap">

                <div class="section-menu-left">
                    @include('components.logo')
                    @include('components.sidenav')
                </div>

                <div class="section-content-right">
                    @include('components.topnav')
<div class="main-content">
