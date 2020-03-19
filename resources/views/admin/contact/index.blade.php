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
                                <h3 class="box-title"><strong>Ölkələr</strong></h3>
                                <br>
                            </div>
                            <div class="box-body no-padding">
                                <form action="" method="get" id="searchForm">
                                    <input name="search" value="true" type="hidden" class="submitClass widthFull form-control" type="text" />
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th >#</th>
                                            <th>Ad</th>
                                            <th>Email</th>
                                            <th>telefon</th>
                                            <th>Mesaj</th>
                                        </tr>
                                        <tr class="search-container">
                                            <th></th>
                                            <th>
                                                <div class="input-group focused">
                                                    <input name="name" value="{{isset($params['name'])?$params['name']:''}}" class="form-control r-0" type="text" placeholder="Ölkənin adı">
                                                </div>
                                            </th>
                                            <th>
                                                <div class="input-group focused">
                                                    <input name="email" value="{{isset($params['email'])?$params['email']:''}}" class="form-control r-0" type="text" placeholder="Email">
                                                </div>
                                            </th>
                                            <th>
                                                <div class="input-group focused">
                                                    <input name="phone" value="{{isset($params['phone'])?$params['phone']:''}}" class="form-control r-0" type="text" placeholder="telefon">
                                                </div>
                                            </th>
                                            <th>
                                                <div class="input-group focused">
                                                    <input name="description" value="{{isset($params['description'])?$params['description']:''}}" class="form-control r-0" type="text" placeholder="telefon">
                                                </div>
                                            </th>
                                            <th></th>
                                        </tr>
                                        @php($startCount = count($data) > 0?($data->currentPage() - 1) * $data->perPage(): 0)
                                        @foreach($data as $key => $value)
                                        <tr>
                                            <td style="font-weight: bold;">{{++$startCount}}.</td>
                                            <td style="font-weight: bold;">{{$value->name}}</td>
                                            <td style="font-weight: bold;">{{$value->email}}</td>
                                            <td style="font-weight: bold;">{{$value->phone}}</td>
                                            <td style="font-weight: bold;">{{$value->description}}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </form>
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
