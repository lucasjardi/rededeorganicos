@extends('layouts.app')

@section('content')
<div class="container">
    <div class="shadow bg-white p-6 rounded">
      @if (session('message'))
        <h1>{{ session('message') }}</h1>
      @endif
    </div>
</div>
@endsection
