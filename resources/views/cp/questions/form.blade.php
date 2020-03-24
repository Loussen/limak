<?PHP
use Illuminate\Support\Facades\Session;
?>

@extends('cp.layouts.admin')
@section('styles')

    <link type="text/css" rel="stylesheet" href="{{asset('admin/css/form_elements.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('admin/js/summernote/css/summernote.css')}}" />
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
    <h4>SUAL ƏLAVƏ ET</h4>
    <ol class="breadcrumb">
        <li><a href="/cp"><i class="fa fa-home"></i>Ana səhifə</a></li>
        <li><a href="/cp/questions"><i class="fa fa-home"></i>Suallar</a></li>
        <li class="active">Suallar</li>
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
                    <h4>Xəbərlər</h4>
                    <form method="POST"  action="/{{App::getLocale()}}/cp/questions{{!is_null($data)?"/".$id:""}}" enctype="multipart/form-data">
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
                                                <li class="nav-item">
                                                    <a class="nav-link r-20" id="w3--tab2" data-toggle="tab" href="#w3-tab3" role="tab" aria-controls="tab2" aria-selected="false">EN</a>
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
                                                    <label for="shopName" class="col-form-label">Sual</label>
                                                    <input name="value[az]" type="text" class="form-control" id="shopName" value="{{!is_null($data) && isset($data->translates[0]->value) ? $data->translates[0]->value : ''}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="shopUrl" class="col-form-label">Cavab</label>
                                                    <textarea required class="ckeditor" id="ckeditor" name="answer[az]">{{!is_null($data) && isset($data->translates[0]->answer) ? $data->translates[0]->answer : ''}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="w3-tab2" role="tabpanel" aria-labelledby="w3-tab2">
                                            <div class="table-responsive">
                                                <div class="form-group">
                                                    <label for="shopName" class="col-form-label">Sual</label>
                                                    <input name="value[ru]" type="text" class="form-control" id="shopName" value="{{!is_null($data) && isset($data->translates[1]->value) ? $data->translates[1]->value : ''}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="shopUrl" class="col-form-label">Cavab</label>
                                                    <textarea class="ckeditor" id="ckeditor2" name="answer[ru]">{{!is_null($data) && isset($data->translates[1]->answer) ? $data->translates[1]->answer : ''}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="w3-tab3" role="tabpanel" aria-labelledby="w3-tab3">
                                            <div class="table-responsive">
                                                <div class="form-group">
                                                    <label for="shopName" class="col-form-label">Sual</label>
                                                    <input name="value[en]" type="text" class="form-control" id="shopName" value="{{!is_null($data) && isset($data->translates[2]->value) ? $data->translates[2]->value : ''}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="shopUrl" class="col-form-label">Xəbər</label>
                                                    <textarea class="ckeditor" id="ckeditor3" name="answer[en]">{{!is_null($data) && isset($data->translates[2]->answer) ? $data->translates[2]->answer : ''}}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="shopName" class="col-form-label">Step</label>
                                            <input name="step" type="text" class="form-control" id="shopName" value="{{!is_null($data) && isset($data->step) ? $data->step : 1}}">
                                        </div>
                                    </div>
                                </div>
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
    <script type="text/javascript" src="{{asset('admin/js/summernote/js/summernote.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/text_editor/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript">
        CKEDITOR.replace( 'ckeditor ckeditor2 ckeditor3', {
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
