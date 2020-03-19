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
            <ol class="breadcrumb" style="">
                <li><a href="/cp"><i class="fa fa-home"></i>Ana səhifə</a></li>
                <li><a href="/cp#/user-management">İstifadəçi tənzimlənmələri</a></li>
                <li class="active">APİ yarat</li>
            </ol>
            <div class="card">
                <div class="card-body b-b">
                    <h4>`API`-lar</h4>
                    <form method="POST"  action="/{{App::getLocale()}}/cp/apis{{!is_null($data)?"/".$id:""}}">
                        @csrf
                        @if(!is_null($data))
                            @method('PUT')
                        @else
                            @method('POST')
                        @endif
                        <div class="form-group">
                            <label for="shopName" class="col-form-label">Url</label>
                            <input required name="name" type="text" class="form-control" id="name" value="{{!is_null($data) ? $data->name : ''}}" placeholder="/shops/create">
                        </div>
                        <div class="form-group">
                            <label for="shopUrl" class="col-form-label">Method</label>
                            <select name="method" id="method" class="form-control">
                                @foreach($methods as $method)
                                    <option {{$data && $method === $data->method ? 'selected' : ''}} value="{{$method}}">{{$method}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="shopName" class="col-form-label">Description</label>
                            <input required name="description" type="text" class="form-control" id="description" value="{{!is_null($data) ? $data->description : ''}}" placeholder="/shops/create">
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