<x-admin.layout.app>
    @php
        $breadcrumbs = [
            ['name' => "Product", 'link' => route("LaravelShopping.index")],
            ['name' => "Edit"],
        ];
    @endphp
    <x-admin.atoms.breadcrumb :breadcrumbs="$breadcrumbs" />

    <form
        id="myForm"
        method="POST"
        action="{{ route("LaravelShopping.update", $collection->id) }}"
    >
        @csrf
        @method("PATCH")
        <x-admin.atoms.row>
            <x-admin.atoms.label for="banner" class="required mb-2">
                Banner (Size: {{ config('gmj.laravel_shopping_config.banner.image_width') }}(w) x {{ config('gmj.laravel_shopping_config.banner.image_height') }}(h))
            </x-admin.atoms.label>
            <input
                type="file"
                name="banner"
                id="banner"
                class="upload-image-copper"
                data-uic-display-width="768"
                data-uic-display-height="{{ 768 / config('gmj.laravel_shopping_config.banner.image_width') * config('gmj.laravel_shopping_config.banner.image_height') }}"
                data-uic-target-width="{{ config('gmj.laravel_shopping_config.banner.image_width') }}"
                data-uic-target-height="{{ config('gmj.laravel_shopping_config.banner.image_height') }}"
                data-uic-title="Size: {{ config('gmj.laravel_shopping_config.banner.image_width') }}(w) x {{ config('gmj.laravel_shopping_config.banner.image_height') }}(h)"
                data-uic-path="{{ $collection->getFirstMedia("laravel_shopping_banner")->getUrl() }}"
            />
            @error("uic_base64_banner")
                <x-admin.atoms.error>
                    {{ $message }}
                </x-admin.atoms.error>
            @enderror
        </x-admin.atoms.row>

        <x-admin.atoms.row>
            <x-admin.atoms.label for="thumbnail" class="required mb-2">
                Thumbnail (Size: {{ config('gmj.laravel_shopping_config.thumbnail.image_width') }}(w) x {{ config('gmj.laravel_shopping_config.thumbnail.image_height') }}(h))
            </x-admin.atoms.label>
            <input
                type="file"
                name="thumbnail"
                id="thumbnail"
                class="upload-image-copper field"
                data-uic-display-width="400"
                data-uic-display-height="{{ 400 / config('gmj.laravel_shopping_config.thumbnail.image_width') * config('gmj.laravel_shopping_config.thumbnail.image_height') }}"
                data-uic-target-width="{{ config('gmj.laravel_shopping_config.thumbnail.image_width') }}"
                data-uic-target-height="{{ config('gmj.laravel_shopping_config.thumbnail.image_height') }}"
                data-uic-title="Size: {{ config('gmj.laravel_shopping_config.thumbnail.image_width') }}(w) x {{ config('gmj.laravel_shopping_config.thumbnail.image_height') }}(h)"
                data-uic-path="{{ $collection->getFirstMedia("laravel_shopping_thumbnail")->getUrl() }}"
            />
            @error("uic_base64_thumbnail")
                <x-admin.atoms.error>
                    {{ $message }}
                </x-admin.atoms.error>
            @enderror
        </x-admin.atoms.row>
        
        <x-admin.atoms.row>
            <x-admin.atoms.label for="category" class="mb-2 required">
                Category
            </x-admin.atoms.label>
            <x-admin.atoms.select id="category" name="category[]" multiple class="select2">
                @foreach ($categories as $item)
                    <option value="{{ $item->id }}" @if(in_array($item->id, $collection->categories->pluck("id")->toArray()))
                        selected
                    @endif>{{ $item->title }}</option>
                @endforeach
            </x-admin.atoms.select>
            @error("category")
                <x-admin.atoms.error>
                    {{ $message }}
                </x-admin.atoms.error>
            @enderror
        </x-admin.atoms.row>
        
        @foreach (config("translatable.locales") as $locale)
            <x-admin.atoms.row>
                <x-admin.atoms.label for="title_{{$locale}}" class="required">
                    Title ({{ $locale }})
                </x-admin.atoms.label>
                <x-admin.atoms.text name="title_{{$locale}}" id="title_{{$locale}}" value="{{ $collection->getTranslation('title', $locale) }}" aria-invalid="false" />
                @error("title_{$locale}")
                    <x-admin.atoms.error>
                        {{ $message }}
                    </x-admin.atoms.error>
                @enderror
            </x-admin.atoms.row>
        @endforeach

        @foreach (config("translatable.locales") as $locale)
            <x-admin.atoms.row>
                <x-admin.atoms.label for="excerpt_{{$locale}}" class="required">
                    Excerpt ({{ $locale }})
                </x-admin.atoms.label>
                <x-admin.atoms.textarea name="excerpt_{{$locale}}" id="excerpt_{{$locale}}">{{ $collection->getTranslation("excerpt", $locale) }}</x-admin.atoms.textarea>
                @error("excerpt_{$locale}")
                    <x-admin.atoms.error>
                        {{ $message }}
                    </x-admin.atoms.error>
                @enderror
            </x-admin.atoms.row>
        @endforeach

        @foreach (config("translatable.locales") as $locale)
            <x-admin.atoms.row>
                <x-admin.atoms.label for="text_{{$locale}}" class="required">
                    Text ({{ $locale }})
                </x-admin.atoms.label>
                <x-admin.atoms.textarea name="text_{{$locale}}" id="text_{{$locale}}" class="laravel-product-tinymce">{{ $collection->getTranslation("text", $locale) }}</x-admin.atoms.textarea>
                @error("text_{$locale}")
                    <x-admin.atoms.error>
                        {{ $message }}
                    </x-admin.atoms.error>
                @enderror
            </x-admin.atoms.row>
        @endforeach

        <x-admin.atoms.row>
            <x-admin.atoms.label for="price" class="required">
                Price
            </x-admin.atoms.label>
            <x-admin.atoms.number id="price" name="price" value="{{ $collection->price }}" />
            @error('price')
                <x-admin.atoms.error>
                    {{ $message }}
                </x-admin.atoms.error>
            @enderror
        </x-admin.atoms.row>

        <x-admin.atoms.row>
            <x-admin.atoms.label for="quantity" class="required">
                Quantity
            </x-admin.atoms.label>
            <x-admin.atoms.number
                id="quantity"
                name="quantity"
                value="{{ $collection->quantity }}"
            />
            @error('quantity')
                <x-admin.atoms.error>
                    {{ $message }}
                </x-admin.atoms.error>
            @enderror
        </x-admin.atoms.row>

        <x-admin.atoms.row>
            <label class="text-gray-700 cursor-pointer">
                <span class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                    <input
                        type="checkbox"
                        name="on_sale"
                        id="on_sale"
                        class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer focus:outline-none focus:ring-offset-0 focus:ring-transparent"
                        {{ $collection->on_sale ? "checked": ""}}
                    />
                    <label for="on_sale"
                        class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                </span>
                On Sale
            </label>
        </x-admin.atoms.row>

        <hr class="my-10">

        <div class="text-right mt-4">
            <x-admin.atoms.link href="{{ route('LaravelShopping.index') }}">
                Cancel
            </x-admin.atoms.link>
            <x-admin.atoms.button type="submit">
                Save
            </x-admin.atoms.button>
        </div>
    </form>

    @push('js')
        <script src="{{ asset("gmj/js/laravel_shopping_init.js") }}"></script>
        <script>
            $("#myForm").validate({
                debug: false,
                rules: {
                    "category[]": "required",
                    "price":{
                        required: true,
                        digits: true
                    },
                    "quantity": {
                        required: true,
                        digits:true
                    }
                },
                errorPlacement: function(error, element) {
                    element.parent().append(error);
                }
            });

            @foreach (config('translatable.locales') as $locale)
                $("#title_{{ $locale }}").rules("add", {
                    required: true,
                });

                $("#excerpt_{{ $locale }}").rules("add", {
                    required: true,
                });

                $("#text_{{ $locale }}").rules("add", {
                    required: true,
                });
            @endforeach

            
            $('.select2').on('select2:select', function (e) {
                $("#category").valid();
            });
            
            //because the element is plug
            //it wrapper new element on input field
            //so it need run delegate
            //and after change it need time to update the element
            //so need setTimeout
            // only for create
            /* $(document).delegate("#banner", "change", function(){
                setTimeout(() => {
                    $("#uic_base64_banner").valid();    
                }, 400);
            }); */

           /*  $('.select2').on('select2:select', function (e) {
                $("name=category[]").valid();
            }); */



            //$('#myForm').valid();
        </script>
    @endpush
</x-admin.layout.app>