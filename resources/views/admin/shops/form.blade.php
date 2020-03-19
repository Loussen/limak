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
            <div class="card">
                <div class="card-body b-b">
                    <h4>Mağazalar</h4>
                    <form method="POST"  action="/{{App::getLocale()}}/admin/shops{{!is_null($data)?"/".$id:""}}" enctype="multipart/form-data">
                        @csrf
                        @if(!is_null($data))
                            @method('PUT')
                        @else
                            @method('POST')
                        @endif
                        <div class="form-group">
                            <label for="shopName" class="col-form-label">Mağazanın adı</label>
                            <input required name="name" type="text" class="form-control" id="shopName" value="{{!is_null($data) ? $data->name : ''}}" placeholder="Trendyol">
                        </div>
                        <div class="form-group">
                            <label for="shopUrl" class="col-form-label">Mağazanın linki</label>
                            <input required name="url" type="text" class="form-control" id="shopUrl" value="{{!is_null($data) ? $data->url : ''}}" placeholder="https://www.trendyol.com/">
                        </div>
                        <div class="form-group text-center">
                            <div style="display: inline-block">
                                <div class="html5-image-upload" data-width="250" data-image="{{!is_null($data) && $data->file ? url(Storage::url($data->file)) : ''}}" data-ajax="false" data-height="80">
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