@extends('layouts/admin')
@section('styles')
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
        }php
        .own-error {
            color: red;
        }

    </style>
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div class="container">
            <div class="card">
                <div class="card-body b-b">
                    <h4>Ən çox verilən suallar</h4>
                    <form id="form" method="POST"  action="/{{App::getLocale()}}/admin/faq{{!is_null($data)?"/".$id:""}}" enctype="multipart/form-data">
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
                                        <label for="shopName" class="col-form-label">Sual</label>
                                        <input required name="question[az]" type="text" class="form-control" id="question-az" value="{{!is_null($data) ? $data->translates[0]->question : ''}}" placeholder="Necə sifariş edim?">
                                    </div>
                                    <div class="form-group">
                                        <label for="shopUrl" class="col-form-label">Cavab</label>
                                        <textarea required rows="5"  required class="ckeditor" id="ckeditor"  name="answer[az]">{{!is_null($data) ? $data->translates[0]->answer : ''}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="w3-tab2" role="tabpanel" aria-labelledby="w3-tab2">
                                <div class="table-responsive">
                                    <div class="form-group">
                                        <label for="shopName" class="col-form-label">Sual</label>
                                        <input required name="question[ru]" type="text" class="form-control" id="question-ru" value="{{!is_null($data) && count($data->translates) >= 2  ? $data->translates[1]->question : ''}}" placeholder="Sifarişinizi rahat şəkildə edin">
                                    </div>
                                    <div class="form-group">
                                        <label for="shopUrl" class="col-form-label">Cavab</label>
                                        <textarea required rows="5"  required class="ckeditor" id="ckeditor" name="answer[ru]">{{!is_null($data) && count($data->translates) >= 2  ? $data->translates[1]->answer : ''}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button id="sbmt" type="submit" class="btn btn-success">Təsdiqlə</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $("#sbmt").click(function (e) {
            e.preventDefault();
            var submit = 'test';
            $('#form :input').each(function (asd) {
                $this = $(this);
                $result = '<label class="own-error">Xana boş ola bilməz</label>';
                if ($this[0].hasAttribute('required')) {
                    if (!$this.val()) {
                        submit = false;
                        if ($this.parent().find('.own-error').length === 0) {
                            $this.parent().append($result);
                        }
                    } else {
                        if (submit !== false) {
                            submit = true;
                        }
                        $this.parent().find('.own-error').remove();
                    }
                }
            });

            if ($("#question-az").val() !== '' && $("#answer-az").val() !== '') {
                $("#w3--tab2").trigger('click');
            } else {
            }

            if (submit) {
                $('#form').submit();
            }
        });

    </script>
@endsection
