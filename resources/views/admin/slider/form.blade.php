@extends('layouts/admin')
@section('styles')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{secure_asset('admin/js/cropperjs/dist/cropper.min.css')}}">
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

        .modal-dialog {
            max-width: 100%;
            padding: 0 20px;
        }

        .modal-body canvas, #show-image canvas, #show-image-mobile canvas {
            max-width: 100%;
        }


    </style>
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div class="container">
            <div class="card">
                <div class="card-body b-b">
                    @if(Session::has('message_error'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message_error') }}</p>
                    @endif
                    @if(Session::has('message_success'))
                        <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message_success') }}</p>
                    @endif
                    <h4>Slider</h4>
                    <form method="POST"  action="/{{App::getLocale()}}/admin/slider{{!is_null($data)?"/".$id:""}}" enctype="multipart/form-data">
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
                                        <li class="nav-item">
                                            <a class="nav-link r-20" id="w3--tab2" data-toggle="tab" href="#w3-tab2" role="tab" aria-controls="tab2" aria-selected="false">RU</a>
                                        </li>
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
                                        <label for="shopName" class="col-form-label">Slide adı</label>
                                        <input required name="name[az]" type="text" class="form-control" id="shopName" value="{{!is_null($data) && isset($data->translates[0]->name) ? $data->translates[0]->name : ''}}" placeholder="Ən aşağı qiymətlər bizdə">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="w3-tab2" role="tabpanel" aria-labelledby="w3-tab2">
                                <div class="table-responsive">
                                    <div class="form-group">
                                        <label for="shopName" class="col-form-label">Slide adı</label>
                                        <input name="name[ru]" type="text" class="form-control" id="shopName" value="{{!is_null($data) && isset($data->translates[1]->name) ? $data->translates[1]->name : ''}}" placeholder="Ən aşağı qiymətlər bizdə">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="main-image" class="col-form-label">Slide şəkli</label>
                            <input hidden name="main_image" id="main-image-input">
                            <div class="inner bg-container" id="show-image">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="file" name="file" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?PHP if(isset($data->file)){ ?>
                            <div class="form-group">
                                <img width="600px" src="<?=url(Storage::url($data->file))?>">
                                <p><strong>Qeyd: Yeni şəkil yükləndikdə mövcud şəkil silinəcəkdir</strong></p>
                            </div>
                        <?PHP }; ?>

                        <div class="form-group">
                            <label for="mobile-image" class="col-form-label">Slide şəkli mobil</label>
                            <input hidden name="mobile_image" id="mobile-image-input">
                            <div class="inner bg-container" id="show-image-mobile">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="file" name="mobile_file" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?PHP if(isset($data->mobile_file)){ ?>
                        <div class="form-group">
                            <img width="400px" src="<?=url(Storage::url($data->mobile_file))?>">
                            <p><strong>Qeyd: Yeni şəkil yükləndikdə mövcud şəkil silinəcəkdir</strong></p>
                        </div>
                        <?PHP }; ?>

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
    <script type="text/javascript" src="{{asset('admin/js/cropperjs/dist/cropper.min.js')}}"></script>
    <script type="text/javascript">

        var $download = $('#download');
        var $dataX = $('#dataX');
        var $dataY = $('#dataY');
        var $dataHeight = $('#dataHeight');
        var $dataWidth = $('#dataWidth');
        var $dataRotate = $('#dataRotate');
        var $dataScaleX = $('#dataScaleX');
        var $dataScaleY = $('#dataScaleY');


        var console = window.console || { log: function () {} };
        var $image = $('#image');
        var options = {
            aspectRatio: 19 / 5,
            preview: '.img-preview',
            crop: function (e) {
                $dataX.val(Math.round(e.x));
                $dataY.val(Math.round(e.y));
                $dataHeight.val(Math.round(e.height));
                $dataWidth.val(Math.round(e.width));
                $dataRotate.val(e.rotate);
                $dataScaleX.val(e.scaleX);
                $dataScaleY.val(e.scaleY);
            }
        };

        $image.on({
            'build.cropper': function (e) {
            },
            'built.cropper': function (e) {
            },
            'cropstart.cropper': function (e) {
            },
            'cropmove.cropper': function (e) {
            },
            'cropend.cropper': function (e) {
            },
            'crop.cropper': function (e) {
            },
            'zoom.cropper': function (e) {
            }
        }).cropper(options);

        if (!$.isFunction(document.createElement('canvas').getContext)) {
            $('button[data-method="getCroppedCanvas"]').prop('disabled', true);
        }

        if (typeof document.createElement('cropper').style.transition === 'undefined') {
            $('button[data-method="rotate"]').prop('disabled', true);
            $('button[data-method="scale"]').prop('disabled', true);
        }

        $('.docs-buttons').on('click', '[data-method]', function () {
            var $this = $(this);
            var data = $this.data();
            var $target;
            var result;

            if ($this.prop('disabled') || $this.hasClass('disabled')) {
                return;
            }

            if ($image.data('cropper') && data.method) {
                data = $.extend({}, data); // Clone a new one

                if (typeof data.target !== 'undefined') {
                    $target = $(data.target);

                    if (typeof data.option === 'undefined') {
                        try {
                            data.option = JSON.parse($target.val());
                        } catch (e) {
                        }
                    }
                }

                if (data.method === 'rotate') {
                    $image.cropper('clear');
                }

                result = $image.cropper(data.method, data.option, data.secondOption);
                if (data.method === 'rotate') {
                    $image.cropper('crop');
                }

                switch (data.method) {
                    case 'scaleX':
                    case 'scaleY':
                        $(this).data('option', -data.option);
                        break;

                    case 'getCroppedCanvas':
                        if (result) {

                            // Bootstrap's Modal
                            $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);


                            $download.click(function () {
                                var asd = result.toDataURL('image/jpeg');
                                $('#show-image').html(result);
                                $('#main-image-input').val(asd);
                                $('#getCroppedCanvasModal').removeClass("show");
                                $('.modal-backdrop').remove();
                                $('body').removeClass("modal-open");
                            })

                        }

                        break;
                }

                if ($.isPlainObject(result) && $target) {
                    try {
                        $target.val(JSON.stringify(result));
                    } catch (e) {
                    }
                }

            }
        });

        $(document.body).on('keydown', function (e) {

            if (!$image.data('cropper') || this.scrollTop > 300) {
                return;
            }

            switch (e.which) {
                case 37:
                    e.preventDefault();
                    $image.cropper('move', -1, 0);
                    break;

                case 38:
                    e.preventDefault();
                    $image.cropper('move', 0, -1);
                    break;

                case 39:
                    e.preventDefault();
                    $image.cropper('move', 1, 0);
                    break;

                case 40:
                    e.preventDefault();
                    $image.cropper('move', 0, 1);
                    break;
            }

        });

        var $inputImage = $('#inputImage');
        var URL = window.URL || window.webkitURL;
        var blobURL;

        if (URL) {
            $inputImage.on("change",function () {
                var files = this.files;
                var file;

                if (!$image.data('cropper')) {
                    return;
                }

                if (files &&files.length) {
                    file = files[0];

                    console.log(file);

                    if (/^image\/\w+$/.test(file.type)) {
                        blobURL = URL.createObjectURL(file);
                        $image.on('built.cropper', function () {

                            // Revoke when load complete
                            URL.revokeObjectURL(blobURL);
                        }).cropper('reset').cropper('replace', blobURL);
                        $inputImage.val('');
                    } else {
                        window.alert('Please choose an image file.');
                    }
                }
            });
        } else {
            $inputImage.prop('disabled', true).parent().addClass('disabled');
        }

        ///////////////////////////////////////////////////

        var $mobile_image = $('#mobile_image');
        var $mobile_download = $('#mobile_download');
        var mobile_options = {
            aspectRatio: 6 / 5,
            preview: '.img-preview-mobile',
            crop: function (e) {
                $dataX.val(Math.round(e.x));
                $dataY.val(Math.round(e.y));
                $dataHeight.val(Math.round(e.height));
                $dataWidth.val(Math.round(e.width));
                $dataRotate.val(e.rotate);
                $dataScaleX.val(e.scaleX);
                $dataScaleY.val(e.scaleY);
            }
        };

        $mobile_image.on({
            'build.cropper': function (e) {
            },
            'built.cropper': function (e) {
            },
            'cropstart.cropper': function (e) {
            },
            'cropmove.cropper': function (e) {
            },
            'cropend.cropper': function (e) {
            },
            'crop.cropper': function (e) {
            },
            'zoom.cropper': function (e) {
            }
        }).cropper(mobile_options);

        if (!$.isFunction(document.createElement('canvas').getContext)) {
            $('button[data-method="mobileGetCroppedCanvas"]').prop('disabled', true);
        }

        if (typeof document.createElement('cropper').style.transition === 'undefined') {
            $('button[data-method="rotate"]').prop('disabled', true);
            $('button[data-method="scale"]').prop('disabled', true);
        }

        $('.mobile-docs-buttons').on('click', '[data-method]', function () {
            var $this = $(this);
            var data = $this.data();
            var $target;
            var result_mobile;

            if ($this.prop('disabled') || $this.hasClass('disabled')) {
                return;
            }

            if ($mobile_image.data('cropper') && data.method) {
                data = $.extend({}, data); // Clone a new one

                if (typeof data.target !== 'undefined') {
                    $target = $(data.target);

                    if (typeof data.option === 'undefined') {
                        try {
                            data.option = JSON.parse($target.val());
                        } catch (e) {
                        }
                    }
                }

                if (data.method === 'rotate') {
                    $mobile_image.cropper('clear');
                }

                result_mobile = $mobile_image.cropper(data.method, data.option, data.secondOption);
                if (data.method === 'rotate') {
                    $mobile_image.cropper('crop');
                }

                switch (data.method) {
                    case 'scaleX':
                    case 'scaleY':
                        $(this).data('option', -data.option);
                        break;

                    case 'mobileGetCroppedCanvas':
                        if (result_mobile) {

                            // Bootstrap's Modal
                            $('#mobileGetCroppedCanvasModal').modal().find('.modal-body').html(result_mobile);


                            $mobile_download.click(function () {
                                var asd = result_mobile.toDataURL('image/jpeg');
                                $('#show-image-mobile').html(result_mobile);
                                $('#mobile-image-input').val(asd);
                                $('#mobileGetCroppedCanvasModal').removeClass("show");
                                $('.modal-backdrop').remove();
                                $('body').removeClass("modal-open");
                            })

                        }

                        break;
                }

                if ($.isPlainObject(result_mobile) && $target) {
                    try {
                        $target.val(JSON.stringify(result_mobile));
                    } catch (e) {
                    }
                }

            }
        });

        $(document.body).on('keydown', function (e) {

            if (!$mobile_image.data('cropper') || this.scrollTop > 300) {
                return;
            }

            switch (e.which) {
                case 37:
                    e.preventDefault();
                    $mobile_image.cropper('move', -1, 0);
                    break;

                case 38:
                    e.preventDefault();
                    $mobile_image.cropper('move', 0, -1);
                    break;

                case 39:
                    e.preventDefault();
                    $mobile_image.cropper('move', 1, 0);
                    break;

                case 40:
                    e.preventDefault();
                    $mobile_image.cropper('move', 0, 1);
                    break;
            }

        });

        var $inputImageMobile = $('#inputImageMobile');
        var URLM = window.URL || window.webkitURL;
        var blobURLMobile;

        if (URLM) {
            $inputImageMobile.on("change",function () {
                var mobile_files = this.files;
                var mobile_file;

                if (!$mobile_image.data('cropper')) {
                    return;
                }

                if (mobile_files && mobile_files.length) {
                    mobile_file = mobile_files[0];

                    if (/^image\/\w+$/.test(mobile_file.type)) {
                        blobURLMobile = URLM.createObjectURL(mobile_file);
                        $mobile_image.on('built.cropper', function () {

                            URLM.revokeObjectURL(blobURLMobile);
                        }).cropper('reset').cropper('replace', blobURLMobile);
                        $inputImageMobile.val('');
                    } else {
                        window.alert('Please choose an image file.');
                    }
                }
            });
        } else {
            $inputImageMobile.prop('disabled', true).parent().addClass('disabled');
        }


    </script>
@endsection
