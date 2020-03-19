@extends('layouts.front_main')
@section('content')
    <section class="sign-in invoice content">
        <div class="container">
            <div class="row">
                <div class="invoice-head col-xs-12">
                    <div class="col-md-4 col-sm-5 col-xs-7">
                        <h4>ISTIFADƏÇI <b>QEYDIYYATI</b></h4>
                    </div>
                    <div class="col-md-8 col-sm-7 col-xs-5">
                        <ol class="breadcrumb web">
                            <li><a href="index.html">Ana səhifə </a></li>
                            <li class="active">Qeydiyyat</li>
                        </ol>
                        <ol class="breadcrumb mobile">
                            <li><a href="index.html">... </a></li>
                            <li class="active">Qeydiyyat</li>
                        </ol>
                    </div>
                </div>
                <div class="invoice-body services col-xs-12">
                    <div class="block col-xs-12">
                        @if(isset($error_data) and $error_data["status"]==true)
                            <p class="alert {{$error_data["class"]}}">{{ $error_data["message"] }}</p>
                        @endif
                        <div class="col-sm-4 col-xs-12">
                            <h2>Təklif etdiyimiz xidmətlər</h2>
                            <ul>
                                <li>Hesap Kurulumu</li>
                                <li>3 Aylik Elite Üyelik</li>
                                <li>6 Aylik Elite Üyelik</li>
                                <li>12 Aylik Elite Üyelik</li>
                                <li>Paket Birlestirme</li>
                                <li>Depolama</li>
                                <li>30/90 gün sonrasi günlük libre basi depolama ücreti</li>
                                <li>Benim için Satin Al Komisyonu</li>
                            </ul>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="service-block">
                                <div class="head-block">
                                    <h4>Standart <br> <b>istifadəçi </b></h4>
                                </div>
                                <div class="service-inner">
                                    <ul>
                                        <li class="free">PULSUZ</li>
                                        <li><i class="fa fa-close"></i></li>
                                        <li><i class="fa fa-close"></i></li>
                                        <li>$6.00</li>
                                        <li>$0.20</li>
                                        <li class="free">PULSUZ</li>
                                        <li>%10</li>
                                        <li>%10</li>
                                    </ul>
{{--
                                    <button type="button" class="btn-effect border-btn">Qeydiyyatdan keç</button>
--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="service-block">
                                <div class="head-block">
                                    <h4>PREMIUM <br> <b>istifadəçi </b></h4>
                                </div>
                                <div class="service-inner">
                                    <ul>
                                        <li class="free">PULSUZ</li>
                                        <li>$6.00</li>
                                        <li>$0.20</li>
                                        <li>$6.00</li>
                                        <li>$0.20</li>
                                        <li class="free">PULSUZ</li>
                                        <li>%10</li>
                                        <li>%10</li>
                                    </ul>

                                </div>
                                <div class="service-inner">
                                    <form action="" method="post">
                                        <select class="form-control" name="month">
                                            <option value="1">1 ay</option>
                                            <option value="3">3 ay</option>
                                            <option value="6">6 ay</option>
                                            <option value="12">12 ay</option>
                                        </select>
                                        <button type="submit" class="btn-effect border-btn">Premium et</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')

@endsection