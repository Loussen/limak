@foreach($component->data as $data)
    @if($data->locale === Lang::getLocale())
        <h4 class="text-center">{{$data->name}}</h4>
        <div class="my-3">{!! $data->description !!}</div>
    @endif
@endforeach