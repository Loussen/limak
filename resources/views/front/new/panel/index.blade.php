@extends('layouts.front_main')
@section('styles')

@endsection
@section('content')
    <div id="user-panel">
        <router-view></router-view>
    </div>
@endsection

@section('scripts')
    <script>
        window.default_locale = "{{ config('app.locale') }}";
        window.fallback_locale = "{{ config('app.fallback_locale') }}";
        window.messages = @json($message);
    </script>
    <script type="text/javascript" src="{{asset('js/vue/new-user-panel/app.js?v=').time()}}"></script>
@endsection
