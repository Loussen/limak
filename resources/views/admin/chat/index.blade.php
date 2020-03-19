@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endsection
@section('content')
    <section id="app-chat">
        <router-view></router-view>
    </section>
@endsection
@section('scripts')
    <script type="application/javascript" src="{{asset('js/vue/chat/app.js')}}" ></script>
@endsection
