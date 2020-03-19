@if(count($data) === 0)
    <tr >
        <td colspan="13" style="font-size: 22px;font-weight: bold;text-align: center;"
            class="text-center">İnvoys yoxdur
        </td>
    </tr>
@endif
@php($count=1)
@foreach($data as $value)
    <tr class="tr_{{$value->id}}">
        <td>{{$count++}}</td>
        <td>
            {{--@if($value->users)--}}
            {{$value->name}} {{$value->surname}}
            <b>{{$value->uniqid}}</b>
            {{--@endif--}}
        </td>

        <td>
            {{--                                            @if($value->users)--}}
            {{--                                                @if($value->users->userContacts)--}}
            {{--@foreach($value->users->userContacts as $contact)--}}
            {{--                                                       {{$contact->name}}--}}
            {{$value->phone}}
            {{--@endforeach--}}
            {{--@endif--}}
            <b>{{$value->email}}</b>
            @php($pin=$value->pin)
            {{--@endif--}}

        </td>
        <td>{{isset($pin) ? $pin : ''}}</td>
        <td>{{$value->purchase_no}}</td>
        <td>{{$value->shop_name}}</td>
        <td>{{$value->quantity}}</td>
        {{--<td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{!empty($value->productTypes) ? $value->products->productTypes->name: $value->products->product_type_name}}</td>--}}
        <td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{$value->product_type_name}}</td>
        <td>{{$value->price}} TL</td>
        <td>
            {{$value->comment}}
        </td>
        <td><a href="/admin/invoices/waybill/{{$value->id}}"
               target="_blank" class="btn-info"> Waybill </a></td>
        <td style="width: 14%">
            <button class="lite-green btn-effect updateDimAndWeight"  data-id="{{$value->id}}">
                <img src="{{asset('/admin/img/time.png')}}">
            </button>
            <button type="button"  class="blue btn-effect printHawb" id="printHawb" data-id="{{$value->product_id}}">
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