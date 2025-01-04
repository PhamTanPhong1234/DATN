@extends('frontend/layouts/app-user')

@section('main')
<section>
    @include('component.index.slider-area')
    @include('component.index.static-area')
    @include('component.index.banner-area-2')
     {{-- @include('component.index.best-sells-area') --}}
     @include('component.index.categorie-area')
     @include('component.index.banner-area')
     @include('component.index.recent-add-area')
     @include('component.index.brand-area')

</section>

@endsection




