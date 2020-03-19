@extends('layouts/admin')
@section('styles')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">

    <link type="text/css" rel="stylesheet" href="{{asset('admin/js/image_cropper/newCrop.css')}}" />
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
            <form method="POST"  action="/{{App::getLocale()}}/admin/roles{{!is_null($data)?"/".$id:""}}" enctype="multipart/form-data">
                @csrf
                @if(!is_null($data))
                    @method('PUT')
                @else
                    @method('POST')
                @endif
                <div class="card">
                    <div class="card-body b-b">
                        <h4>Admin tipləri</h4>
                        <div class="form-group">
                            <label for="shopName" class="col-form-label">Admin tipinin adı</label>
                            <input required name="name" type="text" class="form-control" id="shopName" value="{{!is_null($data) ? $data->name : ''}}" placeholder="Trendyol">
                        </div>
                        <div>
                            <div class="col-lg-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title"><strong>İcazələrin siyahısı</strong></h3>
                                        <br>
                                    </div>
                                    <div class="box-body no-padding">
                                            <table id="searchForm" class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Seçin</th>
                                                    <th>Adı</th>
                                                    <th>Url növü</th>
                                                    <th>Açıqlama</th>
                                                </tr>
                                                <tr class="search-container">
                                                    <th>
                                                    </th>
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input value="" class=" searchInput form-control r-0" type="text" placeholder="url">
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input value="" class="searchInput form-control r-0" type="text" placeholder="tip">
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input value="" class="searchInput form-control r-0" type="text" placeholder="açıqlama">
                                                        </div>
                                                    </th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($apis as $key => $value)
                                                    <tr>
                                                        <td style="font-weight: bold;">
                                                            <div class="material-switch">
                                                                @if(isset($value->relRolesApis) && isset($value->relRolesApis[0]))
                                                                    <input id="someSwitchOptionSucce{{$value->id}}ss" name="apisUpdate[{{ isset($value->relRolesApis) && isset($value->relRolesApis[0])?$value->id:''}}]" {{ isset($value->relRolesApis) && isset($value->relRolesApis[0])?'checked':''}} value="{{$value->id}}" type="checkbox">
                                                                    @else
                                                                    <input id="someSwitchOptionSucce{{$value->id}}ss" name="apis[]" value="{{$value->id}}" type="checkbox">
                                                                    @endif

                                                                <label for="someSwitchOptionSucce{{$value->id}}ss" class="bg-success"></label>
                                                            </div>
                                                        </td>
                                                        <td class="searchable" style="font-weight: bold;">{{$value->name}}</td>
                                                        <td class="searchable" style="font-weight: bold;">{{$value->method}}</td>
                                                        <td class="searchable">{{$value->description}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Təsdiqlə</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $(".searchInput").on("keyup", function() {
                console.log('key up');
                var values = [];
                $(".searchInput").each(function() {
                    values.push($(this).val().toLowerCase());
                });
                console.log("values");
                console.log(values);
                $("#searchForm tbody tr").filter(function() {
                    var found = true;
                    var increment = 0;
                    $(this).find('.searchable').each(function() {
                        console.log('tds');
                        if($(this).text().toLowerCase().indexOf(values[increment]) === -1) {
                            found = false;
                        }
                        increment++;
                    });
                    console.log(found);
                    if(found) {
                        $(this).css('display', 'table-row');
                    } else {
                        $(this).css('display', 'none');
                    }
                });
            });
        });
    </script>
@endsection
