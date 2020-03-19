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
                                <h3 class="box-title"><strong>İmtina və çatışmazlıq loqları</strong></h3>
                                <br>
                            </div>
                            <div class="box-body no-padding">
                                <table class="table table-striped">
                                    <tbody>
                                    <tr>
                                        <th >#</th>
                                        <th>Müştəri</th>
                                        <th>Əlaqə</th>
                                        <th>Admindən</th>
                                        <th>Adminə</th>
                                        <th>tipi</th>
                                        <th>Mağaza</th>
                                        <th>Qiymət</th>
                                        <th>Status</th>
                                        <th>Qeyd</th>
                                        <th>Tarix</th>
                                    </tr>
                                    @php($increment = 1)
                                    @foreach($data as $key => $value)
                                    @if($value->product_id)
                                        <tr>
                                            <td style="font-weight: bold;">{{$increment++}}</td>
                                            <td style="font-weight: bold;">{{$value->userName}} {{$value->userSurname}}</td>
                                            <td style="font-weight: bold;">{{$value->email}}<br>{{$value->phone}}</td>
                                            <td style="font-weight: bold;">{{$value->fromName}} {{$value->fromSurname}}</td>
                                            <td style="font-weight: bold;">{{$value->toName}} {{$value->toSurname}}</td>
                                            <td style="font-weight: bold;">{{$value->productTypeName? $value->productTypeName : $value->product_type_name}}</td>
                                            <td style="font-weight: bold;">{{$value->shopName}}</td>
                                            <td style="font-weight: bold;">{{$value->productPrice}}</td>
                                            <td style="font-weight: bold;"><span style="font-size:15px" class="badge badge-danger">Çatışmazlıq</span></td>
                                            <td style="font-weight: bold;">{{$value->note}}</td>
                                            <td style="font-weight: bold;">{{$value->updated_at}}</td>
                                        </tr>
                                    @endif
                                    @if($value->rel_user_product_id)
                                        <tr>
                                            <td style="font-weight: bold;">{{$increment++}}</td>
                                            <td style="font-weight: bold;">{{$value->rel_userName}} {{$value->rel_userSurname}}</td>
                                            <td style="font-weight: bold;">{{$value->rel_email}}<br>{{$value->rel_phone}}</td>
                                            <td style="font-weight: bold;">{{$value->fromName}} {{$value->fromSurname}}</td>
                                            <td style="font-weight: bold;">{{$value->toName}} {{$value->toSurname}}</td>
                                            <td style="font-weight: bold;">{{$value->rel_productTypeName? $value->rel_productTypeName : $value->relProductTypeName}}</td>
                                            <td style="font-weight: bold;">{{$value->rel_shopName}}</td>
                                            <td style="font-weight: bold;">{{$value->rel_productPrice}}</td>
                                            <td style="font-weight: bold;"><span style="font-size:15px" class="badge badge-primary">İmtina</span></td>
                                            <td style="font-weight: bold;">{{$value->note}}</td>
                                            <td style="font-weight: bold;">{{$value->updated_at}}</td>
                                        </tr>
                                    @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                {{ $data->appends($params)->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.search-container').find('input').each(function() {
            $(this).on('keyup', function(e){
                if(e.keyCode === 13)
                {
                    console.log('key press');
                    $('#searchForm').submit();
                }
            });
        });
    </script>
@endsection
