@extends('layouts.front')

@section('content')
<section class="page-block question">
    <div class="container">
        <div class="page-head">
            <img src="{{asset('front/img/question-icon.png')}}" alt="question">
            <h1>@lang('common.faq')</h1>
        </div>
        <div class="block">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @for($i = 0; $i < ceil(count($questions) / 2); $i++)
                            <div class="panel panel-default">
                                <div class="panel-heading" >
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion">
                                            {{$questions[$i]->question}}
                                        </a>
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse">
                                    <div class="panel-body">
                                        {{$questions[$i]->answer}}
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @for($k = $i; $k < count($questions); $k++)
                            <div class="panel panel-default">
                                <div class="panel-heading" >
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion">
                                            {{$questions[$k]->question}}
                                        </a>
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse">
                                    <div class="panel-body">
                                        {{$questions[$k]->answer}}
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection