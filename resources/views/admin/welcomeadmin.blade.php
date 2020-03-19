@extends('layouts.admin2')

@section('content')
    <script>
        //let user = @json_decode(Auth::guard('admin')->user())
        //let roles = {{ json_decode(Auth::guard('admin')->user()->roles)  }}
    </script>
    <div id="admin-index">
        <app user="@json(Auth::guard('admin')->user()->roles)" > </app>
    </div>
@endsection

