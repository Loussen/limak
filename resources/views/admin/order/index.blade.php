@extends('layouts.admin')
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
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div style="width: 100%;" class="container">
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Sifarişlər</strong></h3>
                                <br>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body no-padding">
                                <table class="table table-hovered table-striped">
                                    <thead>
                                    <tr>
                                        <td colspan="6">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link {{empty(\Illuminate\Support\Facades\Input::get('type')) ? 'active' : ''}}" href="{{url('/admin/order/list')}}">Hamısı</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link {{!empty(\Illuminate\Support\Facades\Input::get('type')) && \Illuminate\Support\Facades\Input::get('type') === 'waiting' ? 'active' : ''}}" href="{{url('/admin/order/list?type=waiting')}}">Gözləyir</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link {{!empty(\Illuminate\Support\Facades\Input::get('type')) && \Illuminate\Support\Facades\Input::get('type') === 'ordered' ? 'active' : ''}}" href="{{url('/admin/order/list?type=ordered')}}">Sifariş verildi</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link {{!empty(\Illuminate\Support\Facades\Input::get('type')) && \Illuminate\Support\Facades\Input::get('type') === 'rejection' ? 'active' : ''}}" href="{{url('/admin/order/list?type=rejection')}}">Çatışmazlıq</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link {{!empty(\Illuminate\Support\Facades\Input::get('type')) && \Illuminate\Support\Facades\Input::get('type') === 'transaction' ? 'active' : ''}}" href="{{url('/admin/order/list?type=transaction')}}">İmtina edilmiş</a>
                                                </li>

                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>№</th>
                                        <th>Sifarişin linki</th>
                                        <th>Qeyd</th>
                                        <th>Sifariş tarixi</th>
                                        <th>Status</th>
                                        <th>Qiymət</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($incr = 0)
                                    @foreach($response as $order)
                                        @foreach($order->products as $product)
                                            <tr>
                                                <td>{{++$incr}}</td>
                                                <td>
                                                    @if($product->extras->link)
                                                    <a style="color: #3f51b5!important;" href="{{$product->extras->link}}" target="_blank"><strong>Keçid</strong></a>
                                                    @endif
                                                </td>
                                                <td>{{$product->description}}</td>
                                                <td>{{date('d.m.Y H:i' , strtotime($product->created_at))}}</td>
                                                <td>{{$order->is_ordered == 1 && $product->statuses->id != 6?'Sifariş verildi': $product->statuses->name}}</td>
                                                <td>{{$product->price}}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
