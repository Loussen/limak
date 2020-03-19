@extends('cp.layouts.admin')
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
        .breadcrumb{
            width: 400px;
            float: right;
            display: block;
            text-align: right;
        }
        .breadcrumb li a{
            color: #86939e
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div style="width: 100%;" class="container">
{{--            <a class="btn btn-primary" style="margin-bottom: 20px;" href="{{url('shops/create-shop')}}">Mağaza əlavə et</a>--}}
            <a class="btn btn-primary" style="margin-bottom: 20px;" href="/cp/shops/create?country_id={{$params['country_id']}}">Mağaza əlavə et</a>
            <a class="btn btn-primary" style="margin-bottom: 20px;" href="/cp/shop-types/">Tiplər</a>
            <ol class="breadcrumb">
                <li><a href="/cp"><i class="fa fa-home"></i>Ana səhifə</a></li>
                <li class="active" ><a href="/cp/shops?country_id={{$params['country_id']}}">Mağazalar</a></li>
            </ol>
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Mağazalar</strong></h3>
                                <br>
                            </div>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                @foreach($country as $item)
                                    {{--<li class="nav-item">--}}
                                        {{--<a class="nav-link {{($item->id==1 ? 'active' : '')}}" id="{{$item->prefix}}-tab" data-toggle="tab" href="#{{$item->prefix}}" role="tab" aria-controls="{{$item->prefix}}" aria-selected="{{($item->id==1 ? 'true' : 'false')}}">{{$item->name}}</a>--}}
                                    {{--</li> --}}
                                    <li class="nav-item">
                                        <a class="nav-link {{$item->id==$params['country_id'] ? 'active' : ''}}"  href="/cp/shops?country_id={{$item->id}}">{{$item->name}}</a>
                                    </li>
                                @endforeach

                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tr" role="tabpanel" aria-labelledby="tr-tab">
                                    <div class="box-body no-padding">
                                        <table class="table table-striped">
                                            <tbody>
                                            <tr>
                                                <th >#</th>
                                                <th>Mağazanın adı</th>
                                                <th>Mağazanın linki</th>
                                                <th class="text-center">Mağazanın logosu</th>
                                                <th>Ətraflı</th>
                                            </tr>
                                            <tr class="search-container">
                                                <form action="" method="get" id="searchForm">
                                                    <input name="search" value="true" type="hidden" class="submitClass widthFull form-control" type="text" />
                                                    <input name="country_id" value="1" type="hidden" type="text" />
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input name="id" value="{{isset($params['id'])?$params['id']:''}}" class="form-control r-0" type="text" placeholder="id">
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input name="name" value="{{isset($params['name'])?$params['name']:''}}" class="form-control r-0" type="text" placeholder="Mağazanın adı">
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input name="link" value="{{isset($params['link'])?$params['link']:''}}" class="form-control r-0" type="text" placeholder="Mağazanın logosu">
                                                        </div>
                                                    </th>
                                                    <th></th>
                                                    <th><button type="submit" class="btn"> Axtar</button></th>
                                                </form>
                                            </tr>
                                            @foreach($data as $shop)
                                                <tr>
                                                    <td style="font-weight: bold;">{{$shop->id}}</td>
                                                    <td style="font-weight: bold;">{{$shop->name}}</td>
                                                    <td style="font-weight: bold;">{{$shop->url}}</td>
                                                    <td class="text-center" style="font-weight: bold;">
                                                        <img width="80px" src="{{ asset('storage/shops/'.$shop->id.'.png') }}"></td>
                                                        <img width="80px" src="/storage/app/shops/{{$shop->id}}.png"></td>
                                                    <td>
                                                        <a style="width: 40px; height: 38px;margin-right: 5%;" href="{{url('az/cp/shops/'.$shop->id.'/edit?country_id='.$params['country_id'])}}" class="btn btn-success btn-xs purple">
                                                            <i style="font-size: 17px;padding-top: 5px;" class="far fa-edit"></i>
                                                        </a>
                                                        <form style="float: right" action="{{route('shops.destroy',['id'=>$shop->id])}}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button style="width: 40px; height: 38px;" type="submit" class="btn btn-danger btn-xs red">
                                                                <i style="font-size: 17px;padding-top: 5px;" class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
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
                                <div class="tab-pane fade" id="us" role="tabpanel" aria-labelledby="us-tab">
                                    <div class="box-body no-padding">
                                        <table class="table table-striped">
                                            <tbody>
                                            <tr>
                                                <th >#</th>
                                                <th>Mağazanın adı</th>
                                                <th>Mağazanın linki</th>
                                                <th class="text-center">Mağazanın logosu</th>
                                                <th>Ətraflı</th>
                                            </tr>
                                            <tr class="search-container">
                                                <form action="" method="get" id="searchForm">
                                                    <input name="search" value="true" type="hidden" class="submitClass widthFull form-control" type="text" />
                                                    <input name="country_id" value="2" type="hidden" type="text" />
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input name="id" value="{{isset($params['id'])?$params['id']:''}}" class="form-control r-0" type="text" placeholder="id">
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input name="name" value="{{isset($params['name'])?$params['name']:''}}" class="form-control r-0" type="text" placeholder="Mağazanın adı">
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input name="link" value="{{isset($params['link'])?$params['link']:''}}" class="form-control r-0" type="text" placeholder="Mağazanın logosu">
                                                        </div>
                                                    </th>
                                                    <th></th>
                                                    <th><button type="submit">Axtar</button></th>
                                                </form>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12">
                                        {{--{{ $data2->appends($params)->links() }}--}}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="ae" role="tabpanel" aria-labelledby="ae-tab">
                                    <div class="box-body no-padding">
                                        <table class="table table-striped">
                                            <tbody>
                                            <tr>
                                                <th >#</th>
                                                <th>Mağazanın adı</th>
                                                <th>Mağazanın linki</th>
                                                <th class="text-center">Mağazanın logosu</th>
                                                <th>Ətraflı</th>
                                            </tr>
                                            <tr class="search-container">
                                                <form action="" method="get" id="searchForm">
                                                    <input name="search" value="true" type="hidden" class="submitClass widthFull form-control" type="text" />
                                                    <input name="country_id" value="3" type="hidden" type="text" />
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input name="id" value="{{isset($params['id'])?$params['id']:''}}" class="form-control r-0" type="text" placeholder="id">
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input name="name" value="{{isset($params['name'])?$params['name']:''}}" class="form-control r-0" type="text" placeholder="Mağazanın adı">
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="input-group focused">
                                                            <input name="link" value="{{isset($params['link'])?$params['link']:''}}" class="form-control r-0" type="text" placeholder="Mağazanın logosu">
                                                        </div>
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                </form>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12">
                                        {{--{{ $data3->appends($params)->links() }}--}}
                                    </div>
                                </div>
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
