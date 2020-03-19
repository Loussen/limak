<div class="row">
    <div class="form-group col-md-6">
        <label>Weight (kq)</label>
        <input type="number" class="form-control" value="{{$data->weight}}" name="weight" placeholder="Ex: 3.40"/>
    </div>
    <div class="form-group col-md-6">
        <label>Length (sm)</label>
        <input type="number" class="form-control" value="{{$data->length}}" name="length" placeholder="Ex: 3.40"/>
    </div>
    <div class="form-group col-md-6">
        <label>Width (sm)</label>
        <input type="number" class="form-control" value="{{$data->width}}" name="width" placeholder="Ex: 5.40"/>
    </div>
    <div class="form-group col-md-6">
        <label>Height (sm)</label>
        <input type="number" class="form-control" value="{{$data->height}}" name="height" placeholder="Ex: 14.40"/>
    </div>
    <div class="form-group col-md-6">
        <label>Waybill no</label>
        <input class="form-control" value="{{$data->waybill}}" name="waybill" placeholder=""/>
    </div>
</div>