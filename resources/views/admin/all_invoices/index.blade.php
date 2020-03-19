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
            {{--<a class="btn btn-primary" style="margin-bottom: 20px;" href="{{route('faq.create')}}">Sual-Cavab əlavə et</a>--}}
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Bütün İnvoyslar</strong></h3>
                                <br>
                            </div>
                            <div class="box-body no-padding">
                                <table class="table table-striped">
                                    <tbody>
                                    <tr>
                                        <th >#</th>
                                        <th>Müştəri</th>
                                        <th>Əlaqə</th>
                                        <th>İzləmə nömrəsi</th>
                                        <th>Tipi</th>
                                        <th>Mağaza</th>
                                        <th>Miqdar</th>
                                        <th>Qiymət</th>
                                        <th>Status</th>
                                        <th>İnvoys (Sənəd)</th>
                                    </tr>
                                    {{--<tr class="search-container">--}}
                                        {{--<form action="" method="get" id="searchForm">--}}
                                            {{--<input name="search" value="true" type="hidden" class="submitClass widthFull form-control" type="text" />--}}
                                            {{--<th>--}}
                                                {{--<div class="input-group focused">--}}
                                                    {{--<input name="id" value="{{isset($params['id'])?$params['id']:''}}" class="form-control r-0" type="text" placeholder="id">--}}
                                                {{--</div>--}}
                                            {{--</th>--}}
                                            {{--<th>--}}
                                                {{--<div class="input-group focused">--}}
                                                    {{--<input name="question" value="{{isset($params['question'])?$params['question']:''}}" class="form-control r-0" type="text" placeholder="Mağazanın adı">--}}
                                                {{--</div>--}}
                                            {{--</th>--}}
                                            {{--<th>--}}
                                                {{--<div class="input-group focused">--}}
                                                    {{--<input name="answer" value="{{isset($params['answer'])?$params['answer']:''}}" class="form-control r-0" type="text" placeholder="Mağazanın logosu">--}}
                                                {{--</div>--}}
                                            {{--</th>--}}
                                            {{--<th></th>--}}
                                        {{--</form>--}}
                                    {{--</tr>--}}
                                    @foreach($data as $key => $value)
                                        <tr>
                                            <td style="font-weight: bold;">{{$value->id}}</td>
                                            <td style="font-weight: bold;">{{$value->relUserProducts->users->name}} {{$value->relUserProducts->users->surname}}</td>
                                            <td style="font-weight: bold;">{{$value->relUserProducts->users->email}}<br>{{$value->relUserProducts->users->userContacts[0]->name}}</td>
                                            <td style="font-weight: bold;">{{$value->purchase_no}}</td>
                                            <td style="font-weight: bold;">{{$value->products->product_type_id && !is_null($value->products->productsType)?$value->products->productsType->name : $value->products->product_type_name}}</td>
                                            <td style="font-weight: bold;">{{$value->products->shop_name}}</td>
                                            <td style="font-weight: bold;">{{$value->products->quantity}}</td>
                                            <td style="font-weight: bold;">{{$value->products->price}}</td>
                                            <td style="font-weight: bold;">{{$value->products->statuses->name}}</td>
                                            <td style="font-weight: bold;">
                                                <a style="cursor: pointer;" download  href="{{url($value->file)}}" class="btn-fab btn-fab-sm btn-warning shadow text-white"><i class="icon-file"></i></a>
                                            </td>
                                        </tr>
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
