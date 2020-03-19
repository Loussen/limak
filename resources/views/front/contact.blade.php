@extends('layouts.front')

@section('content')
    <section class="page-block count elaqe">
        <div class="container">
            <div class="page-head">
                <img src="{{asset('front/img/olkeler-page.png')}}" alt="kalkulyator">
                <h1>@lang('common.contact')</h1>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="block">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        <form action="" method="POST">
                            <div class="row">
                                <div class="input-group">
                                    <label for="name"></label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="@lang('common.name_surname')">
                                </div>
                                <div class="input-group">
                                    <label for="email1"></label>
                                    <input type="email" name="email" class="form-control" id="email1" placeholder="@lang('common.mail')">
                                </div>
                                <div class="input-group">
                                    <label for="telefon"></label>
                                    <input type="text" name="phone" class="form-control" id="@lang('common.phone')"
                                           placeholder="Telefon">
                                </div>
                                <div class="input-group">
                                    <label for="comment"></label>
                                    <textarea class="form-control" rows="5" name="description" id="comment" placeholder="@lang('common.text')"></textarea>
                                </div>
                                <div class="count-end">
                                    <button class="btn-effect">@lang('common.send')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="right-side col-md-6 col-sm-12 col-xs-12">
                    <div class="block">
                        <div class="block map-contact">
                            <ul class="contact">
                                <li><a href="tel:*9595"><i
                                                class="fa fa-phone"></i><span>*9595</span><br>
                                        </a>
                                </li>
                                <li><a href="tel:+994125058797"><i
                                                class="fa fa-phone"></i><span>+994125058797</span><br>
                                        </a>
                                </li>
                                <li><a href="tel:+9948249595"><i
                                                class="fa fa-whatsapp"></i><span>*9948249595</span><br>
                                        </a>
                                </li>
                                <li><a href="mailto:info@limak.az"><i
                                                class="fa fa-envelope"></i><span>info@limak.az</span></a>
                                </li>
                                <li><i class="fa fa-map-marker"></i><span>@lang('common.address')</span></li>
                            </ul>
                        </div>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d319.53452916962686!2d49.8293159739942!3d40.365812947941436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307dc96ca1a6b1%3A0x3729c8228d2fdf4!2zMTEzIExlcm1vbnRvdiBTdHJlZXQsIEJha8SxLCDQkNC30LXRgNCx0LDQudC00LbQsNC9!5e0!3m2!1sru!2s!4v1550040327286" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection