@extends('cp.layouts.admin')
@section('styles')
    {{--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">--}}

    {{--<link type="text/css" rel="stylesheet" href="{{asset('admin/js/image_cropper/newCrop.css')}}" />--}}
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

        .breadcrumb {
            display: block;
            text-align: right;
        }

        .breadcrumb li a {
            color: #86939e
        }

        .check-button {
            position: relative;
            padding-left: 35px;
            margin: 4px 0;
            cursor: pointer;
            font-size: 14px;
            color: #808080;
            font-weight: 400;
            transition: ease-in .05s;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .check-button input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .check-button .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            border: 1px solid #aaa;
            background: transparent;
            transition: ease-in .05s;
            margin-top: 3px;
        }

        .check-button .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .check-button input:checked ~ .checkmark.selected:after {
            display: block;
        }

        .check-button input:checked ~ .checkmark.selected {
            border: 1px solid #f95631;
        }

        .check-button .checkmark.selected:after {
            left: 6px;
            bottom: 3px;
            width: 7px;
            height: 15px;
            border: solid #f95631;
            border-width: 0 2px 2px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(37deg);
        }

    </style>
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="/cp"><i class="fa fa-home"></i>Ana səhifə</a></li>
                <li><a href="/cp/shops?country_id={{$c_id}}">Mağazalar</a></li>
                <li class="active">Mağaza əlavə et</li>
            </ol>
            <div class="card">
                <div class="card-body b-b">
                    <h4>Mağazalar</h4>
                    {{--<form method="POST"  action="/{{App::getLocale()}}/admin/shops{{!is_null($data)?"/".$id:""}}" enctype="multipart/form-data">--}}
                    <form method="POST" action="/cp/shops{{!is_null($data)?"/".$id:""}}" enctype="multipart/form-data">
                        <div class="row">
                            @csrf
                            @if(!is_null($data))
                                @method('PUT')
                            @else
                                @method('POST')
                            @endif
                            <div class="form-group col-md-6">
                                <label for="shopName" class="col-form-label">Mağazanın adı</label>
                                <input required name="name" type="text" class="form-control" id="shopName"
                                       value="{{!is_null($data) ? $data->name : ''}}" placeholder="Trendyol">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="shopUrl" class="col-form-label">Mağazanın linki</label>
                                <input required name="url" type="text" class="form-control" id="shopUrl"
                                       value="{{!is_null($data) ? $data->url : ''}}"
                                       placeholder="https://www.trendyol.com/">
                            </div>
                            <div class="col-md-6">
                                <h4>Ölkəni seçin</h4>
                                <select required class="form-control country" aria-label="secin">
                                    @foreach($countries as $item)
                                        @if($item->id==$c_id)
                                            <option selected value="{{$item->id}}">{{$item->name}}</option>
                                        @else
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <input type="hidden" name="country_id" value="{{$c_id}}">
                            </div>
                            <div class="form-group col-md-6" style="display: flex;align-items: flex-end">
                                <div style="display: inline-block">
                                    <div class="html5-image-upload" data-width="250"
                                         data-image="{{!is_null($data) && $data->file ? url(Storage::url($data->file)) : ''}}"
                                         data-ajax="false" data-height="80">
                                        <input type="file" name="file"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                @php

                                    $shop_types=explode(',',($data ? $data->types : ''));
                                @endphp
                                <ul>
                                    @foreach($types as $item)
                                        <li>
                                            <label class="check-button selected">
                                                <span class="check-btn-text">{{$item->name}}</span>
                                                <input name="types[]" class="whenUnclick" value="{{$item->id}}" {{in_array($item->id,$shop_types) ? 'checked' : ''}} type="checkbox" >
                                                <span class="checkmark selected"></span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <input type="hidden" name="deleteTypes" value="">
                            <div class="form-group col-md-12" style="margin-top: 1%;text-align: right">
                                <button type="submit" class="btn btn-success">Təsdiqlə</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{--<script type="text/javascript" src="{{asset('admin/js/image_cropper/newCrop.js')}}"></script>--}}
    {{--<script type="text/javascript">--}}
    {{--$('.html5-image-upload').html5imageupload({--}}
    {{--buttonEdit: true,--}}
    {{--buttonZoomin: true--}}
    {{--});--}}
    {{--</script>--}}
    <script>

        var arr = [];
        $('.whenUnclick').click(function () {
            var elem = event.target;

            if ($(elem).prop("checked"))
                arr.splice($(elem).val(),1);
            else
                arr.push($(elem).val())

            $('input[name="deleteTypes"]').val(arr.join(','));
        });
        $('select.country').change(function () {
            var selectedCountry = $(this).children("option:selected").val();
            $('input[name="country_id"]').val(selectedCountry)
        });
    </script>
@endsection

