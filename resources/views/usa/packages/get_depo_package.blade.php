<div class="row">
    <div class="form-group col-md-6">
        <h4>Parcel ID: {{$data->id}}</h4>
        <h4>User: {{$data->user_id}}</h4>
        <input type="hidden" value="{{$data->id}}" name="id" id="package_id">
        <label>Invoice ID</label>
        <input type="text" class="form-control" value="{{$data->invoice_id}}" name="package_invoice_id" id="package_invoice_id" placeholder="123"/>

        <label>User ID</label>
        <input type="text" class="form-control" value="{{$data->user_id}}" name="package_user_id" id="package_user_id" placeholder="123"/>
    </div>

</div>

