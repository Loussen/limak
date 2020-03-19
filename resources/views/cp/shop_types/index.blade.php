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
            width: 400px;
            float: right;
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
        <div style="width: 100%;" class="container">
            <a class="btn btn-primary" style="margin-bottom: 20px;" href="{{route('shop-types.create')}}">Tip əlavə et</a>
            <ol class="breadcrumb">
                <li><a href="/cp"><i class="fa fa-home"></i>Ana səhifə</a></li>
                <li class="active"><a href="/cp/shop-types">Məhsul Tipləri</a></li>
            </ol>
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Tiplər</strong></h3>
                                <br>
                            </div>
                            <div class="box-body no-padding">
                                <table class="table table-striped">
                                    <tbody>
                                    <tr>
                                        <th >#</th>
                                        <th>Tip adı (az)</th>
                                        <th>Tip adı (ru)</th>
                                        <th>Ətraflı</th>
                                    </tr>
                                    @php($count=1)
                                    @foreach($data as $item)
                                        @php($name=explode(',',$item->names))
                                        <tr>
                                            <td style="font-weight: bold;">{{$count++}}</td>
                                            <td style="font-weight: bold;">{{$name[0]}}
                                            <td style="font-weight: bold;">{{isset($name[1]) ? $name[1] : ''}}
                                            <td>

                                                <form style="float: right;" action="{{route('shop-types.destroy',['id'=>$item->id])}}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="width: 40px; height: 38px;"  class="btn btn-danger btn-xs red">
                                                        <i style="font-size: 17px;padding-top: 5px;" class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                                <a style="width: 40px; height: 38px;margin-right: 5%;" href="{{route('shop-types.edit',['id'=>$item->id])}}" class="btn btn-success btn-xs purple">
                                                    <i style="font-size: 17px;padding-top: 5px;" class="far fa-edit"></i>
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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

    </script>
@endsection
