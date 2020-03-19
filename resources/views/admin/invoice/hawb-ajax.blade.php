<div class="row">
    <div class="form-group col-md-6">
        <label>Ağırlık (kq)</label>
        <input type="number" class="form-control" value="{{$data->weight}}" name="weight" placeholder="Misal: 3.40"/>
    </div>
    <div class="form-group col-md-6">
        <label>Uzunluk (sm)</label>
        <input type="number" class="form-control" value="{{$data->length}}" name="length" placeholder="Misal: 3.40"/>
    </div>
    <div class="form-group col-md-6">
        <label>Genişlik (sm)</label>
        <input type="number" class="form-control" value="{{$data->width}}" name="width" placeholder="Misal: 5.40"/>
    </div>
    <div class="form-group col-md-6">
        <label>Yükseklik (sm)</label>
        <input type="number" class="form-control" value="{{$data->height}}" name="height" placeholder="Misal: 14.40"/>
    </div>
    <div class="form-group col-md-6">
        <label>Fiyat</label>
        <input class="form-control" value="{{$data->price}}" name="price" placeholder=""/>
    </div>
    <div class="form-group col-md-6" style="margin-top: 30px">
        <input type="checkbox" value="1" name="liquid" {{$data->liquid_type==1?'checked':''}} placeholder=""/>
        <label>Sıvı mallar</label>
    </div>
    <div class="form-group col-md-6" style="margin-top: 30px">
        <input type="checkbox" value="1" name="by_bus" {{$data->by_bus==1?'checked':''}} placeholder=""/>
        <label>Otobüs</label>
    </div>
</div>