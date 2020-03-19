<form enctype="multipart/form-data" action="{{route('usr.msg.send')}}" method="POST">
    <fieldset>
        <legend>Mesaj göndər:</legend>
        {{csrf_field()}}
        Başlıq: <input type="text" title="Başlıq" name="subject" placeholder="İstifadəçi kodumu necə tapa bilərəm..." value="Test"/><br>
        Mesaj: <textarea type="text" title="Mesaj" name="message" placeholder="...">Hello there</textarea><br>
        Kateqoriya: <select name="category_id" id="">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select><br>
        Qoşma: <input type="file" name="files[]" title="Qoşma" multiple /><br>
        <button type="submit">Gonder</button>
    </fieldset>
</form>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>@lang('panel-errors.messages')</th>
        <th>Tarix</th>
        <th>Cavabla</th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $index => $message)
    <tr>
        <td>{{++$index}}</td>
        <td>{{$message->subject}}</td>
        <td>{{date('d.m.Y', strtotime($message->created_at))}}</td>
        <td>
            <button>E</button>
            <button>D</button>
        </td>
    </tr>
    @if($message->messages && count($message->messages) > 0)
        @foreach($message->messages as $childIndex => $child)
            <tr>
                <td>{{++$childIndex}}</td>
                <td>{{$child->message}}</td>
                <td>{{date('d.m.Y', strtotime($child->created_at))}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4">
                <form enctype="multipart/form-data" action="{{route('usr.msg.reply', $message->id)}}" method="POST">
                    <fieldset>
                        <legend>Mesaj göndər:</legend>
                        {{csrf_field()}}
                        Mesaj: <textarea type="text" title="Mesaj" name="message" placeholder="...">Hello there</textarea><br>
                        Qoşma: <input type="file" name="files[]" title="Qoşma" multiple /><br>
                        <button type="submit">Gonder</button>
                    </fieldset>
                </form>
            </td>
        </tr>
    @endif
    @endforeach
    </tbody>
</table>
