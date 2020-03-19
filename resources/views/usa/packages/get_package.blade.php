<div class="`row">
    <div class="col-md-12">
        <h4>Parcel ID: {{$data->parcel_id}}</h4>
        <h4>Suite Number: {{$data->suite_number}}</h4>
        <input type="hidden" value="{{$data->parcel_id}}" name="parcel_id" id="package_parcel_id">
    </div>
    <div class="form-group col-md-6">
        <label>Invoice ID</label>
        <input type="text" class="form-control" value="{{$data->invoice_id}}" name="package_invoice_id" id="package_invoice_id" placeholder="123"/>
    </div>
    <div class="form-group col-md-6">
        <label>User ID</label>
        <input type="text" class="form-control" value="{{$data->user_id}}" name="package_user_id" id="package_user_id" placeholder="123"/>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <label>Price Value ($)</label>
        <input type="text" class="form-control" value="{{$data->price}}" name="package_price" id="package_price" placeholder="Ex.: 3.40"/>
    </div>
    <div class="form-group col-md-6">
        <label>DESC</label>
        <input type="text" class="form-control" value="{{$data->description}}" name="package_desc" id="package_desc" placeholder="Ex.:2 PCS COSMETICS"/>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h4>User Information</h4>
    </div>
    <div class="form-group col-md-6">
        <label>Address</label>
        <input type="text" class="form-control" value="{{$user->address}}" name="package_address" id="package_address" placeholder="123"/>
    </div>
    <div class="form-group col-md-6">
        <label>Phone</label>
        <input type="text" class="form-control" value="{{$user->phone}}" name="package_phone" id="package_phone" placeholder="123"/>
    </div>
    <div class="form-group col-md-6">
        <label>Company</label>
        <input type="text" class="form-control" value="{{$user->name}} {{$user->surname}}" name="package_company" id="package_company" placeholder="123"/>
    </div>
    <div class="form-group col-md-6">
        <label>Email</label>
        <input type="text" class="form-control" value="{{$user->email}}" name="package_email" id="package_email" placeholder="123"/>
    </div>
</div>

