<x-layouts.store title="Головна">
<!--Home slider-->
<div class="slideshow slideshow-wrapper pb-section sliderFull">
    <div class="home-slideshow">
        @foreach ($sliders as $slider)
            <div class="slide">
                <div class="blur-up lazyload bg-size">
                    <img class="blur-up lazyload bg-img" data-src="{{ asset('storage/'.$slider->image_path) }}" src="{{ asset('storage/'.$slider->image_path) }}" alt="{{ $slider->title }}" title="{{ $slider->title }}" />
                <div class="slideshow__text-wrap slideshow__overlay classic bottom">
                    <div class="slideshow__text-content bottom">
                        <div class="wrap-caption center">
                                <h2 class="h1 mega-title slideshow__title">{{ $slider->title }}</h2>
                                <span class="mega-subtitle slideshow__subtitle">{{ $slider->subtitle }}</span>
                                {{-- <span class="btn">{{ asset('storage/'.$slider->image_path) }}</span> --}}
                            </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        {{-- <div class="slide">
            <div class="blur-up lazyload bg-size">
                <img class="blur-up lazyload bg-img" data-src="assets/images/slideshow-banners/belle-banner2.jpg" src="assets/images/slideshow-banners/belle-banner2.jpg" alt="Summer Bikini Collection" title="Summer Bikini Collection" />
                <div class="slideshow__text-wrap slideshow__overlay classic bottom">
                    <div class="slideshow__text-content bottom">
                        <div class="wrap-caption center">
                            <h2 class="h1 mega-title slideshow__title">Summer Bikini Colddddlection</h2>
                            <span class="mega-subtitle slideshow__subtitle">Save up to 50% off this weekend only</span>
                            <span class="btn">Shop now</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="slide">
            <div class="blur-up lazyload bg-size">
                <img class="blur-up lazyload bg-img" data-src="assets/images/slideshow-banners/belle-banner1.jpg" src="assets/images/slideshow-banners/belle-banner1.jpg" alt="Shop Our New Collection" title="Shop Our New Collection" />
                <div class="slideshow__text-wrap slideshow__overlay classic bottom">
                    <div class="slideshow__text-content bottom">
                        <div class="wrap-caption center">
                                <h2 class="h1 mega-title slideshow__title">Shop Our New Casdasdollection</h2>
                                <span class="mega-subtitle slideshow__subtitle">From Hight to low, classic or modern. We have you covered</span>
                                <span class="btn">Shop now</span>
                            </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>
<!--End Home slider-->



</x-layouts.store>
