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
                    <div class="col-md-6 text-left">
                        <div>
                            <form id="find_by_tel" method="get">
                                <div class="form-group has-right-icon m-0 focused">
                                    <input id="user_code" name="user_code" class="form-control light user_code_all"
                                           placeholder="İstifadəçi kodu ilə" type="text">
                                    <i class="icon-search"></i>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-success" data-toggle="modal" data-target="#addInvoice"> + Bəyənnamə əlavə
                            et
                        </button>
                        <button class="btn btn-info" data-target="#addInvoice"> + Hamısını göndər</button>
                    </div>
                </div>
                <div style="display: none;" id="search_second" class="row">
                    <div class="col-md-6 text-left">
                        <div>
                            <form id="find_by_tel" method="get">
                                <div class="form-group has-right-icon m-0 focused">
                                    <input id="user_codeRoad" name="user_code" class="form-control light user_code_all"
                                           placeholder="İstifadəçi kodu ilə" type="text">
                                    <i class="icon-search"></i>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-success" data-toggle="modal" data-target="#addInvoice"> + Bəyənnamə əlavə
                            et
                        </button>
                    </div>
                </div>
                @if($label === 'foreign')
                    <div style="display: none;" id="search_three" class="row">
                        <div class="col-md-6 text-left">
                            <div>
                                <form id="find_by_tel" method="get">
                                    <div class="form-group has-right-icon m-0 focused">
                                        <input id="user_codeWaiting" name="user_code" class="form-control light user_code_all"
                                               placeholder="İstifadəçi kodu ilə" type="text">
                                        <i class="icon-search"></i>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-success" data-toggle="modal" data-target="#addInvoice"> + Bəyənnamə
                                əlavə et
                            </button>
                        </div>
                    </div>
                @endif
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card no-b">
                            <div class="card-header white pb-0">
                                <div class="d-flex justify-content-between">
                                    <div class="align-self-center">
                                        <ul class="nav nav-pills mb-3" role="tablist">
                                            @if($label === 'foreign' || $label === 'super_admin')
                                                <li class="nav-item">
                                                    <a class="nav-link r-20" id="w3--tab4" data-toggle="tab"
                                                       href="#w3-tab4" role="tab" aria-controls="tab4"
                                                       aria-selected="false">e-Faturasi olmayanlar</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link r-20" id="w3--tab3" data-toggle="tab"
                                                       href="#w3-tab3" role="tab" aria-controls="tab3"
                                                       aria-selected="false">e-faturasi olanlar</a>
                                                </li>
                                            @endif
                                            <li class="nav-item">
                                                <a class="nav-link active show r-20" id="w3--tab1" data-toggle="tab"
                                                   href="#w3-tab1" role="tab" aria-controls="tab1" aria-expanded="true"
                                                   aria-selected="true">Anbardadır</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link r-20" id="w3--tab2" data-toggle="tab" href="#w3-tab2"
                                                   role="tab" aria-controls="tab2" aria-selected="false">Yoldadır</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="align-self-center">
                                        <button id="openModalInsuff" class="btn btn-info"><i style="margin-right:10px"
                                                                                             class="icon-warning"> </i>
                                            Çatışmazlıq barədə bildiriş göndər
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <div class="card-body no-p">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="w3-tab1" role="tabpanel"
                                         aria-labelledby="w3-tab1">
                                        <div class="table-responsive">
                                            <table class="table tableStorage table-hover">
                                                <tbody>
                                                @if(count($dataStorage) === 0)
                                                    <tr colspan="7">
                                                        <td style="font-size: 22px;font-weight: bold;color: #04a8f457;"
                                                            class="text-center">İnvoys yoxdur
                                                        </td>
                                                    </tr>
                                                @endif
                                                @foreach($dataStorage as $value)

                                                    <tr class="no-b">
                                                        <td style="width: 17%;" class="table-img">
                                                            @if($value->relUserProducts)
                                                                <h5 class="font-weight-bold">{{$value->relUserProducts->users->name}} {{$value->relUserProducts->users->surname}} {{$value->relUserProducts->users->uniqid}}</h5>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($value->relUserProducts)
                                                                <i class="icon-email"></i>
                                                                <span>{{$value->relUserProducts->users->email}} </span>
                                                                <br>

                                                                @if($value->relUserProducts->users->userContacts)
                                                                    @foreach($value->relUserProducts->users->userContacts as $contact)
                                                                        <i class="icon-phone"></i>
                                                                        <span> {{$contact->name}}</span>
                                                                        <br>
                                                                    @endforeach
                                                                @endif
                                                                <i class="icon-address-card"></i>
                                                                <span> {{$value->relUserProducts->users->pin}}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <h6>Purchase No</h6>
                                                            <span>{{$value->purchase_no}}</span>
                                                        </td>
                                                        <td>
                                                            <h6>Mağaza</h6>
                                                            <span>{{$value->products->shop_name}}</span>
                                                        </td>
                                                        <td>
                                                            <h6>Məhsulun tipi</h6>
                                                            <span>{{!empty($value->products->productTypes) ? $value->products->productTypes->name: $value->products->product_type_name}}</span>
                                                        </td>
                                                        <td>
                                                            <h6>Məhsulun sayı</h6>
                                                            <span>{{$value->products->quantity}}</span>

                                                        </td>
                                                        <td>
                                                            <h6>Qiymət</h6>
                                                            <div style="font-size: 16px;color: #56b759;font-weight: bold;"
                                                                 class="d-none d-lg-block">{{$value->products->price}}</div>
                                                        </td>
                                                        <td>
                                                            <div class="d-none d-lg-block">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-none d-lg-block">
                                                                <span style="color: #4CAF50;font-weight: bold;"><i
                                                                            class="icon icon-data_usage"></i> {{$value->invoiceStatus->name}}</span><br>
                                                                <span><i class="icon icon-timer"></i> {{$value->updated_at}}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-outline-warning"
                                                                    id="updateDimAndWeight"
                                                                    data-id="{{$value->id}}">
                                                                <i style="padding-right: 0"
                                                                   class="icon icon-update"></i>
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-outline-primary" id="printHawb"
                                                                    data-id="{{$value->id}}">
                                                                <i style="padding-right: 0"
                                                                   class="icon icon-print"></i>
                                                            </button>
                                                        </td>
                                                        <td>
                                                            @if($value->file)
                                                                <a style="cursor: pointer;" download
                                                                   href="{{url($value->file)}}"
                                                                   class="btn-fab btn-fab-sm btn-warning shadow text-white"><i
                                                                            class="icon-file"></i></a>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <a href="/admin/invoices/waybill/{{$value->id}}"
                                                               target="_blank" class="btn-info"> Waybill </a>
                                                        </td>
                                                        <td> <span class="btn btn-primary btn-file" style="position: relative; overflow: hidden">
                                                                    Invoice yukle<input class="file_upload" type="file" data-id="{{$value->id}}" data-transactionId="{{$value->relUserProducts->transactionId}}" style=" position: absolute;                                                                                                    top: 0;
                                                                                                        right: 0;
                                                                                                        min-width: 100%;
                                                                                                        min-height: 100%;
                                                                                                        font-size: 100px;
                                                                                                        text-align: right;
                                                                                                        filter: alpha(opacity=0);
                                                                                                        opacity: 0;
                                                                                                        outline: none;
                                                                                                        cursor: inherit;
                                                                                                        display: block;">
                                                                </span></td>
                                                        <td>
                                                            <a data-id="{{$value->id}}" style="cursor: pointer;"
                                                               class="submit-invoice-storage btn-fab btn-fab-sm btn-primary shadow text-white"><i
                                                                        class="icon-check"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="w3-tab2" role="tabpanel" aria-labelledby="w3-tab2">
                                        <div class="table-responsive">
                                            <table class="table tableRoad table-hover">
                                                <tbody>
                                                @if(count($dataInRoad) === 0)
                                                    <tr colspan="7">
                                                        <td style="font-size: 22px;font-weight: bold;color: #04a8f457;"
                                                            class="text-center">İnvoys yoxdur
                                                        </td>
                                                    </tr>
                                                @endif
                                                @foreach($dataInRoad as $value)
                                                    <tr class="no-b">
                                                        <td style="width: 17%;" class="table-img">
                                                            @if($value->relUserProducts)<h5
                                                                    class="font-weight-bold">{{$value->relUserProducts->users->name}} {{$value->relUserProducts->users->surname}}</h5> @endif
                                                        </td>
                                                        <td>
                                                            @if($value->relUserProducts)
                                                                <i class="icon-email"></i>
                                                                <span>{{$value->relUserProducts->users->email}} {{$value->relUserProducts->users->uniqid}}</span>
                                                                <br>

                                                                @if($value->relUserProducts->users->userContacts)
                                                                    @foreach($value->relUserProducts->users->userContacts as $contact)
                                                                        <i class="icon-phone"></i>
                                                                        <span> {{$contact->name}}</span>
                                                                        <br>
                                                                    @endforeach
                                                                @endif
                                                                <i class="icon-address-card"></i>
                                                                <span> {{$value->relUserProducts->users->pin}}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <h6>Purchase No</h6>
                                                            <span>{{$value->purchase_no}}</span>
                                                        </td>
                                                        <td>
                                                            <h6>Mağaza</h6>
                                                            <span>{{$value->products->shop_name}}</span>
                                                        </td>
                                                        <td>
                                                            <h6>Məhsulun tipi</h6>
                                                            <span>{{!empty($value->products->productTypes) ? $value->products->productTypes->name: $value->products->product_type_name}}</span>
                                                        </td>
                                                        <td>
                                                            <h6>Məhsulun sayı</h6>
                                                            <span>{{$value->products->quantity}}</span>

                                                        </td>
                                                        <td>
                                                            <h6>Qiymət</h6>
                                                            <div style="font-size: 16px;color: #56b759;font-weight: bold;"
                                                                 class="d-none d-lg-block">{{$value->products->price}}</div>
                                                        </td>
                                                        <td>
                                                            <div class="d-none d-lg-block">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-none d-lg-block">
                                                                <span style="color: #4CAF50;font-weight: bold;"><i
                                                                            class="icon icon-data_usage"></i> {{$value->invoiceStatus->name}}</span><br>
                                                                <span><i class="icon icon-timer"></i> {{$value->updated_at}}</span>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <button class="btn btn-outline-warning"
                                                                    id="updateDimAndWeight"
                                                                    data-id="{{$value->id}}">
                                                                <i style="padding-right: 0"
                                                                   class="icon icon-update"></i>
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-outline-primary" id="printHawb"
                                                                    data-id="{{$value->id}}">
                                                                <i style="padding-right: 0"
                                                                   class="icon icon-print"></i>
                                                            </button>
                                                        </td>

                                                        <td>
                                                            @if($value->file)
                                                                <a style="cursor: pointer;" download
                                                                   href="{{url($value->file)}}"
                                                                   class="btn-fab btn-fab-sm btn-warning shadow text-white"><i
                                                                            class="icon-file"></i></a>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($label !== 'foreign')
                                                                <a style="cursor: pointer;" data-id="{{$value->id}}"
                                                                   class="submit-invoice-road btn-fab btn-fab-sm btn-primary shadow text-white"><i
                                                                            class="icon-check"></i></a>
                                                            @endif
                                                        </td>
                                                        <td><button type="button">add-button</button></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @if($label === 'foreign')
                                        <div class="tab-pane fade" id="w3-tab3" role="tabpanel"
                                             aria-labelledby="w3-tab3">
                                            <div class="table-responsive">
                                                <table class="table tableWaiting table-hover">
                                                    <tbody>
                                                    @if(count($dataWaiting) === 0)
                                                        <tr colspan="7">
                                                            <td style="font-size: 22px;font-weight: bold;color: #04a8f457;"
                                                                class="text-center">İnvoys yoxdur
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @foreach($dataWaiting as $value)
                                                        <tr class="no-b">
                                                            <td style="width: 17%;" class="table-img">
                                                                @if($value->relUserProducts)<h5
                                                                        class="font-weight-bold">{{$value->relUserProducts->users->name}} {{$value->relUserProducts->users->surname}} {{$value->relUserProducts->users->uniqid}}</h5> @endif
                                                            </td>
                                                            <td>
                                                                @if($value->relUserProducts)
                                                                    <i class="icon-email"></i>
                                                                    <span>{{$value->relUserProducts->users->email}}</span>
                                                                    <br>

                                                                    @if($value->relUserProducts->users->userContacts)
                                                                        @foreach($value->relUserProducts->users->userContacts as $contact)
                                                                            <i class="icon-phone"></i>
                                                                            <span> {{$contact->name}}</span>
                                                                            <br>
                                                                        @endforeach
                                                                    @endif
                                                                    <i class="icon-address-card"></i>
                                                                    <span> {{$value->relUserProducts->users->pin}}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <h6>Purchase No</h6>
                                                                <span>{{$value->purchase_no}}</span>
                                                            </td>
                                                            <td>
                                                                <h6>Mağaza</h6>
                                                                <span>{{$value->products->shop_name}}</span>
                                                            </td>
                                                            {{--<td>
                                                                <h6>Məhsulun tipi</h6>
                                                                <span>{{!empty($value->products->productTypes) ? $value->products->productTypes->name: $value->products->product_type_name}}</span>
                                                            </td>--}}
                                                            <td>
                                                                <h6>Məhsulun sayı</h6>
                                                                <span>{{$value->products->quantity}}</span>
                                                            </td>
                                                            <td>
                                                                <h6>Qiymət</h6>
                                                                <div style="font-size: 16px;color: #56b759;font-weight: bold;"
                                                                     class="d-none d-lg-block">{{$value->products->price}}</div>
                                                            </td>
                                                            <td></td>
                                                            <td>
                                                                <div class="d-none d-lg-block">
                                                                    <span style="color: #4CAF50;font-weight: bold;"><i
                                                                                class="icon icon-data_usage"></i> {{$value->invoiceStatus->name}}</span><br>
                                                                    <span><i class="icon icon-timer"></i> {{$value->updated_at}}</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a href="/admin/invoices/waybill/{{$value->id}}"
                                                                   target="_blank" class="btn-info"> Waybill </a>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-outline-warning"
                                                                        id="updateDimAndWeight"
                                                                        data-id="{{$value->id}}">
                                                                    <i style="padding-right: 0"
                                                                       class="icon icon-update"></i>
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-outline-primary" id="printHawb"
                                                                        data-id="{{$value->id}}">
                                                                    <i style="padding-right: 0"
                                                                       class="icon icon-print"></i>
                                                                </button>
                                                            </td>

                                                            <td>
                                                                @if($value->file)
                                                                    <a style="cursor: pointer;" download
                                                                       href="{{url($value->file)}}"
                                                                       class="btn-fab btn-fab-sm btn-warning shadow text-white"><i
                                                                                class="icon-file"></i></a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a style="cursor: pointer;" data-id="{{$value->id}}"
                                                                   class="submit-invoice-waiting btn-fab btn-fab-sm btn-primary shadow text-white"><i
                                                                            class="icon-check"></i></a>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    {{-- insufficient modal--}}
    <div class="modal fade" id="insuffModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Invoys barədə çatışmazlıq bildirişi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="insuffForm">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">İstifadəçi kodu:</label>
                            <input name="user_uid" type="text" class="form-control" id="recipient-name">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Bağla</button>
                    <button id="insuffBtn" type="button" class="btn btn-primary">Göndər</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="hawbModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Çap - ətraflı</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="hawbForm" method="post">
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Ağırlık (kq)</label>
                                <input type="number" class="form-control" name="weight" placeholder="Misal: 3.40"/>
                            </div>
                            <div class="form-group col-6">
                                <label>Uzunluk (sm)</label>
                                <input type="number" class="form-control" name="length" placeholder="Misal: 3.40"/>
                            </div>
                            <div class="form-group col-6">
                                <label>Genişlik (sm)</label>
                                <input type="number" class="form-control" name="width" placeholder="Misal: 5.40"/>
                            </div>
                            <div class="form-group col-6">
                                <label>Yükseklik (sm)</label>
                                <input type="number" class="form-control" name="height" placeholder="Misal: 14.40"/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Bağla</button>
                    <button id="hawbBtn" type="button" class="btn btn-primary">Gönder</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="hawbUpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Çap - ətraflı</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="hawbUpdateForm" method="post">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Bağla</button>
                    <button id="hawbUpdateBtn" type="button" class="btn btn-primary">Gönder</button>
                </div>
            </div>
        </div>
    </div>

    <div style="opacity: 0; height: 0;" id="hawbData">
    </div>

    <div class="modal fade" id="addInvoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Invoys yüklə</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="invoice-form" action="/admin/invoice" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="shop">Istifadeci kodu</label>
                                            <input type="text" name="user_code" class="form-control user_code_all" id="shop"
                                                   placeholder="Istifadeci kodu" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="file" class="form-group">
                                                <span id="fileName"></span>
                                                <span><img src="{{asset('front/img/beyenname-yukle.png')}}"
                                                           alt="beyanname-yukle"> @lang('invoice/index.file')</span>
                                                <input required type="file" class="form-control-file" id="file"
                                                       name="invoice">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">

                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="shop">@lang('invoice/index.shop')</label>
                                            <input type="text" name="shop_name" class="form-control" id="shop"
                                                   placeholder="@lang('invoice/index.shop')" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="tip">@lang('invoice/index.product-type')</label>
                                            <select class="selectpicker form-control" name="product_type_id" id="tip"
                                                    title="@lang('invoice/index.product-type')" required>
                                                @foreach($productTypes as $productType)
                                                    <option value="{{$productType->id}}">{{$productType->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="count">@lang('invoice/index.count')</label>
                                            <input type="number" class="form-control" name="quantity" id="count"
                                                   required
                                                   placeholder="@lang('invoice/index.count')">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="value">@lang('invoice/index.product-price') (TL)</label>
                                            <input type="number" class="form-control" id="value" name="price" required
                                                   placeholder="@lang('invoice/index.product-price')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="count">@lang('invoice/index.orderNumber')</label>
                                            <input type="text" class="form-control" name="order_number" id="orderNumber"
                                                   required
                                                   placeholder="@lang('invoice/index.orderNumber')">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="value">@lang('invoice/index.orderDate')</label>
                                            <input type="date" class="form-control" id="orderDate" name="order_date"
                                                   required placeholder="@lang('invoice/index.orderDate')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Bağla</button>
                    <button id="saveInvoice" type="button" class="btn btn-primary">Gönder</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('js/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('front/js/hawb.js?v=').time()}}"></script>
    <script>
        var tab = 'storage';
        var table = 'tableStorage';
        var latestSearch = false;
        var defaultSearch = function () {
            $.ajax({
                'type': 'GET',
                url: "{{url('/admin/invoices')}}?tab=" + tab,
                success: function ($response) {
                    $('.' + table).html($response);
                }
            });
            blickCheck();
        }
        $('.search-container').find('input').each(function () {
            $(this).on('keyup', function (e) {
                if (e.keyCode === 13) {
                    console.log('key press');
                    $('#searchForm').submit();
                }
            });
        });
        var getStorageData = function () {
            $.ajax({
                'type': 'GET',
                url: "{{url('/admin/invoices')}}?tab=storage&user_code=" + $('#user_code').val(),
                success: function ($response) {
                    if ($response.length === 17) {
                        $('.tableStorage').html('<tr colspan="7" ><td style="font-size: 22px;font-weight: bold;color: #04a8f457;" class="text-center" >İnvoys yoxdur</td></tr>');
                    } else {
                        $('.tableStorage').html(`<tbody>` + $response + `</tbody>`);
                    }
                }
            });
            blickCheck();
        };
        getRoadData = function () {
            $.ajax({
                'type': 'GET',
                url: "{{url('/admin/invoices')}}?tab=road&user_code=" + $('#user_codeRoad').val(),
                success: function ($response) {
                    if ($response.length === 17) {
                        $('.tableRoad').html('<tr colspan="7" ><td style="font-size: 22px;font-weight: bold;color: #04a8f457;" class="text-center" >İnvoys yoxdur</td></tr>');
                    } else {
                        $('.tableRoad').html(`<tbody>` + $response + `</tbody>`);
                    }
                }
            });
            blickCheck();
        };
        getWaitingData = function (invoice) {
            $.ajax({
                'type': 'GET',
                url: "{{url('/admin/invoices')}}?invoice=" + invoice + "&tab=waiting&user_code=" + $('#user_codeWaiting').val(),
                success: function ($response) {
                    if ($response.length === 17) {
                        $('.tableWaiting').html('<tr colspan="7" ><td style="font-size: 22px;font-weight: bold;color: #04a8f457;" class="text-center" >İnvoys yoxdur</td></tr>');
                    } else {
                        $('.tableWaiting').html(`<tbody>` + $response + `</tbody>`);
                    }
                }
            });
            blickCheck();
        };
        $('#pin').keyup(delay(function (e) {
            e.preventDefault();
            getStorageData();
            latestSearch = getStorageData;

        }, 200));
        $('#tel').keyup(delay(function (e) {
            e.preventDefault();
            getStorageData();
            latestSearch = getStorageData;
        }, 200));
        $('#user_code').keyup(delay(function (e) {
            e.preventDefault();
            getStorageData();
            latestSearch = getStorageData;
        }, 200));
        $('#pinRoad').keyup(delay(function (e) {
            e.preventDefault();
            getRoadData();
            latestSearch = getRoadData;
        }, 200));

        $('#telRoad').keyup(delay(function (e) {
            e.preventDefault();
            getRoadData();
            latestSearch = getRoadData;
        }, 200));
        $('#user_codeRoad').keyup(delay(function (e) {
            e.preventDefault();
            getRoadData();
            latestSearch = getRoadData;
        }, 200));
        @if($label === 'foreign')
        $('#pinWaiting').keyup(delay(function (e) {
            e.preventDefault();
            getWaitingData(1);
            latestSearch = getWaitingData;

        }, 200));

        $('#telWaiting').keyup(delay(function (e) {
            e.preventDefault();
            getWaitingData(1);
            latestSearch = getWaitingData;
        }, 200));
        $('#user_codeWaiting').keyup(delay(function (e) {
            e.preventDefault();
            getWaitingData(1);
            latestSearch = getWaitingData;
        }, 200));

        @endif

        function delay(callback, ms) {
            var timer = 0;
            return function () {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        $('#w3--tab1').click(function () {
            getStorageData();
            tab = 'storage';
            table = 'tableStorage';
            $('#search_first').css('display', 'flex');
            $('#search_second').css('display', 'none');
            @if($label === 'foreign')
            $('#search_three').css('display', 'none');
            @endif
        });
        $('#w3--tab2').click(function () {
            getRoadData();
            tab = 'road';
            table = 'tableRoad';
            $('#search_first').css('display', 'none');
            $('#search_second').css('display', 'flex');
            @if($label === 'foreign')
            $('#search_three').css('display', 'none');
            @endif
        });
        @if($label === 'foreign')
        $('#w3--tab3').click(function () {
            getWaitingData(1);
            tab = 'waiting';
            table = 'tableWaiting';
            $('#search_first').css('display', 'none');
            $('#search_second').css('display', 'none');
            $('#search_three').css('display', 'flex');
        });
        $('#w3--tab4').click(function () {
            getWaitingData(0);
            tab = 'waiting';
            table = 'tableWaiting';
            $('#search_first').css('display', 'none');
            $('#search_second').css('display', 'none');
            $('#search_three').css('display', 'flex');
        });

        @endif

        function blickCheck() {
            @if($label === 'foreign')
            $('.submit-invoice-storage').unbind('click');
            $('.submit-invoice-road').unbind('click');

            $(document).on('click', '.submit-invoice-storage', function () {
                $id = $(this).attr('data-id');
                swal({
                    title: 'Əminsizmi?',
                    text: "seçilmiş invoys doğrudurmu!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Təsdiqlə'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: '{{url("/admin/invoices/check-storage")}}',
                            data: {id: $id},
                            success: function (response) {
                                if (latestSearch) {
                                    latestSearch();
                                } else {
                                    defaultSearch();
                                }
                            }
                        });
                        swal(
                            'Təsdiqləndi!',
                            'seçilmiş invoys sonrakı mərhələyə əlavə edildi',
                            'success'
                        )
                    }
                })
            });
            $(document).on('click', '.submit-invoice-waiting', function () {
                $id = $(this).attr('data-id');
                swal({
                    title: 'Əminsizmi?',
                    text: "seçilmiş invoys doğrudurmu!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Təsdiqlə'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: '{{url("/admin/invoices/check-waiting")}}',
                            data: {id: $id},
                            success: function (response) {
                                if (latestSearch) {
                                    latestSearch();
                                } else {
                                    defaultSearch();
                                }
                            }
                        });
                        swal(
                            'Təsdiqləndi!',
                            'seçilmiş invoys sonrakı mərhələyə əlavə edildi',
                            'success'
                        )
                    }
                })
            });
            @else
            $('.submit-invoice-storage').unbind('click');
            $('.submit-invoice-road').unbind('click');

            $(document).on('click', '.submit-invoice-storage', function () {
                $id = $(this).attr('data-id');
                swal({
                    title: 'Əminsizmi?',
                    text: "seçilmiş invoys doğrudurmu!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Təsdiqlə'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: '{{url("/admin/invoices/check-native-storage")}}',
                            data: {id: $id},
                            success: function (response) {
                                if (latestSearch) {
                                    latestSearch();
                                } else {
                                    defaultSearch();
                                }
                            }
                        });
                        swal(
                            'Təsdiqləndi!',
                            'seçilmiş invoys sonrakı mərhələyə əlavə edildi',
                            'success'
                        )
                    }
                })
            });
            $(document).on('click', '.submit-invoice-road', function () {
                $id = $(this).attr('data-id');
                swal({
                    title: 'Əminsizmi?',
                    text: "seçilmiş invoys doğrudurmu!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Təsdiqlə'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: '{{url("/admin/invoices/check-road")}}',
                            data: {id: $id},
                            success: function (response) {
                                if (latestSearch) {
                                    latestSearch();
                                } else {
                                    defaultSearch();
                                }
                            }
                        });
                        swal(
                            'Təsdiqləndi!',
                            'seçilmiş invoys sonrakı mərhələyə əlavə edildi',
                            'success'
                        )
                    }
                })
            });
            @endif
        }
        
        function uploadInvoice(id, transactionId) {
            // console.log($(this));
            // let formData = new FormData();
            // formData.append('file', e.target.files[0]);
            // formData.append('productId', id);
            // formData.append('transactionId', transactionId);

        }
        $('.file_upload').on('change',function () {
            var self = $(this);
            var formData = new FormData();
            formData.append('file', $(this)[0].files[0]);
            formData.append('invoiceId', self.attr('data-id'));
            formData.append('transactionId', self.attr('data-transactionId'));
            // console.log($(this)[0].files[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                method: "POST",
                url: '/admin/order/invoice-upload_new',
                data: formData,
                processData: false,
                contentType: false,
                success: function (result) {

                }
            });
        });
        blickCheck();

        $('#openModalInsuff').click(function () {
            $('#insuffModal').modal('show');
        });
        $("#insuffBtn").click(function () {
            var formData = $("#insuffForm").serializeArray();
            $.ajax({
                'type': 'POST',
                'url': '{{url("/admin/invoices/send-invoiceless-message")}}',
                'data': formData,
                'success': function (response) {
                    $("#insuffForm")[0].reset();
                    $('#insuffModal').modal('hide');
                    swal('Göndərildi!')
                }
            });
        });


        $('#invoice-form').validate({
            messages: {
                shop_name: $.validator.messages.shop_name,
                product_type_id: $.validator.messages.product_type_id,
                quantity: $.validator.messages.quantity,
                price: $.validator.messages.price,
                invoice: $.validator.messages.invoice,
                order_number: $.validator.messages.order_number,
                order_date: $.validator.messages.order_date,
            }
        });

        $("#saveInvoice").click(function () {
            var $invoiceForm = $('#invoice-form');

            if ($invoiceForm.valid()) {
                var frmData = new FormData();
                var formData = $invoiceForm.serializeArray();
                var invoice = $('#file')[0].files[0];

                $.each(formData, function (_, kv) {
                    frmData.append(kv.name, kv.value);
                });

                frmData.append('invoice', invoice);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    method: "POST",
                    url: '/admin/invoices',
                    data: frmData,
                    processData: false,
                    contentType: false,
                    success: function (result) {
                        if (result.status === 200) {
                            $("#invoice-success").fadeTo(2000, 500).text(result.message).fadeOut(500, function () {
                                $("#invoice-success").fadeOut(500);
                            });
                            $("#fileName").text("");
                            $('#invoice-form').find("input[type=text], textarea, input[type=file], input[type=number]").val("");
                        }
                    }
                });
            }
        });

        $("#file").change(function () {
            var invoice = $(this)[0].files[0];

            $("#fileName").text(invoice.name);
        });

        $('.user_code_all').keyup(function(){
            var value = $(this).val();
            $('.user_code_all').val(value);
        })
    </script>
@endsection
