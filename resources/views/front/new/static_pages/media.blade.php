<section class="slider-media">
    <div class="owl-carousel owl-theme">
            @foreach($component->files as $file)
                <div><img src="{{$file->file}}" class="img-responsive" alt="{{$file->name}}"></div>
            @endforeach
    </div>
</section>