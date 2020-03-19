<div class="row">
    <div class="col-md-12">
        <h4>İade ID: {{$data->id}}</h4>
        <h4>Baglama ID: {{$data->invoice_id}}</h4>
        <h4>Mağaza: {{$data->shop_name}}</h4>
        <h4>İçerik: {{$data->product_type_name}}</h4>
        <h4>Say: {{$data->product_quantity}}</h4>
        <h4>Çeki: {{$data->weight}}</h4>
        <input type="hidden" value="{{$data->id}}" name="return_id" id="return_id">
    </div>
    <div class="form-group col-md-4">
        <label>Iade Kodu</label>
        <input type="text" class="form-control" value="{{$data->return_code}}" name="return_code" id="return_code" placeholder="123"/>
    </div>
    <div class="form-group col-md-4">
        <label>Kargo</label>
        <input type="text" class="form-control" value="{{$data->cargo_name}}" name="cargo_name" id="cargo_name" placeholder="123"/>
    </div>
    <div class="form-group col-md-4">
        <label>Kargo takip numarası</label>
        <input type="text" class="form-control" value="{{$data->tracking_number}}" name="tracking_number" id="tracking_number" placeholder="123"/>
    </div>

</div>

