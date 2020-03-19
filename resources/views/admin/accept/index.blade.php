@extends('layouts/admin')
@section('styles')
    <style type="text/css">
        @media (min-width: 1200px) {
            .container {
                max-width: 100% !important;
            }
        }
    </style>
@endsection
@section('content')
    <div id="accept-container">
        <router-view></router-view>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset('js/vue/accept/app.js?v=').time()}}"></script>
@endsection
