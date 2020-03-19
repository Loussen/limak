<div class="container">
    <div class="row">
        <div class="invoice-head col-xs-12">
            <div class="col-md-4 col-sm-5 col-xs-6">
                <h4>@lang("breadcrumb.$section") </h4>
            </div>
            <div class="col-md-8 col-sm-7 col-xs-6">
                <ol class="breadcrumb web">
                    <li><a href="{{ route('front.main') }}">@lang('breadcrumb.home') </a></li>
                    <li class="active"> @lang("breadcrumb.$section")</li>
                </ol>
                <ol class="breadcrumb mobile">
                    <li><a href="index.html">... </a></li>
                    <li class="active">@lang("breadcrumb.$section")</li>
                </ol>
            </div>
        </div>
    </div>
</div>