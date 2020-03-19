@extends('layouts.front_main')

@section('content')
    <section class="contact brands">
        @include('front.components.breadcrumb',['section' => 'contact'])
        <div class="contact-body">
            <div class="container">
                <div class="row relative">
                    <div class="col-xs-12">
                        <div class="contact-content invoice-left-side countries-body">
                            <div class="row">
                                @if (session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                <div class="col-sm-7 col-xs-12">
                                    <h1>@lang('common.contact')</h1>


                                    <ul class="nav nav-tabs country-inner-tabs" role="tablist">
                                        <li role="presentation" class="active">
                                            <a href="#baku" aria-controls="baku" role="tab" data-toggle="tab">
                                                @lang('panel-errors.baku')
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#sumgait" aria-controls="sumgait" role="tab" data-toggle="tab">
                                                @lang('panel-errors.sumgait')
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#ganja" aria-controls="ganja" role="tab" data-toggle="tab">
                                                @lang('panel-errors.ganja')
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#zagatala" aria-controls="zagatala" role="tab" data-toggle="tab">
                                                @lang('panel-errors.zagatala')
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#lankaran" aria-controls="lankaran" role="tab" data-toggle="tab">
                                                @lang('panel-errors.lankaran')
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade in active" id="baku">
                                            <address>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/phone-call.png') }}" alt="phone-call"> *9595
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/whatsapp2.png') }}" alt="phone-call"> 99450 824 95 95
                                                </div>

                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/envelope.png') }}" alt="envelope"> info@limak.az
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/place.png') }}" alt="place"> @lang('common.address')
                                                    <p >

                                                    <b>@lang('home.order_service'):</b> @lang('home.order_service_time') <br />
                                                    <b>@lang('home.customer_service'):</b> @lang('home.customer_service_time') <br />
                                                    <b>@lang('home.depot'):</b>@lang('home.iv') @lang('home.depot_time') <br />
                                                    </p>
                                                </div>
                                            </address>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="sumgait">
                                            <address>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/phone-call.png') }}" alt="phone-call"> +994704919595
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/phone-call.png') }}" alt="phone-call"> *9595
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/whatsapp2.png') }}" alt="phone-call"> 99450 824 95 95
                                                </div>

                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/envelope.png') }}" alt="envelope"> info@limak.az
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/place.png') }}" alt="place"> @lang('common.address-sumgait')
                                                    <p >
                                                        <b>@lang('home.order_service'):</b> @lang('home.order_service_time') <br />
                                                        <b>@lang('home.customer_service'):</b> @lang('home.customer_service_time') <br />
                                                        <b>@lang('home.depot'):</b>  @lang('home.iv')  @lang('home.region_depot_time') <br />
                                                    </p>
                                                </div>
                                            </address>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="ganja">
                                            <address>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/phone-call.png') }}" alt="phone-call"> 994707279595
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/phone-call.png') }}" alt="phone-call"> *9595
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/whatsapp2.png') }}" alt="phone-call"> 99450 824 95 95
                                                </div>

                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/envelope.png') }}" alt="envelope"> info@limak.az
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/place.png') }}" alt="place"> @lang('common.address-ganja')
                                                    <p >
                                                        <b>@lang('home.order_service'):</b> @lang('home.order_service_time') <br />
                                                        <b>@lang('home.customer_service'):</b> @lang('home.customer_service_time') <br />
                                                        <b>@lang('home.depot'):</b>@lang('home.iv') @lang('home.region_depot_time') <br />
                                                    </p>
                                                </div>
                                            </address>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="zagatala">
                                            <address>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/phone-call.png') }}" alt="phone-call"> 994708249595
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/phone-call.png') }}" alt="phone-call"> *9595
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/whatsapp2.png') }}" alt="phone-call"> 99450 824 95 95
                                                </div>

                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/envelope.png') }}" alt="envelope"> info@limak.az
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/place.png') }}" alt="place"> @lang('common.address-zagatala')
                                                    <p >
                                                        <b>@lang('home.order_service'):</b> @lang('home.order_service_time') <br />
                                                        <b>@lang('home.customer_service'):</b> @lang('home.customer_service_time') <br />
                                                        <b>@lang('home.depot'):</b>@lang('home.iv') @lang('home.zagatala_depot_time') <br />
                                                    </p>
                                                </div>
                                            </address>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="lankaran">
                                            <address>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/phone-call.png') }}" alt="phone-call"> 99470 624 95 95
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/phone-call.png') }}" alt="phone-call"> *9595
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/whatsapp2.png') }}" alt="phone-call"> 99450 824 95 95
                                                </div>

                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/envelope.png') }}" alt="envelope"> info@limak.az
                                                </div>
                                                <div class="address">
                                                    <img src="{{ asset('front/new/img/place.png') }}" alt="place"> @lang('common.address-lankaran')
                                                    <p >
                                                        <b>@lang('home.order_service'):</b> @lang('home.order_service_time') <br />
                                                        <b>@lang('home.customer_service'):</b> @lang('home.customer_service_time') <br />
                                                        <b>@lang('home.depot'):</b>@lang('home.iv')  @lang('home.lankaran_depot_time') <br />
                                                    </p>
                                                </div>
                                            </address>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-sm-5 col-xs-12">
                                    <h3><b>@lang('common.write_us')</b></h3>
                                    <form action="{{ route('front.contact.store') }}" method="POST">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="input-group border-radius">
                                                    <label>
                                                        <input type="text" name="name" class="form-control inputText" placeholder=" ">
                                                        <span>@lang('common.name_surname') * </span>
                                                    </label>
                                                </div>
                                                <div class="input-group border-radius">
                                                    <label>
                                                        <input type="text" name="email" class="form-control inputText" placeholder=" ">
                                                        <span>@lang('common.mail') * </span>
                                                    </label>
                                                </div>
                                                <div class="textarea-form form-group">
                                                    <textarea class="form-control" name="description"  rows="4" id="comment" placeholder="@lang('common.message') *"></textarea>
                                                </div>
                                                <button type="submit" class="btn-effect border-btn">@lang('common.send')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 100%">
            <iframe width="100%" height="600" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d319.53452916962686!2d49.8293159739942!3d40.365812947941436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307dc96ca1a6b1%3A0x3729c8228d2fdf4!2zMTEzIExlcm1vbnRvdiBTdHJlZXQsIEJha8SxLCDQkNC30LXRgNCx0LDQudC00LbQsNC9!5e0!3m2!1sru!2s!4v1550040327286" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
                <a href="https://www.maps.ie/map-my-route/">Create route map</a>
            </iframe>
        </div>
    </section>
@endsection