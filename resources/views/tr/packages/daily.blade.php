@extends('tr.layouts.admin_anbar_tr')

@section('header')
    <header>
        <nav class="navbar navbar-default navbar-fixed-top custom">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="{{url('/')}}"><img src="{{asset('/admin/img/logo_black2.png')}}" alt="logo" class="img-responsive"></a>
                        </div>
                    </div>
                    <div class="col-md-10 text-center middle content">
                        <form action="{{url('daily_data')}}" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input name="data_file" type="file" class="form-control" placeholder="File">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input name="day" type="date" class="form-control" placeholder="tarih">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <input class="btn-effect" type="submit" name="upload_file" value="Upload">
                                </div>
                                <div class="col-md-1">
                                    <a href="{{url('logout')}}" class="btn-effect" style="background: red;padding: 10px"><i class="fa fa-sign-out"></i> Çıxış</a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

@endsection

@section('content')
<div class="content">
    <div class="clearfix"></div>
    <div class="custom-container">

        <div class="right_buttons_container" >
            <ul class="right_buttons">
                    <li>
                        <form action="{{url('invoices/change-all-stauses')}}" method="post">
                            <input type="file" name="file" required>
                            <input type="date" name="day" value="">
                            <input type="submit" class="btn-effect blue_inner" name="submit_file" value="Ilave et">
                        </form>
                    </li>

            </ul>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="custom-container table_data">
        <div class="row">
            <div class="inform col-md-12 col-sm-12 col-xs-12">
                <div class="block table-block">
                    <table class="table table-responsive table-striped anbar">
                        @php($count=1)
                        <thead>
                        <tr>
                            <th></th>
                            <th>İD</th>
                            <th>File</th>
                            <th>Gün</th>
                            <th>İlave tarihi</th>
                            <th></th>

                        </tr>
                        </thead>
                        <tbody class="anbar_body">
                        @if(count($data) === 0)
                        <tr >
                            <td colspan="13" style="font-size: 22px;font-weight: bold;text-align: center;"
                                class="text-center">No Invoice
                            </td>
                        </tr>
                        @endif
                        @foreach($data as $value)
                        <tr>
                        <tr>
                            <td>{{$count++}}</td>
                            <td>
                                {{$value->id}}
                            </td>
                            <td>
                                <a href="https://limak.az{{$value->name}}" target="_blank">
                                Fayla Bak
                                </a>
                            </td>
                            <td>
                                {{date("d-m-Y",strtotime($value->day))}}
                            </td>
                            <td>
                                {{$value->created_at}}
                            </td>
                            {{--<td class="align-middle">
                                <button type="submit" class="btn btn-danger deleteDaily" data-item-id="{{$value->id}}">Sil</button>
                            </td>--}}
                        </tr>
                        @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>`
    </div>
</div>


@endsection

@section('scripts')

@endsection
