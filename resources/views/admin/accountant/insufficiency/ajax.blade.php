<tbody>
@if(count($data) === 0)
    <tr colspan="7" ><td style="font-size: 22px;font-weight: bold;color: #04a8f457;" class="text-center" >yoxdur</td></tr>
@endif
@foreach($data as $value)
    <tr class="no-b">
        <td style="width: 17%;" class="table-img">
            <h5 class="font-weight-bold">{{$value->products->relUserProducts->users->name}} {{$value->products->relUserProducts->users->surname}}</h5>
        </td>
        <td>
            <i class="icon-email" ></i>
            <span>{{$value->products->relUserProducts->users->email}}</span>
            <br>

            @if($value->products->relUserProducts->users->userContacts)
                @foreach($value->products->relUserProducts->users->userContacts as $contact)
                    <i class="icon-phone" ></i>
                    <span> {{$contact->name}}</span>
                    <br>
                @endforeach
            @endif
            <i class="icon-address-card" ></i>
            <span> {{$value->products->relUserProducts->users->pin}}</span>
        </td>
        <td>
            <h6>Alıcı</h6>
            <span>{{$value->buyer->name}} {{$value->buyer->surname}}</span>
        </td>
        <td>
            <h6>Məhsulun qiyməti</h6>
            <div style="font-size: 16px;color: #d43838;font-weight: bold;" class="d-none d-lg-block">{{$value->products->price}}</div>
        </td>
        <td>
            <h6>Ümumi qiymət</h6>
            <div style="font-size: 16px;color: #56b759;font-weight: bold;" class="d-none d-lg-block">{{$value->products->relUserProducts->price}}</div>
        </td>
        <td ><div class="d-none d-lg-block">
            </div></td>
        <td>
            <div class="d-none d-lg-block">
                <span style="color: #d43838;font-weight: bold;"><i class="icon icon-data_usage"></i> {{$value->products->statuses->name}}</span><br>
                <span><i class="icon icon-timer"></i> {{$value->updated_at}}</span>
            </div>
        </td>
        <td >
            <a style="cursor: pointer;" data-product-id="{{$value->products->id}}" data-id="{{$value->products->relUserProducts->id}}" class="products_advanced btn-fab btn-fab-sm btn-primary shadow text-white"><i class="icon-more_vert"></i></a>
        </td>
        <td >
            <a style="cursor: pointer;" data-id="{{$value->products->id}}" class="transaction_refuse btn-fab btn-fab-sm btn-primary shadow text-white"><i class="icon-cash-register"></i></a>
        </td>
    </tr>
@endforeach
</tbody>
