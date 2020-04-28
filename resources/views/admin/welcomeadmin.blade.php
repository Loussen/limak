@extends('layouts.admin2')

@section('content')
    <script>
        //let user = @json_decode(Auth::guard('admin')->user())
        //let roles = {{ json_decode(Auth::guard('admin')->user()->roles)  }}
    </script>

    <script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
    <div id="admin-index">
        <app user="@json(Auth::guard('admin')->user()->roles)" > </app>
    </div>

@endsection

