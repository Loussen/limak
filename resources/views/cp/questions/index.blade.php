@extends('cp.layouts.admin')
@section('styles')
    <style>
        @media (min-width: 1200px) {
            .container {
                max-width: 100% !important;
            }
        }
        .box-body tr td {
            text-align: left;
        }
        .table tr th {
            text-align: left;
        }
    </style>
@endsection
@section('breadcrumb')
    <h4>SUALLAR</h4>
    <ol class="breadcrumb">
        <li><a href="/cp"><i class="fa fa-home"></i>Ana səhifə</a></li>
        <li class="active">Suallar</li>
    </ol>
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div style="width: 100%;" class="container">
            <a class="btn btn-primary" style="margin-bottom: 20px;" href="{{route('questions.create')}}">Sual əlavə et</a>
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Suallar</strong></h3>
                                <br>
                                @if(Session::has('message_error'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message_error') }}</p><br>
                                @endif
                                @if(Session::has('message_success'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message_success') }}</p><br>
                                @endif
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body no-padding">

                                    <table class="table table-striped">
                                        <tbody>
                                        <form id="searchForm" action="{{route('questions.index')}}" method="get">
                                            <tr>
                                                <th >#</th>
                                                <th >id</th>
                                                <th>Sual</th>
                                                <th>Cavab</th>
                                                <th>Step</th>
                                                <th style="min-width: 200px;">Əlavə edilmə tarixi</th>
                                                <th>Redaktə</th>
                                            </tr>
                                        </form>
                                        @php($startCount = count($data) > 0?($data->currentPage() - 1) * $data->perPage(): 0)
                                        @foreach($data as $key => $value)
                                            <tr>
                                                <td style="font-weight: bold;">{{++$startCount}}.</td>
                                                <td style="font-weight: bold;">{{$value->id}}</td>
                                                <td style="font-weight: bold;">{{$value->translates[0]->value}}</td>
                                                <td style="font-weight: bold;">{!! $value->translates[0]->answer !!}</td>
                                                <td style="font-weight: bold;">{!! $value->step !!}</td>
                                                <td style="text-align: center;">{{date('Y-m-d', strtotime($value->created_at))}}</td>
                                                <td style="min-width: 200px;">
                                                    <a style="width: 40px; height: 38px;" href="{{url('az/cp/questions/'.$value->id.'/edit')}}" class="btn btn-success btn-xs purple">
                                                        <i style="font-size: 17px;padding-top: 5px;" class="far fa-edit"></i>
                                                    </a>
                                                    <form style="float: right" action="{{route('questions.destroy',['id'=>$value->id])}}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button style="width: 40px; height: 38px;" type="submit" class="btn btn-danger btn-xs red">
                                                            <i style="font-size: 17px;padding-top: 5px;" class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                            </div>
                            <div class="col-md-12">
                                {{ $data->appends(isset($params)?$params:[])->links() }}
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.search-container').find('input').each(function() {
            $(this).on('keyup', function(e){
                if(e.keyCode === 13)
                {
                    console.log('key press');
                    $('#searchForm').submit();
                }
            });
        });
    </script>
@endsection
