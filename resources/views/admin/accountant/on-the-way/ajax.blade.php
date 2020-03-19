<tbody>
@foreach($dataInRoad as $value)
    <tr class="no-b">
        <td style="width: 17%;" class="table-img">
            <h5 class="font-weight-bold">{{$value->relUserProducts->users->name}} {{$value->relUserProducts->users->surname}}</h5>
        </td>
        <td>
            <i class="icon-email" ></i>
            <span>{{$value->relUserProducts->users->email}}</span>
            <br>

            @if($value->relUserProducts->users->userContacts)
                @foreach($value->relUserProducts->users->userContacts as $contact)
                    <i class="icon-phone" ></i>
                    <span> {{$contact->name}}</span>
                    <br>
                @endforeach
            @endif
            <i class="icon-address-card" ></i>
            <span> {{$value->relUserProducts->users->pin}}</span>
        </td>
        <td>
            <h6>Mağaza</h6>
            <span>{{$value->products->shop_name}}</span>
        </td>
        <td>
            <h6>Məhsulun tipi</h6>
            <span>{{$value->products->productTypes->name}}</span>
        </td>
        <td>
            <h6>Məhsulun sayı</h6>
            <span>{{$value->products->quantity}}</span>

        </td>
        <td>
            <h6>Qiymət</h6>
            <div style="font-size: 16px;color: #56b759;font-weight: bold;" class="d-none d-lg-block">{{$value->products->price}}</div>
        </td>
        <td ><div class="d-none d-lg-block">
            </div></td>
        <td>
            <div class="d-none d-lg-block">
                <span style="color: #4CAF50;font-weight: bold;"><i class="icon icon-data_usage"></i> {{$value->invoiceStatus->name}}</span><br>
                <span><i class="icon icon-timer"></i> {{$value->updated_at}}</span>
            </div>
        </td>
        <td >
            <a style="cursor: pointer;" download  href="{{url($value->file)}}" class="btn-fab btn-fab-sm btn-warning shadow text-white"><i class="icon-file"></i></a>
        </td>
    </tr>
@endforeach
</tbody>
