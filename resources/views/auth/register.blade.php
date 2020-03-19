@extends('layouts.front_main')
@section('styles')
    <style>
    </style>
@endsection
@section('content')
<section class="sign-in invoice content">
    <div class="container">
        <div class="row">
            <div class="invoice-head col-xs-12">
                <div class="col-md-4 col-sm-5 col-xs-7">
                    <h4>@lang('register.user_register')</h4>
                </div>
                <div class="col-md-8 col-sm-7 col-xs-5">
                    <ol class="breadcrumb web">
                        <li><a href="{{ route('front.main') }}">@lang('register.home_page') </a></li>
                        <li class="active"> @lang("register.register")</li>
                    </ol>
                    <ol class="breadcrumb mobile">
                        <li><a href="index.html">... </a></li>
                        <li class="active">@lang("register.register")</li>
                    </ol>
                </div>
            </div>
            <div class="invoice-body col-xs-12">
                <div class="row relative">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="invoice-left-side block">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="left-content">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h4>@lang('register.profile_details')</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 ">
                                            <div class="{{ $errors->has('name') ? 'error-message ' : '' }} input-group border-radius">
                                                <label>
                                                    <input value="{{ old('name') }}" type="text" name="name" class="form-control inputText" placeholder=" ">
                                                    <span>@lang('register.name') * </span>
                                                </label>
                                                @if ($errors->has('name'))
                                                    <span class="error-text">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="{{ $errors->has('surname') ? 'error-message ' : '' }} input-group border-radius">
                                                <label>
                                                    <input value="{{ old('surname') }}" type="text" name="surname" class="form-control inputText" placeholder=" ">
                                                    <span>@lang('register.surname') *</span>
                                                </label>
                                                @if ($errors->has('surname'))
                                                    <span class="error-text">{{ $errors->first('surname') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 ">
                                            <div class="{{ $errors->has('email') ? 'error-message ' : '' }} input-group border-radius">
                                                <label>
                                                    <input value="{{ old('email') }}" type="text" name="email" class="form-control inputText" placeholder=" ">
                                                    <span>@lang('register.email') *</span>
                                                </label>
                                                @if ($errors->has('email'))
                                                    <span class="error-text">{{ $errors->first('email') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="{{ $errors->has('phone') ? 'error-message ' : '' }} input-group border-radius tel-edit">
                                                <label>
                                                    <input value="{{ old('phone') }}" type="text" name="phone" class="form-control inputText" placeholder=" " id="phoneInput">
                                                    <span>@lang('register.phone') *</span>
                                                    <p>+994</p>
                                                </label>
                                                @if ($errors->has('phone'))
                                                    <span class="error-text">{{ $errors->first('phone') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 ">
                                            <div class="{{ $errors->has('password') ? 'error-message ' : '' }} input-group border-radius">
                                                <label>
                                                    <input type="password" name="password" class="form-control inputText" placeholder=" ">
                                                    <span>@lang('register.password') *</span>
                                                </label>
                                                @if ($errors->has('password'))
                                                    <span class="error-text">{{ $errors->first('password') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="{{ $errors->has('password_confirmation') ? 'error-message ' : '' }} input-group border-radius">
                                                <label>
                                                    <input type="password" name="password_confirmation" class="form-control inputText" placeholder=" ">
                                                    <span>@lang('register.password_confirmation') *</span>
                                                </label>
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="error-text">{{ $errors->first('password') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h4>@lang('register.passport_details')</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 ">
                                            <div class="{{ $errors->has('serial_number') ? 'error-message ' : '' }} input-group border-radius">
                                                <label>
                                                    <input value="{{ old('serial_number') }}" type="text" name="serial_number" class="form-control inputText" placeholder=" ">
                                                    <span>@lang('register.serial_number') *</span>
                                                </label>
                                                @if ($errors->has('serial_number'))
                                                    <span class="error-text">{{ $errors->first('serial_number') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="{{ $errors->has('citizenship') ? 'error-message ' : '' }} input-group border-radius">
                                                <label>
                                                    <input value="{{ old('citizenship') }}" type="text" name="citizenship" class="form-control inputText" placeholder=" ">
                                                    <span>@lang('register.citizinship') *</span>
                                                </label>
                                                @if ($errors->has('citizenship'))
                                                    <span class="error-text">{{ $errors->first('citizenship') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="invoice-date">
                                                <h5>@lang('register.birthdate') *</h5>
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <div class="{{ $errors->has('day') ? 'error-message ' : '' }} invoice-day input-group border-radius">
                                                <select class=" form-control selectpicker" name="day" title="@lang('invoice/index.day')">
                                                    <?php $days = range(1,31);
                                                    foreach ($days as $day){
                                                        if(old('day') == $day){
                                                            echo "<option selected value='$day'>$day</option>";
                                                        } else{
                                                            echo "<option value='$day'>$day</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                @if ($errors->has('day'))
                                                    <span class="error-text">{{ $errors->first('day') }}</span>
                                                @endif
                                            </div>
                                            <?php $months = explode(",",__('register.months')) ?>
                                            <div class="{{ $errors->has('month') ? 'error-message ' : '' }} invoice-month input-group border-radius">
                                                <select class="form-control selectpicker" name="month" title="@lang('invoice/index.month')">
                                                    <?php
                                                        for ($i = 0; $i < 12;$i++){
                                                            $month = $i+1;
                                                            if(old('month') == $i+1 ){
                                                                echo "<option selected value='$month'>$months[$i]</option>";
                                                            } else{
                                                                echo "<option value='$month'>$months[$i]</option>";
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                                @if ($errors->has('month'))
                                                    <span class="error-text">{{ $errors->first('month') }}</span>
                                                @endif
                                            </div>
                                            <div class="{{ $errors->has('year') ? 'error-message ' : '' }} invoice-year input-group border-radius">
                                                <select class="form-control selectpicker" name="year" title="@lang('invoice/index.year')">
                                                    <?php $years = range(date("Y")-16 ,date("Y") - 100);
                                                    foreach ($years as $year){
                                                        if(old('year') == $year){
                                                            echo "<option selected value='$year'>$year</option>";
                                                        } else{
                                                            echo "<option value='$year'>$year</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                @if ($errors->has('year'))
                                                    <span class="error-text">{{ $errors->first('year') }}</span>
                                                @endif
                                            </div>
                                            <div class="{{ $errors->has('gender') ? 'error-message ' : '' }} gender input-group border-radius">
                                                <select class="form-control selectpicker" name="gender" title="@lang('register.gender') *">
                                                    <option {{ old('gender') == 2 ? 'selected' : '' }} value="2">@lang('register.female')</option>
                                                    <option {{ old('gender') == 1 ? 'selected' : '' }} value="1">@lang('register.male')</option>
                                                </select>
                                                @if ($errors->has('gender'))
                                                    <span class="error-text">{{ $errors->first('gender') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="{{ $errors->has('fin') ? 'error-message ' : '' }} input-group border-radius">
                                                <label>
                                                    <input value="{{ old('fin') }}" type="text" name="fin" class="form-control inputText" placeholder=" ">
                                                    <span>@lang('register.fin')</span>
                                                </label>
                                                @if ($errors->has('fin'))
                                                    <span class="error-text">{{ $errors->first('fin') }}</span>
                                                @endif
                                                <span class="fin">@lang('register.what_is_fin')
                                            <button type="button" data-toggle="modal" data-target=".bs-example-modal-sm-1"><i class="fa fa-question-circle" aria-hidden="true"></i></button>
                                        </span>
                                                <div class="modal fade bs-example-modal-sm-1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel2">
                                                    <div class="modal-dialog modal-sm" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="gridSystemModalLabel2">@lang('register.serial_number')</h4>
                                                            </div>
                                                            <img src="{{ asset('front/new/img/fin-big.png') }}" alt="fin_big" class="img-responsive center-block">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="{{ $errors->has('address') ? 'error-message ' : '' }} input-group border-radius">
                                                <label>
                                                    <input value="{{ old('address') }}" type="text" name="address" class="form-control inputText" placeholder=" ">
                                                    <span>@lang('register.address') *</span>
                                                </label>
                                                @if ($errors->has('address'))
                                                    <span class="error-text">{{ $errors->first('address') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @php($staticPage = staticPage('userpolicy', Lang::getLocale()))
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="btn-part">
                                                <label class="check-button">
                                                    <span class="check-btn-text">
                                                        @if(!empty($staticPage))
                                                            <a target="_blank" data-toggle="modal" data-target=".privacy-modal">@lang('register.terms')</a>
                                                        @endif

                                                    </span>
                                                    <input {{ old('terms') ? 'checked' : '' }} value="1" name="terms" type="checkbox" >
                                                    <span class="checkmark"></span>
                                                </label>
                                                <button type="submit" class="btn-effect">
                                                    <span>@lang('register.confirm_big')</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12 web">
                        <div class="invoice-right block">
                            <h3 class="text-center">@lang('register.attention')</h3>
                            <p>@lang('register.attention_text')</p>
                            <img src="{{ asset('front/new/img/fin-big.png') }}" alt="fin_image" class="img-responsive center-block">
                            <div class="focus">
                                <button type="button" class="btn" data-toggle="modal" data-target=".bs-example-modal-sm-2">@lang('register.zoom_out')</button>
                            </div>
                            <div class="modal fade bs-example-modal-sm-2" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="gridSystemModalLabel1">@lang('register.serial_number')</h4>
                                        </div>
                                        <img src="{{ asset('front/new/img/fin-big.png') }}" alt="fin_big" class="img-responsive center-block">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="privacy-modal modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <header class="modal-header">
                <h5 style="display: inline-block;" class="modal-title">
                    <h3>@lang('register.user_rules')</h3>
                </h5>
                <button type="button" class="close"  data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </header>
            <main class="modal-body">
                <div >
                    @lang('home.user_rules')
                </div>
            </main>
            <footer class="modal-footer">
                <button class="btn btn-effect" data-dismiss="modal">
                    @lang('panel-errors.accept')
                </button>
            </footer>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        /*$(document).ready(function () {
            var checked = false;
            $('#checkbox1').on('click', function () {
                checked = !checked;
                $('#acceptRegister').attr('disabled', !checked);
            });

            $opened = localStorage.getItem('shown');
            if($opened && $opened !== '') {

            } else {
                setTimeout(function() {
                    $('#exampleModal').modal('show');
                }, 2500);
                localStorage.setItem('shown', 'true');
            }
        });*/

    </script>
@endsection
