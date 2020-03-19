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
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="/cp"><i class="fa fa-home"></i>Ana səhifə</a></li>
                <li><a href="/cp#/user-management">İstifadəçi tənzimlənmələri</a></li>
                <li class="active">APİ-lər</li>
            </ol>
            <div class="card">
                <div class="card-body b-b">
                    <h4>Admin</h4>
                    <form method="POST"  action="/{{App::getLocale()}}/admin/admins{{!is_null($data)?"/".$id:""}}">
                        @csrf
                        @if(!is_null($data))
                            @method('PUT')
                        @else
                            @method('POST')
                        @endif
                        <div class="form-group">
                            <label for="name" class="col-form-label">Ad</label>
                            <input required name="name" type="text" class="form-control" id="name" value="{{!is_null($data) ? $data->name : ''}}" placeholder="Mahir">
                        </div>
                        <div class="form-group">
                            <label for="surname" class="col-form-label">Soyad</label>
                            <input required name="surname" type="text" class="form-control" id="surname" value="{{!is_null($data) ? $data->surname : ''}}" placeholder="Abdullayev">
                        </div>
                        <div class="form-group">
                            <label for="username" class="col-form-label">İstifadəçi adı</label>
                            <input required name="username" type="text" class="form-control" id="username" value="{{!is_null($data) ? $data->username : ''}}" placeholder="mahir_94">
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-form-label">Şifrə</label>
                            <input name="password" type="text" class="form-control" id="password" value="" placeholder="*****">
                            <div style="text-align: right; margin-top: 10px;">
                                <button class="btn btn-primary btn-sm" type="button" id="password-generator">Şifrə generasiya et</button>
                            </div>
                        </div>
                        <div>
                            <div class="col-lg-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title"><strong>Admin Tiplərinin siyahısı</strong></h3>
                                        <br>
                                    </div>
                                    <div class="box-body no-padding">
                                        <div class="form">
                                            <input name="search" value="true" type="hidden" class="submitClass widthFull form-control" type="text" />
                                            <table id="searchForm" class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Seçin</th>
                                                    <th>Adı</th>
                                                </tr>
                                                <tr class="search-container">
                                                    <th></th>
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input name="roleName" value="" class="searchInput form-control r-0" type="text" placeholder="Admin">
                                                        </div>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($roles as $key => $value)
                                                    <tr>
                                                        <td style="font-weight: bold;">
                                                            <div class="material-switch">
                                                                <input value="{{$value->id}}" {{isset($selectedRoles ) && in_array($value->id, $selectedRoles) ? 'checked' : ''}} id="someSwitchOptionSucce{{$value->id}}ss" name="roles[]" type="checkbox">
                                                                <label for="someSwitchOptionSucce{{$value->id}}ss" class="bg-success"></label>
                                                            </div>
                                                        </td>
                                                        <td style="font-weight: bold;">{{$value->name}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Təsdiqlə</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $("#password-generator").click(function () {
            $password = generatePassword();
            $("#password").val($password);
        })

        function generatePassword() {
            var length = 8,
                charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
                retVal = "";
            for (var i = 0, n = charset.length; i < length; ++i) {
                retVal += charset.charAt(Math.floor(Math.random() * n));
            }
            return retVal;
        }
    </script>
@endsection