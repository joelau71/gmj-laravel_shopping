@extends("frontend.layout.app")

@section('content')
    <div>
        <img src="https://images.unsplash.com/photo-1616499370260-485b3e5ed653?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1920&h=600&q=80 alt="">
    </div>
    <div class="mt-14">
        <h1 class="text-3xl text-center font-bold uppercase">
            {{ __("LaravelShopping::web.my shopping cart") }}
        </h1>
    </div>
    <form class="container mx-auto my-14" method="POST" action="{{ route("LaravelShopping.cart.store") }}">
        <input type="hidden" name="action" id="action" value="save">
        @csrf
        <div class="flex items-center w-full pb-4 border-b border-gray-200">
            <div class="px-4 w-20 capitalize"></div>
            <div class="px-4 w-40 capitalize">{{ __("LaravelShopping::web.thumbnail") }}</div>
            <div class="px-4 flex-1 capitalize">{{ __("LaravelShopping::web.name") }}</div>
            <div class="px-4 flex-1 capitalize">{{ __("LaravelShopping::web.quantity") }}</div>
            <div class="px-4 flex-1 capitalize">{{ __("LaravelShopping::web.price") }}</div>
            <div class="px-4 flex-1 capitalize">{{ __("LaravelShopping::web.total") }}</div>
        </div>
        @forelse ($collections as $item)
            <div class="flex items-center mt-4">
                <div class="px-4 w-20">
                    <a href="{{ route('LaravelShopping.cart.delete', $item->rowId) }}" class="remove-from-cart cursor-pointer" data-rowId="{{$item->rowId}}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 64 64" xml:space="preserve"><path d="M34.5 32 62.2 4.2c.7-.7.7-1.8 0-2.5s-1.8-.7-2.5 0L32 29.5 4.2 1.8c-.7-.7-1.8-.7-2.5 0s-.7 1.8 0 2.5L29.5 32 1.8 59.8c-.7.7-.7 1.8 0 2.5.3.3.8.5 1.2.5s.9-.2 1.2-.5L32 34.5l27.7 27.8c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5.7-.7.7-1.8 0-2.5L34.5 32z"/></svg>
                    </a>
                </div>
                <div class="px-4 w-36">
                    <img src="{{ asset($item->options->thumbnail) }}" alt="">
                </div>
                <div class="px-4 flex-1">
                    {{ $item->title }}
                </div>
                <div class="px-4 flex-1">
                    <input type="number" min="1"b step="1" name="qty[]" value="{{ $item->qty }}" class="border border-gray-200 px-2 py-1 quantity" /> / {{ $item->options->storage }}
                    @error("qty.{$loop->index}")
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <div class="px-4 flex-1 price">
                    {{ $item->price }}
                </div>
                <div class="px-4 flex-1 total">
                    {{ $item->qty * $item->price }}
                </div>
            </div>
        @empty
            <div class="text-center mt-10 text-green-600">
                {{ __("no item in your shopping cart") }}
            </div>
        @endforelse
        @if ($collections->count() > 0)
            <div class="flex items-center w-full pt-4 mt-4 border-t border-gray-200">
                <div class="px-4 w-20"></div>
                <div class="px-4 w-40"></div>
                <div class="px-4 flex-1"></div>
                <div class="px-4 flex-1"></div>
                <div class="px-4 flex-1 uppercase">{{ __("LaravelShopping::web.sum") }}</div>
                <div class="px-4 flex-1 sum"></div>
            </div>
            <div class="text-center mt-8">
                <button type="submit" class="main-btn-bg-color text-white px-4 py-2 rounded-md save uppercase" data-action="save">
                    {{ __("LaravelShopping::web.save") }}
                </button>
                <button type="submit" class="main-btn-bg-color text-white px-4 py-2 rounded-md save uppercase" data-action="checkout">
                    {{ __("LaravelShopping::web.checkout") }}
                </button>
            </div>
        @endif
    </form>
@endsection

@push('js')
    <script>
        $(function(){
            $(".quantity").on("change", function(){
                const $this = $(this);
                const qty = $this.val();
                const $parent = $this.parent().parent();
                const price = $parent.find(".price").text().trim();
                const $total = $parent.find(".total");
                const total = qty * price;
                $total.text(total);
                calculate();
            });

            $(".save").on("click", function(){
                const action = $(this).attr("data-action");
                $("#action").val(action);
            });

            function calculate(){
                let sum = 0;
                const totals = document.querySelectorAll(".total");
                console.log(totals);
                totals.forEach((item) => {
                    sum += parseInt(item.innerHTML.trim());
                });
                $(".sum").text(sum);
            }
            
            $(".remove-from-cart").on("click", function(event){
                const $this = $(this)
                event.preventDefault();
                Swal.fire({
                    title: "{{ __('LaravelShopping::web.do you want to delete this product') }}",
                    showCancelButton: true,
                    cancelButtonText: "{{ __('LaravelShopping::web.cancel') }}",
                    confirmButtonText: "{{ __('LaravelShopping::web.confirm') }}"
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = $this.attr("href");
                    }
                });
            });

            calculate();
            
        });
    </script>
@endpush

