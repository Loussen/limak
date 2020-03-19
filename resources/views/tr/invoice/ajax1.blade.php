@if(count($data) === 0)
    <tr >
        <td colspan="13" style="font-size: 22px;font-weight: bold;text-align: center;"
            class="text-center">İnvoys yoxdur
        </td>
    </tr>
@endif
@php($count=1)
@foreach($data as $value)
    <tr class="tr_{{$value->id}}" style="{{$value->by_bus==1 ? 'background:#e07f7f;color:white;' : ''}}{{$value->liquid_type==1 ? 'background:#7fbce0;color:white;' : ''}}{{$value->return_id>1 ? 'background:#ccc;color:black;' : ''}}">
        <td>{{$count++}}</td>
        <td>
            <?php
            if($value->client_id>0 and $value->corporate==1){
            ?>
            {{$value->client_name}} {{$value->client_surname}}
            <b>1{{str_pad($value->client_id,6,"0",STR_PAD_LEFT)}}</b>
            <?php
            }else{
            ?>
            {{$value->name}} {{$value->surname}}
            <b>{{$value->uniqid}}</b>
            <?php
            }
            ?>
        </td>
        <td>{{$value->order_tracking_number}}</td>
        <td>{{$value->purchase_no}}</td>
        <td>{{$value->shop_name}}</td>
        <td>{{$value->weight}} kq</td>
        <td>{{$value->quantity}}</td>
        {{--<td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{!empty($value->productTypes) ? $value->products->productTypes->name: $value->products->product_type_name}}</td>--}}
        <td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{$value->product_type_name}}</td>
        <td>{{$value->price}} TL</td>
        <td>
            {{$value->comment}}
        </td>
        <td><a href="/invoices/waybill/{{$value->id}}"
               target="_blank" class="btn-info"> Waybill </a></td>
        <td style="width: 14% ">
            @if($status==2)
                <button class="lite-red btn-effect changeVehicle" data-bus="{{$value->by_bus}}" data-id="{{$value->id}}">
                    {{--<img src="{{asset('/admin/img/time.png')}}">--}}
                    <i class="fa fa-bus"></i>
                </button>
            @endif
            <button class="lite-green btn-effect updateDimAndWeight"  data-id="{{$value->id}}">
                <img src="{{asset('/admin/img/time.png')}}">
            </button>
            <button type="button"  class="blue btn-effect printHawb" id="printHawb" data-id="{{$value->id}}">
                <i class="fa fa-print"></i>
            </button>
            @if($value->file)
                <a download
                   href="{{url($value->file)}}"
                   class="yellow btn-effect">
                    <i class="fa fa-file"></i>
                </a>
            @endif
            @if($status==1)
                <a href="javascript:void(0)" data-id="{{$value->id}}" data-status="{{$value->status_id}}" class="submit-invoice-storage1 pink btn-effect id_{{$value->id}}" style="display:{{$value->weight>0 ? 'show' : 'none'}}">
                    ok
                </a>
            @endif
        </td>
        @if($status==2)
            <td>
                <input type="checkbox" class="checkboxes" id="checkbox_{{$value->id}}" name="selected_invoices[]" value="{{$value->id}}">

                {{--<div class="checkbox">--}}
                {{--<input type="checkbox" id="checkbox_{{$value->id}}" name="selected_invoices[]" value="{{$value->id}}">--}}
                {{--<label for="checkbox_{{$value->id}}"></label>--}}
                {{--</div>--}}
            </td>
        @endif
    </tr>
@endforeach