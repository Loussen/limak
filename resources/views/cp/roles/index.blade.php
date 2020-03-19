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
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div style="width: 100%;" class="container">
            <a class="btn btn-primary" style="margin-bottom: 20px;" href="{{route('roles.create')}}">Rol əlavə et</a>
            <ol class="breadcrumb" style="width: 400px;float: right;">
                <li><a href="/cp"><i class="fa fa-home"></i>Ana səhifə</a></li>
                <li><a href="/cp#/user-management">İstifadəçi tənzimlənmələri</a></li>
                <li class="active">Admin tipləri</li>
            </ol>
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Rollar</strong></h3>
                                <br>
                            </div>
                            <div class="box-body no-padding">

                                    <table class="table table-striped">
                                        <tbody>
                                        <form action="" method="get" id="searchForm">
                                            <input name="search" value="true" type="hidden" class="submitClass widthFull form-control" type="text" />
                                            <tr>
                                                <th >#</th>
                                                <th>Id</th>
                                                <th>Adı</th>
                                                <th>Ətraflı</th>
                                            </tr>
                                            <tr class="search-container">
                                                <th></th>
                                                <th>
                                                    <div class="input-group focused">
                                                        <input name="id" value="{{isset($params['id'])?$params['id']:''}}" class="form-control r-0" type="text" placeholder="id">
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="input-group focused">
                                                        <input name="name" value="{{isset($params['name'])?$params['name']:''}}" class="form-control r-0" type="text" placeholder="Mağazanın adı">
                                                    </div>
                                                </th>
                                                <th></th>
                                            </tr>
                                        </form>
                                        @php($startCount = count($data) > 0?($data->currentPage() - 1) * $data->perPage(): 0)
                                        @foreach($data as $shop)
                                        <tr>
                                            <td style="font-weight: bold;">{{++$startCount}}.</td>
                                            <td style="font-weight: bold;">{{$shop->id}}</td>
                                            <td style="font-weight: bold;">{{$shop->name}}</td>
                                            <td>
                                                <a style="width: 40px; height: 38px;" href="{{url('az/cp/roles/'.$shop->id.'/edit')}}" class="btn btn-success btn-xs purple">
                                                    <i style="font-size: 17px;padding-top: 5px;" class="far fa-edit"></i>
                                                </a>
                                                {{ Form::open(array('url' => App::getLocale()."/cp/roles/". $shop->id,'style' => 'display:inline')) }}
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
                                {{ $data->appends($params)->links() }}
                            </div>
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
