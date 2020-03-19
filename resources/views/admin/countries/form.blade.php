@extends('layouts/admin')
@section('styles')
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

        .html5-image-upload:after {
            content: 'Şəkil';
            bottom: 30%;
        }

    </style>
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div class="container">
            <div class="card">
                <div class="card-body b-b">
                    <h4>Ölkənlər</h4>
                    <form method="POST"  action="/{{App::getLocale()}}/admin/countries{{!is_null($data)?"/".$id:""}}" enctype="multipart/form-data">
                        @csrf
                        @if(!is_null($data))
                            @method('PUT')
                        @else
                            @method('POST')
                        @endif

                        <div class="card-header white pb-0">
                            <div class="d-flex justify-content-between">
                                <div class="align-self-center">
                                    <ul class="nav nav-pills mb-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active show r-20" id="w3--tab1" data-toggle="tab" href="#w3-tab1" role="tab" aria-controls="tab1" aria-expanded="true" aria-selected="true">AZ</a>
                                        </li>
                                        {{--<li class="nav-item">
                                            <a class="nav-link r-20" id="w3--tab2" data-toggle="tab" href="#w3-tab2" role="tab" aria-controls="tab2" aria-selected="false">RU</a>
                                        </li>--}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="w3-tab1" role="tabpanel" aria-labelledby="w3-tab1">
                                <div class="table-responsive">
                                    <div class="form-group">
                                        <label for="shopName" class="col-form-label">Ölkənin adı</label>
                                        <input required name="name[az]" type="text" class="form-control" id="shopName" value="{{!is_null($data) ? $data->translates[0]->name : ''}}" placeholder="Necə sifariş edim?">
                                    </div>
                                </div>
                            </div>
                            {{--<div class="tab-pane fade" id="w3-tab2" role="tabpanel" aria-labelledby="w3-tab2">
                                <div class="table-responsive">
                                    <div class="form-group">
                                        <label for="shopName" class="col-form-label">Ölkənin adı</label>
                                        <input required name="name[ru]" type="text" class="form-control" id="shopName" value="{{!is_null($data) ? $data->translates[1]->name : ''}}" placeholder="Sifarişinizi rahat şəkildə edin">
                                    </div>
                                </div>
                            </div>--}}
                        </div>
                        <div class="form-group text-center">
                            <div style="display: inline-block">
                                <div class="html5-image-upload" data-width="100" data-image="{{!is_null($data) && $data->file ? url(Storage::url($data->file)) : ''}}" data-ajax="false" data-height="50">
                                    <input type="file" name="file" />
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
    <script type="text/javascript" src="{{asset('admin/js/image_cropper/newCrop.js')}}"></script>
    <script type="text/javascript">
        $('.html5-image-upload').html5imageupload({
            buttonEdit: true,
            buttonZoomin: true
        });
    </script>
@endsection