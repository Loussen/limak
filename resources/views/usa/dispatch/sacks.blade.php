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
                            <h1>Paketlər</h1>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= URL::previous()?>" class="btn btn-success">&laquo; geri</a>
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
                                        <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <th>Çuval nömrəsi</th>
                                            <th>Çuval</th>
                                        </tr>
                                        @if(count($data)==0)
                                            <p class="alert alert-danger">Çuval əlavə edilməyib</p>
                                        @endif
                                        @foreach($data as $item)
                                            <tr class="no-b">
                                                <td> ID: {{ $item->sack_id }}</td>
                                                <td> {{ $item->invoice_no }}</td>
                                                <td>
                                                    {{ $item->sack_id }}
                                                </td>

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

@endsection


