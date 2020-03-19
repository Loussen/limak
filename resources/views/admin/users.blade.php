@extends('layouts.admin')
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
        .copy-input {
            opacity: 0;
            display: flex;
            height: 1px;
            margin: 0;
            padding: 0;
        }
        .card {
            height: 100%;
        }
        .box-header .col-md-2{
            margin-bottom: 10px;
        }
    </style>
    @endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div style="width: 100%;" class="container">
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"><strong>İstifadəçilər</strong></h3>
                                <br>
                            </div>
                            @php($total = $usersCount)
                            <div class="box-header">
                                <div class="row text-center">
                                    <div class="col-md-2 col-sm-6">
                                        <div class="card mb-2 bg-primary text-white">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div><i class="icon-users s-18"></i></div>
                                                    <div><span class="text-danger"></span></div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="s-48 my-3 font-weight-lighter">{{$total}}</div>
                                                    Ümumi istifadəçilərin sayı
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div><i class="icon-male s-18"></i></div>
                                                    <div><span class="text-danger"></span></div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="s-48 my-3 font-weight-lighter">{{$usersGender}}</div>
                                                    Kişi
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div><i class="icon-female s-18"></i></div>
                                                    <div><span class="text-danger"></span></div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="s-48 my-3 font-weight-lighter">{{$total - $usersGender}}</div>
                                                    Qadın
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div><i class="icon-perm_contact_calendar s-18"></i></div>
                                                    <div><span class="text-danger"></span></div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="s-48 my-3 font-weight-lighter">{{$usersMonth}}</div>
                                                    Cari ayda qeydiyyatdan keçənlər
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div><i class="icon-today s-18"></i></div>
                                                    <div><span class="text-danger"></span></div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="s-48 my-3 font-weight-lighter">{{$usersDay}}</div>
                                                    Gün ərzində qeydiyyatdan keçənlər
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <div class="card mb-2 bg-success text-white">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div><i class="icon-money s-18"></i></div>
                                                    <div><span class="text-danger"></span></div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="s-48 my-3 font-weight-lighter">{{$usersBalance}}</div>
                                                    Ümumi istifadəçilərin balansı
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body no-padding">
                                <form onsubmit="submit()" method="GET" action="{{route('users.list')}}">
                                    <table class="table table-striped">
                                    <tbody>
                                    <tr>
                                        <th >#</th>
                                        <th>İstifadəçi nömrəsi</th>
                                        <th>@lang('superadmin.name')</th>
                                        <th>@lang('superadmin.surname')</th>
                                        <th >@lang('superadmin.email')</th>
                                        <th >@lang('superadmin.pin')</th>
                                        <th >@lang('superadmin.balance')</th>
                                        <th >@lang('superadmin.serialNumber')</th>
                                        <th >@lang('superadmin.address')</th>
                                        <th >@lang('superadmin.birthday')</th>
                                        <th>Əməliyyatlar</th>
                                    </tr>
                                    <tr class="search-container">
                                        <th></th>
                                        <th>
                                            <div class="input-group focused">
                                                <input name="uniqid" class="form-control r-0" type="text" placeholder="№" value="{{Illuminate\Support\Facades\Input::get('uniqid')}}">
                                            </div>
                                        </th>
                                        <th>
                                            <div class="input-group focused">
                                                <input name="name" value="{{Illuminate\Support\Facades\Input::get('name')}}" class="form-control r-0" type="text" placeholder="@lang('superadmin.name')">
                                            </div>
                                        </th>
                                        <th>
                                            <div class="input-group focused">
                                                <input name="surname" value="{{Illuminate\Support\Facades\Input::get('surname')}}" class="form-control r-0" type="text" placeholder="@lang('superadmin.surname')">
                                            </div>
                                        </th>
                                        <th>
                                            <div class="input-group focused">
                                                <input name="email" value="{{Illuminate\Support\Facades\Input::get('email')}}" class="form-control r-0" type="text" placeholder="@lang('superadmin.email')">
                                            </div>
                                        </th>
                                        <th>
                                            <div class="input-group focused">
                                                <input name="pin" value="{{Illuminate\Support\Facades\Input::get('pin')}}" class="form-control r-0" type="text" placeholder="@lang('superadmin.pin')">
                                            </div>
                                        </th>
                                        <th>
                                            <div class="input-group focused">
                                                <input name="balance" value="{{Illuminate\Support\Facades\Input::get('balance')}}" class="form-control r-0" type="text" placeholder="@lang('superadmin.balance')">
                                            </div>
                                        </th>
                                        <th>
                                            <div class="input-group focused">
                                                <input name="serial_number" value="{{Illuminate\Support\Facades\Input::get('serial_number')}}" class="form-control r-0" type="text" placeholder="@lang('superadmin.serialNumber')">
                                            </div>
                                        </th>
                                        <th>
                                            <div class="input-group focused">
                                                <input name="address" value="{{Illuminate\Support\Facades\Input::get('address')}}" class="form-control r-0" type="text" placeholder="@lang('superadmin.address')">
                                            </div>
                                        </th>
                                        <th>
                                            <div class="input-group">
                                                <input name="birthdate" type="text" class="date-time-picker form-control"
                                                       data-options='{"timepicker":false, "format":"d.m.Y"}' value="{{Illuminate\Support\Facades\Input::get('birthdate')}}"/>
                                                <span class="input-group-append">
                                                    <span class="input-group-text add-on white">
                                                        <i class="icon-calendar"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </th>
                                        <th></th>
                                    </tr>
                                    @foreach($users as $index => $user)
                                    @php($uniqid = uniqid())
                                    <tr>
                                        <td style="font-weight: bold;">{{$index + 1}})</td>
                                        <td>
                                            <button type="button" class="btn btn--outline-dark" onclick="copyText('user_{{$uniqid}}')" >{{$user->uniqid}}</button>
                                            <input type="text" class="copy-input" id="user_{{$uniqid}}" value="{{$user->uniqid}}">
                                        </td>
                                        <td style="font-weight: bold;"><a href="/admin/user/allData/{{ $user->id }}" target="_blank"> {{$user->name}} </a></td>
                                        <td style="font-weight: bold;">{{$user->surname}}</td>
                                        <td style="font-weight: bold;">
                                            <button type="button" class="btn btn--outline-dark" onclick="copyText('user_email_{{$uniqid}}')" >{{$user->email}}</button>
                                            <input type="text" class="copy-input" id="user_email_{{$uniqid}}" value="{{$user->email}}">
                                        </td>
                                        <td style="font-weight: bold;">{{$user->pin}}</td>
                                        <td><span style="font-size: 14px;" class="badge text-white bolder bg-green">{{$user->balance}} AZN</span>
                                        <td style="font-weight: bold;">{{$user->serial_number}}</td>
                                        <td>{{$user->address}}</td>
                                        <td>{{date('d.m.Y' , strtotime($user->birthdate))}}</td>
                                        <td>
                                            <a href="{{route('users.block' , $user->id)}}" class="btn btn-warning" ><strong>{{$user->is_blocked ? 'Blokdan çıxart' : 'Blokla'}}</strong></a></td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="10">
                                                Nəticə sayı: {{$users->total()}}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                                </form>
                                {{ $users->appends(Illuminate\Support\Facades\Input::except('page'))->links() }}
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
        $('.search-container').find('input').each(function(element) {
            $(this).keyup(function(e){
                if(e.keyCode === 13)
                {
                    $(this).trigger("submit");
                }
            });
        });
        function submit(e) {
        }
        function copyText(id) {
            var copyText = document.getElementById(id);
            copyText.select();
            document.execCommand("copy");
        }
    </script>
@endsection
