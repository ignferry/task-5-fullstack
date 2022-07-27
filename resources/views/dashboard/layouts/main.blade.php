@extends('layouts.app')

@section('head')
    <link href="/css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/trix.css">
    <style>
        trix-toolbar [data-trix-button-group="file-tools"] {
          display: none
        }
    </style>
@endsection

@section('content')    
    <div class="container-fluid">
        <div class="row">
            @include('dashboard.layouts.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('dashboard-content')
            </main>
        </div>
    </div>

    <script type="text/javascript" src="/js/trix.js"></script>
@endsection