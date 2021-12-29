@extends("frontend.layout.app")

@section('content')
<div class="bg-gray-100">
    <div>
        <img src="https://images.unsplash.com/photo-1491975474562-1f4e30bc9468?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1920&h=600&q=80" alt="">
    </div>
    <div class="mt-14">
        <h1 class="text-3xl text-center font-bold uppercase">{{ __("LaravelShopping::web.checkout") }}</h1>
    </div>
    <div class="container mx-auto py-14 px-8 flex flex-wrap">
        <div class="w-full lg:w-1/3">
            <div class="flex items-center w-full pb-4 border-b border-gray-200">
                <div class="px-4 w-24 capitalize">{{ __("LaravelShopping::web.thumbnail") }}</div>
                <div class="px-4 flex-1 capitalize">{{ __("LaravelShopping::web.name") }}</div>
                <div class="px-4 flex-1 capitalize">{{ __("LaravelShopping::web.quantity") }}</div>
            </div>

            @foreach ($collections as $item)
                <div class="flex items-center mt-4">
                    <div class="px-4 w-24">
                        <img src="{{ asset($item->options->thumbnail) }}" alt="">
                    </div>
                    <div class="px-4 flex-1">
                        {{ $item->name }}
                    </div>
                    <div class="px-4 flex-1">
                        {{ $item->qty }}
                    </div>
                </div>
            @endforeach
        </div>
        <div class="w-full lg:w-2/3 mt-20 lg:mt-0">
            <form action="{{ route("LaravelShopping.order.checkout2") }}" method="POST" class="lg:ml-10">
                @csrf
                <h2 class="text-xl font-bold text-gray-500">
                    {{ __("LaravelShopping::web.shipping info") }}
                </h2>
                <hr class="my-4" />
                <div>
                    <x-admin.atoms.text name="shipping_fullname" placeholder="{{ __('LaravelShopping::web.full name') }}*" value="{{ old('shipping_fullname') }}"/>
                    @error('shipping_fullname')
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </div>
                <div>
                    <x-admin.atoms.textarea name="shipping_address" placeholder="{{ __('LaravelShopping::web.address') }}*">{{ old("shipping_address") }}</x-admin.atoms.textarea>
                    @error('shipping_address')
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </div>
                <div>
                    <x-admin.atoms.number name="shipping_phone" placeholder="{{ __('LaravelShopping::web.phone') }}*" value="{{ old('shipping_phone') }}" />
                    @error('shipping_phone')
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </div>

                <h2 class="text-xl font-bold text-gray-500 mt-10">
                    {{ __("LaravelShopping::web.billing info") }}
                </h2>
                <hr class="my-4" />
                <div>
                    <x-admin.atoms.text name="billing_fullname" placeholder="{{ __('LaravelShopping::web.full name') }}*" value="{{ old('billing_fullname') }}" />
                    @error('billing_fullname')
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </div>
                <div>
                    <x-admin.atoms.textarea name="billing_address" placeholder="{{ __('LaravelShopping::web.address') }}*">{{ old("billing_address") }}</x-admin.atoms.textarea>
                    @error('billing_address')
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </div>
                <div>
                    <x-admin.atoms.number name="billing_phone" placeholder="{{ __('LaravelShopping::web.phone') }}*" value="{{ old('billing_phone') }}" />
                    @error('billing_phone')
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </div>

                <div class="mt-4">
                    <x-admin.atoms.select name="payment_method" id="payment_method">
                        <option value="">-- {{ __("LaravelShopping::web.please select payment method") }}* --</option>
                        <option value="cash_on_delivery" @if (old("payment_method") == "cash_on_delivery")
                            selected                           
                        @endif>
                            Cash On Delivery
                        </option>
                        <option value="paypal" @if (old("payment_method") == "paypal")
                            selected                           
                        @endif>
                            PayPal
                        </option>
                        <option value="stripe" @if (old("payment_method") == "stripe")
                            selected                           
                        @endif>
                            Stripe
                        </option>
                    </x-admin.atoms.select>
                    @error('payment_method')
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </div>
                <div>
                    <x-admin.atoms.textarea name="notes" placeholder="{{ __('LaravelShopping::web.remarks') }}">{{ old("notes") }}</x-admin.atoms.textarea>
                </div>

                <div class="text-right mt-4">
                    <x-admin.atoms.link href="{{ route('LaravelShopping.cart.index') }}">
                        {{ __("LaravelShopping::web.back to cart") }}
                    </x-admin.atoms.link>
                    <x-admin.atoms.button>
                        {{ __("LaravelShopping::web.save") }}
                    </x-admin.atoms.button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection