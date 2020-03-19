@extends('layouts/admin')
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
            <a class="btn btn-primary" style="margin-bottom: 20px;" href="{{route('admins.create')}}">Admin əlavə et</a>
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Adminlər</strong></h3>
                                <br>
                            </div>
                            <div class="box-body no-padding">
                                <table class="table table-striped">
                                    <tbody>
                                    <tr>
                                        <th >#</th>
                                        <th>Ad</th>
                                        <th>Soyad</th>
                                        <th>Admin tipi</th>
                                        <th>Ətraflı</th>
                                    </tr>
                                    <tr class="search-container">
                                        <form action="" method="get" id="searchForm">
                                            <input name="search" value="true" type="hidden" class="submitClass widthFull form-control" type="text" />
                                            <th>
                                                <div class="input-group focused">
                                                    <input name="id" value="{{isset($params['id'])?$params['id']:''}}" class="form-control r-0" type="text" placeholder="id">
                                                </div>
                                            </th>
                                            <th>
                                                <div class="input-group focused">
                                                    <input name="name" value="{{isset($params['name'])?$params['name']:''}}" class="form-control r-0" type="text" placeholder="Mahir">
                                                </div>
                                            </th>
                                            <th>
                                                <div class="input-group focused">
                                                    <input name="surname" value="{{isset($params['surname'])?$params['surname']:''}}" class="form-control r-0" type="text" placeholder="Abdullayev">
                                                </div>
                                            </th>
                                            <th>
                                                {{--<div class="input-group focused">
                                                    <select name="method" id="method" class="form-control">
                                                        <option value="">Hamısı</option>
                                                        @foreach($roles as $role)
                                                            <option {{isset($params['role']) && $params['role'] === $role->name ? 'selected' : ''}} value="{{$role->name}}">{{$role->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>--}}
                                            </th>
                                            <th></th>
                                        </form>
                                    </tr>
                                    </form>
                                    @foreach($data as $admin)
                                    <tr>
                                        <td style="font-weight: bold;">{{$admin->id}}</td>
                                        <td style="font-weight: bold;">{{$admin->name}}</td>
                                        <td style="font-weight: bold;">{{$admin->surname}}</td>
                                        <td style="font-weight: bold;">
                                            <?php $roleNames = ''; ?>
                                            @foreach($admin->relAdminRoles as $value)
                                                <?php $roleNames = $roleNames. ', '.$value->relRole->name; ?>
                                            @endforeach
                                            {{rtrim(ltrim($roleNames,", "))}}
                                        </td>
                                        <td>
                                            <a style="width: 40px; height: 38px;" href="{{url('az/admin/admins/'.$admin->id.'/edit')}}" class="btn btn-success btn-xs purple">
                                                <i style="font-size: 17px;padding-top: 5px;" class="far fa-edit"></i>
                                            </a>
                                            {{ Form::open(array('url' => App::getLocale()."/admin/admins/". $admin->id,'style' => 'display:inline')) }}
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
        $searchContainer = $('.search-container');
        $searchContainer.find('input').each(function() {
            $(this).on('keyup', function(e){
                if(e.keyCode === 13)
                {
                    console.log('key press');
                    $('#searchForm').submit();
                }
            });
        });

        $searchContainer.find('select').change(function () {
            $('#searchForm').submit();
        })
    </script>
@endsection
