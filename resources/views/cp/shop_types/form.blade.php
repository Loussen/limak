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
        .breadcrumb {
            display: block;
            text-align: right;
        }
        .breadcrumb li a {
            color: #86939e
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="/cp"><i class="fa fa-home"></i>Ana səhifə</a></li>
                <li><a href="{{route('shop-types.index')}}">Məhsul Tipləri</a></li>
                <li class="active">Tip əlavə et</li>
            </ol>

            <div class="card">
                <div class="card-body b-b">
                    <form method="POST" action="/cp/shop-types{{!is_null($data)?"/".$id : ""}}" enctype="multipart/form-data">
                        <div class="row">
                            @csrf
                            @if(!is_null($data))
                                @method('PUT')
                                @foreach($data as $item)
                                    <div class="form-group col-md-6">
                                        <label for="shopName" class="col-form-label">Tipin adı (az)</label>
                                        <input required name="name_{{$item->locale}}" type="text" class="form-control" id="shopName"
                                               value="{{$item->name}}" placeholder="Trendyol">
                                    </div>
                                @endforeach
                            @else
                                @method('POST')
                                <div class="form-group col-md-6">
                                    <label for="shopName" class="col-form-label">Tipin adı (az)</label>
                                    <input required name="name_az" type="text" class="form-control" id="shopName"
                                           value="" placeholder="Trendyol">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="shopName1" class="col-form-label">Tipin adı (ru)</label>
                                    <input required name="name_ru" type="text" class="form-control" id="shopName1"
                                           value="" placeholder="Trendyol">
                                </div>
                            @endif

                            <div class="form-group col-md-12" style="margin-top: 1%;text-align: right">
                                <button type="submit" class="btn btn-success">Təsdiqlə</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>


    </script>
@endsection

