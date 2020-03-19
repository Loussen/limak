<div class="row">
    @foreach($products as $value)
        <div class="col-md-6 text-center my-auto">
            <div class="card my-3 card-block shadow1 d-flex">
                <div class="card-body align-items-center d-flex justify-content-center">
                    <table class="table-of-list table table-striped">
                            <tbody>
                            <tr>
                                <td style="text-align:left">Məhsulun tipi</td>
                                <td>{{!empty($value->productsType->name)? $value->productsType->name: $value->product_type_name}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left" >Qiymət</td>
                                <td><span style="color: #4da239;font-size: 20px;">{{$value->price}}</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left" >Miqdar</td>
                                <td>{{$value->quantity}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left" >Link</td>
                                <td>{{$value->extras->link}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left" >Brend</td>
                                <td>{{$value->extras->brand}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left" >Rəng</td>
                                <td>{{$value->extras->color}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left" >Ölçü</td>
                                <td>{{$value->extras->size}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:left" >Status</td>
                                <td>{{$value->statuses->name}}</td>
                            </tr>
                            </tbody>
                        </table>
                </div>
                <div>
                </div>
            </div>
        </div>
    @endforeach
</div>

