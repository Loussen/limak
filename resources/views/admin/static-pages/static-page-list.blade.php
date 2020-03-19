@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="{{asset('js/ckeditor_4.10.1_standard_easyimage/ckeditor/contents.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
@endsection
@section('content')
    <section id="app-static-pages">
        <router-view></router-view>
    </section>
@endsection
@section('scripts')
    <script type="application/javascript" src="{{asset('js/ckeditor_4.10.1_standard_easyimage/ckeditor/ckeditor.js')}}" ></script>
    <script type="application/javascript" src="{{asset('js/ckeditor_4.10.1_standard_easyimage/ckeditor/styles.js')}}" ></script>
    <script type="application/javascript" src="{{asset('js/vue/static-pages/app.js')}}" ></script>
@endsection
