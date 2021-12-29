@extends("frontend.layout.app")

@section('content')
    <div class="hidden md:block">
        <img src="{{ asset($product->getFirstMedia("laravel_shopping_banner")->getUrl()) }}" class="w-full" alt="">
    </div>
    <div class="container px-8 mx-auto my-20">
        <div class="flex flex-wrap">
            <div class="w-full md:w-1/3">
                <img src="{{ asset($product->getFirstMedia("laravel_shopping_thumbnail")->getUrl()) }} " alt="">
            </div>
            <div class="w-full md:w-2/3 mt-10 md:mt-0">
                <div class="md:ml-10 text-sm md:text-base">
                    <div class="text-xl lg:text-3xl font-bold">
                        {{ $product->getTranslation("title", $locale) }}
                    </div>
                    <div class="mt-4">
                        <div class="inline-flex bg-gray-600 text-white rounded-full px-4 py-1 text-xs font-bold">
                            <div class="mr-2 uppercase">
                                {{ __("LaravelShopping::web.inventory") }}
                            </div>
                            {{ $product->quantity }}
                        </div>
                        <div class="inline-flex bg-gray-600 text-white rounded-full px-4 py-1 text-xs font-bold">
                            <div class="mr-2 uppercase">
                                {{ __("LaravelShopping::web.price") }}
                            </div>
                            ${{ $product->price }}
                        </div>
                    </div>
                    <div class="mt-4">
                        {!! nl2br($product->getTranslation("text", $locale)) !!}
                    </div>

                    <div class="text-right mt-4">
                        <a
                            href="{{ route("LaravelShopping.cart.add_to_cart", request()->id) }}"
                            class="inline-block main-btn-bg-color px-10 py-2 rounded-md text-white capitalize"
                            >
                            {{ __("LaravelShopping::web.buy") }}
                        </a>
                        <a
                            href="{{ url()->previous() }}"
                            class="inline-block main-btn-bg-color px-10 py-2 rounded-md text-white capitalize">
                            {{ __("LaravelShopping::web.back") }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

