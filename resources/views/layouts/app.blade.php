<?php session_start(); ?>	
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<!-- Refresh page each 3 hours -->
	<meta http-equiv="refresh" content="10800">

    <title>{{ config('app.name', 'Laravel') }}</title>

	<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

	<link rel="icon" href="{{ asset('img/icone.ico') }}" type="image/x-icon" />

	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

	<link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">

	<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
	
	<link rel="stylesheet" href="{{ asset('css/bootstrap-select.css') }}" />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css">
</head>
<body>

	<div id="app">
		@include('layouts.nav')

		<section class="main">
			@yield('content')
		</section>
	</div>

	@include('components.modals')

	<div id="loading-general">
        <h1>Carregando...</h1>
    </div>

	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="{{asset('js/axios.min.js')}}"></script>
	<script src="{{asset('js/vue.min.js')}}"></script>
	<script src="{{asset('js/sweetalert.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/scriptsvue.js')}}"></script>
	<script src="{{asset('js/jquery.min.js')}}" ></script>
	<script src="{{asset('js/jquery-ui.min.js')}}"></script> 
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('js/jquery.mask.min.js')}}"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="{{asset('js/app.js')}}"></script>
</body>
</html>