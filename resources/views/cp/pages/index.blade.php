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
    <h4>SƏHİFƏLƏR</h4>
    <ol class="breadcrumb">
        <li><a href="/cp"><i class="fa fa-home"></i>Ana səhifə</a></li>
        <li class="active">Səhifələr</li>
    </ol>
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div style="width: 100%;" class="container">
            <a class="btn btn-effect" style="margin-bottom: 20px;" href="{{route('pages.create')}}">Səhifə əlavə et</a>
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Səhifələr</strong></h3>
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
                                        <form id="searchForm" action="{{route('pages.index')}}" method="get">
                                            <tr>
                                                <th >#</th>
                                                <th >id</th>
                                                <th>Səhifənin adı</th>
                                                <th>Şəkil</th>
                                                <th style="min-width: 200px;">Əlavə edilmə tarixi</th>
                                                <th>Redaktə</th>
                                            </tr>
                                            <tr class="search-container">
                                                <th></th>
                                                <input name="search" value="true" type="hidden" class="submitClass widthFull form-control" type="text" />
                                                <th>
                                                    <div class="input-group focused">
                                                        <input name="id" value="{{isset($params['id'])?$params['id']:''}}" class="form-control r-0" type="text" placeholder="id">
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="input-group focused">
                                                        <input name="name" value="{{isset($params['name'])?$params['name']:''}}" class="form-control r-0" type="text" placeholder="ad">
                                                    </div>
                                                </th>
                                                <th>
                                                </th>
                                                <th style="min-width: 200px;">
                                                    <div class="input-group">
                                                        <input name="created_at" value="{{isset($params['created_at'])?$params['created_at']:''}}" type="text" class="date-time-picker form-control"
                                                               data-options='{"timepicker":false, "format":"d-m-Y"}' />
                                                        <span class="input-group-append">
                                                        <span class="input-group-text add-on white">
                                                            <i class="icon-calendar"></i>
                                                        </span>
                                                    </span>
                                                    </div>
                                                </th>
                                                <th></th>
                                            </tr>
                                        </form>
                                        @php($startCount = count($data) > 0?($data->currentPage() - 1) * $data->perPage(): 0)
                                        @foreach($data as $key => $value)
                                            <tr>
                                                <td style="font-weight: bold;">{{++$startCount}}.</td>
                                                <td style="font-weight: bold;">{{$value->id}}</td>
                                                <td style="font-weight: bold;">{{$value->translates[0]->name}}</td>
                                                <td style="font-weight: bold;"><img width="80px" src="{{url(Storage::url($value->file))}}"/></td>
                                                <td style="text-align: center;">{{date('Y-m-d', strtotime($value->created_at))}}</td>
                                                <td style="min-width: 200px;">
                                                    <a style="width: 40px; height: 38px;" href="{{url('az/cp/pages/'.$value->id.'/edit')}}" class="btn btn-success btn-xs purple">
                                                        <i style="font-size: 17px;padding-top: 5px;" class="far fa-edit"></i>
                                                    </a>
                                                    {{ Form::open(array('url' => App::getLocale()."/admin/pages/". $value->id,'style' => 'display:inline')) }}
                                                    {{ Form::hidden('_method', 'DELETE') }}
                                                    {{ Form::macro('myField', function() { return '<button class="btn btn-danger" type="submit"><i class="far fa-trash-alt"></i></button>';})}}
                                                    <?php echo Form::myField() ?>
                                                    {{ Form::close() }}
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
