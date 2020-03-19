@extends('layouts/admin')
@section('styles')
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div style="width: 100%;" class="container">
            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Bəyənnaməsi yüklənməmiş müraciətlər</strong></h3>
                                <br>
                            </div>
                            <div class="box-body no-padding">
                                <div class="jumbotron p-2 text-left">
                                    <h5>Yenisini əlavə et!</h5>
                                    <form action="{{route('invoiceless.store')}}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Müştəri nömrəsi</label>
                                            <input type="text" class="form-control" name="user_uid" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Qeyd</label>
                                            <textarea type="text" class="form-control" name="note" required> </textarea>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary btn-lg" >Əlavə et!</button>
                                        </div>
                                    </form>
                                </div>
                                <table class="table table-hovered table-stripped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Qeyd</th>
                                        <th>Müşdəri nömrəsi</th>
                                        <th>Daxil olma tarixi</th>
                                        <th>Göndərilmə tarixi</th>
                                        <th>Bəyənnamə</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list as $index => $value)
                                            <tr>
                                                <td>{{++$index}}</td>
                                                <td>{{$value->note}}</td>
                                                <td>{{$value->user_uid}}</td>
                                                <td>{{date('d.m.Y H:i', strtotime($value->created_at))}}</td>
                                                <td>{{$value->sent ? date('d.m.Y H:i', strtotime($value->sent_date)) : 'Göndərilməmişdir'}}</td>
                                                <td>
                                                    {{$value->is_active ? 'yüklənməmmişdir' : 'yüklənmişdir'}}
                                                    @if($value->sent)
                                                        <a href="{{route('invoiceless.done' , $value->id)}}" class="btn btn-warning" title="Bəyənnamə yükləndio olaraq qeydiyyata al!">Yükləndi</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendModal_{{$index}}">
                                                        Göndər
                                                    </button>
                                                </td>
                                            </tr>


                                            <!-- Modal -->
                                            <div class="modal fade" id="sendModal_{{$index}}" tabindex="-1" role="dialog" aria-labelledby="sendModal_{{$index}}_Label" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form action="{{route('invoiceless.send', $value->id)}}" method="POST">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="sendModal_{{$index}}_Label">Məktub göndər</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <textarea name="note" class="form-control" id="message_{{$index}}" rows="5">{{$value->note}}</textarea>
                                                                <input type="hidden" name="uid" value="{{$value->user_uid}}">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Bağla</button>
                                                                <button type="submit" class="btn btn-primary">Göndər</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

