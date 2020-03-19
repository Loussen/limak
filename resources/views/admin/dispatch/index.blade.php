<?php
use Illuminate\Support\Facades\Session;
?>
@extends('layouts/admin')
@section('styles')
    <link href="{{asset('js/sweetalert/sweetalert2.min.css')}}"/>
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
        td h6 {
            color: #101010;
        }
        table.table tbody td {
            padding: 24px !important;
            border-bottom: 1px solid #59b35e38 !important;
        }
        input.light::placeholder {
            color: rgba(0, 0, 0, 0.67) !important;
        }
    </style>
@endsection
@section('content')

    <div class="content-wrapper relative animatedParent animateOnce">
        <section class="paper-card">
            <div class="animated fadeInUpShort">
                <div id="search_first" class="row">
                    <div class="col-md-2 text-left">
                        <div>
                            <h1>Sevkiyat</h1>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-success" data-toggle="modal" data-target="#addInvoice"> + əlavə et </button>
                    </div>
                </div>
            </div>
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card no-b">
                            <div class="card-body no-p">
                                <div class="table-responsive">
                                    <table class="table tableStorage table-hover">
                                        <thead>
                                        <tr>
                                            <th> ID</th>
                                            <th> Konşimento no</th>
                                            <th> Toplam fiyat</th>
                                            <th> Gönderim tarihi</th>
                                            <th> ETGB</th>
                                            <th> ETGB tarihi</th>
                                            <th> Toplam ağırlık</th>
                                            <th> Çuval ekle</th>
                                            <th> Çuval sayı</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data as $item)
                                            <tr class="no-b">
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->consignment }}</td>
                                                <td>{{ $item->total_price }}</td>
                                                <td>{{ $item->dispatch_date }}</td>
                                                <td>{{ $item->etgb }}</td>
                                                <td>{{ $item->etgb_date }}</td>
                                                <td>{{ $item->total_weight }}</td>
                                                <td>
                                                    <form action="/admin/dispatch/addSack" method="post" enctype="multipart/form-data">
                                                        <input type="text" name="invoice_no" class="form-control invoice_no col-6"  placeholder="Çuval numarasi"/>
                                                        <input type="hidden" name="dispatch_id" value="{{ $item->id }}">
                                                        <input class="btn-info col-6" type="submit" value="ekle">
                                                    </form>
                                                </td>
                                                <td>
                                                    <a href="/admin/sack?dispatch_id={{ $item->id }}"> {{ $item->count }}</a></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="addInvoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="invoice-form" action="/admin/dispatch" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Çuval əlavə et</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="shop">Konşimento no</label>
                                <input type="text" name="consignment" class="form-control" id="code" placeholder="Konşimento no" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="shop">Toplam fiyat</label>
                                <input type="text" name="total_price" class="form-control" id="code" placeholder="Toplam fiyat" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="shop">ETGB</label>
                                <input type="text" name="etgb" class="form-control" id="code" placeholder="ETGB" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="shop">ETGB tarihi</label>
                                <input type="date" name="etgb_date" class="form-control" id="code" placeholder="ETGB tarihi" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="shop">Gönderim tarihi</label>
                                <input type="date" name="dispatch_date" class="form-control" id="code" placeholder="Gönderim tarihi" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="shop">Toplam ağırlık</label>
                                <input type="text" name="total_weight" class="form-control" id="code" placeholder="Gönderim tarihi" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-secondary" data-dismiss="modal">Bağla</a>
                    <button id="saveInvoice" type="submit" class="btn btn-primary">Gönder</button>
                </div>
            </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    $("#saveSack").click(function () {
        var $invoiceForm = $('#sack-form');
        var formData = $invoiceForm.serializeArray();


            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                method: "POST",
                url: '/admin/invoices',
                data: formData,
                processData: false,
                contentType: false,
                success: function (result) {
                    if (result.status === 200) {

                    }
                }
            });
        }
    });
</script>
@endsection
