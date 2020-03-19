<?PHP
use Illuminate\Support\Facades\Session;
?>

@extends('cp.layouts.admin')
@section('styles')

    <link type="text/css" rel="stylesheet" href="{{asset('admin/js/fileinput/css/fileinput.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('admin/css/form_elements.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('admin/js/summernote/css/summernote.css')}}" />
    {{--<link type="text/css" rel="stylesheet" href="{{asset('admin/js/jasny-bootstrap/css/jasny-bootstrap.min.css')}}" />--}}
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
@section('breadcrumb')
    <h4>SƏHİFƏ ƏLAVƏ ET</h4>
    <ol class="breadcrumb">
        <li><a href="/cp"><i class="fa fa-home"></i>Ana səhifə</a></li>
        <li><a href="/cp/news"><i class="fa fa-home"></i>Səhifələr</a></li>
        <li class="active">Səhifələr</li>
    </ol>
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div class="container">
            <div class="card add-news">
                <div class="card-body b-b">
                    @if(Session::has('message_error'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message_error') }}</p>
                    @endif
                    @if(Session::has('message_success'))
                        <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message_success') }}</p>
                    @endif
                    <h4>Səhifələr</h4>
                    <form method="POST"  action="/{{App::getLocale()}}/admin/news{{!is_null($data)?"/".$id:""}}" enctype="multipart/form-data">
                        @csrf
                        @if(!is_null($data))
                            @method('PUT')
                        @else
                            @method('POST')
                        @endif
                        <div class="">
                            <div class="card no-b">
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
                                <div class="card-body no-p">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="w3-tab1" role="tabpanel" aria-labelledby="w3-tab1">
                                            <div class="table-responsive">
                                                <div class="form-group">
                                                    <label for="shopName" class="col-form-label">Başlıq</label>
                                                    <input name="name[az]" type="text" class="form-control" id="shopName" value="{{!is_null($data) && isset($data->translates[0]->name) ? $data->translates[0]->name : ''}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="shopName" class="col-form-label">Qısa başlıq</label>
                                                    <input name="description[az]" type="text" class="form-control" id="shopName" value="{{!is_null($data) && isset($data->translates[0]->description) ? $data->translates[0]->description : ''}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="shopName" class="col-form-label">Açar sözlər</label>
                                                    <input name="keywords[az]" type="text" class="form-control" id="shopName" value="{{!is_null($data) && isset($data->translates[0]->keywords) ? $data->translates[0]->keywords : ''}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="shopUrl" class="col-form-label">Kontent</label>
                                                    <textarea required class="ckeditor" id="ckeditor" name="content[az]">{{!is_null($data) && isset($data->translates[0]->content) ? $data->translates[0]->content : ''}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="w3-tab2" role="tabpanel" aria-labelledby="w3-tab2">
                                            <div class="table-responsive">
                                                <div class="form-group">
                                                    <label for="shopName" class="col-form-label">Başlıq</label>
                                                    <input name="name[ru]" type="text" class="form-control" id="shopName" value="{{!is_null($data) && isset($data->translates[1]->name) ? $data->translates[1]->name : ''}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="shopName" class="col-form-label">Qısa başlıq</label>
                                                    <input name="description[ru]" type="text" class="form-control" id="shopName" value="{{!is_null($data) && isset($data->translates[1]->description) ? $data->translates[1]->description : ''}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="shopName" class="col-form-label">Açar sözlər</label>
                                                    <input name="keywords[ru]" type="text" class="form-control" id="shopName" value="{{!is_null($data) && isset($data->translates[1]->keywords) ? $data->translates[1]->keywords : ''}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="shopUrl" class="col-form-label">Kontent</label>
                                                    <textarea class="ckeditor"  name="content[ru]">{{!is_null($data) && isset($data->translates[1]->content) ? $data->translates[1]->content : ''}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <div style="width: 100%">
                                        <div class="html5-image-upload" data-resize="false" data-width="952" data-image="{{!is_null($data) && $data->file ? url(Storage::url($data->file)) : ''}}" data-ajax="false" data-height="580" style="height: 580px !important; width: 952px !important;">
                                            <input type="file" name="file" />
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="form-group m-t-15">
                                    <label for="main-image">Şəkil qalareyası</label>
                                    <input id="input-22" type="file" multiple="true" name="images[]" accept="image/*" class="form-control file-loading">
                                </div>--}}
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-success">Təsdiqlə</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset('admin/js/fileinput/js/fileinput.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/fileinput/js/theme.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/summernote/js/summernote.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/js/image_cropper/newCrop.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/text_editor/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript">
        $('.html5-image-upload').html5imageupload({
            buttonEdit: true,
            buttonZoomin: true
        });
        CKEDITOR.replace( 'ckeditor', {
            filebrowserBrowseUrl: '/vendor/text_editor/ckfinder/ckfinder.html',
            filebrowserUploadUrl: '/vendor/text_editor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
        } );
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#input-22").fileinput({
            theme: "fa",
            previewFileType: "image",
            browseClass: "btn btn-success",
            browseLabel: "Şəkil seç",
            removeClass: "btn btn-danger",
            removeLabel: "Sil",
            multiple:true,
            showUpload: false,
            deleteUrl: '/{{App::getLocale()}}/admin/delete-news-file',

            initialPreview: [
                @if(count($imagesUrl) != 0)
                    @foreach($imagesUrl as $img)
                    @if($img != "/storage/")
                    "{{url($img)}}",
                @endif
                @endforeach

                @endif

            ],
            initialPreviewConfig: {!! json_encode($imagesConfig)!!}
            ,
            initialPreviewAsData: true
        });
        // created by Mahir Abdullayev
        $(document).ready(function(){
            var lfmt = function(options, cb) {
                var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };


            (function ( $ ) {
                $.fn.bindMultiple = function( options ) {
                    var ths = $(this);
                    var settings = $.extend({
                        funcName: "#556b2f",
                    }, options );
                    var LFMButton = function(context) {
                        var ui = $.summernote.ui;
                        var button = ui.button({
                            contents: '<i class="fa fa-file"></i> ',
                            tooltip: 'Insert image with filemanager',
                            click: function() {
                                lfmt({type: 'image', prefix: '/laravel-filemanager'}, function(url, path ) {
                                    ths.summernote('editor.insertImage', url);
                                });
                            }
                        });
                        return button.render();
                    };
                    $(this).summernote({
                        "height":300,
                        "test" : "here",
                        toolbar: [
                            ['popovers', ['lfm']],
                            ['style', ['bold', 'italic', 'underline', 'clear']],
                            ['font', ['strikethrough', 'superscript', 'subscript']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['height', ['height']]
                        ],
                        buttons: {
                            lfm: LFMButton
                        }
                    })
                };
            }( jQuery ));
            $('#summernote').bindMultiple();
            $('#summernote_ru').bindMultiple();
        });
        ////////////////////////////////////

        function sendFile (file, editor, welEditable){
            data = new formData();
            data.append("file", file);
            $.ajax({
                url:"{{url('upload-editor-file')}}",
                data:data,
                cache: false,
                contentType: false,
                proccessData: false,
                type: 'post',
                success: function(data) {
                    editor.insertImage(welEditable, data.image);
                }
            });
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        @if(isset($data->name_en))
        $('.fileinput-remove-button').click(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({

                method: 'POST',
                url: "/{{App::getLocale()}}/adminstration/transport/removeimage",
                data:{"image": "{{$data->image}}", "id" : "{{$data->id}}"},
                success: function(response){
                }
            });
        });
        @endif
    </script>
@endsection
