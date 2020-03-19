@extends('layouts.front')

@section('content')
    <style>
        .my-3{
            margin-top: 2rem!important;
            margin-bottom: 1rem!important;
        }
    </style>
    <section class="page-block count elaqe">
        <div class="container">
            <div class="page-head text-center">
                @if(!empty($staticPage->thumnail))
                <img width="100%" src="{{asset(Storage::url($staticPage->thumnail))}}" alt="Limark {{$staticPageTranslate->name}}">
                @endif
                <h1 class="text-center my-3">{{$staticPageTranslate->name}}</h1>
                <p>{{$staticPageTranslate->description}}</p>
            </div>

            @foreach($pageContent as $component)
            <div class="row my-3">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="block">
                        @if($component->type === 'media')
                            @include('front.static_pages.media', ['component' => $component])
                        @elseif($component->type === 'text')
                            @include('front.static_pages.text', ['component' => $component])
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
@endsection
