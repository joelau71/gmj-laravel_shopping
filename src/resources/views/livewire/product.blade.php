<div>
    @php
        $breadcrumbs = [
            ['name' => "Product"]
        ];
    @endphp
    <x-admin.atoms.breadcrumb :breadcrumbs="$breadcrumbs" />

    <div class="relative mt-4">
        <div class="flex justify-between items-center">
            <div class="flex w-1/2 items-center">
                <div class="w-1/2">
                    <x-admin.atoms.text type="text" placeholder="title" wire:model.lazy="filter_title" />
                </div>
                <div class="w-1/2 ml-3">
                    <x-admin.atoms.select>
                        <x-slot name="wire">
                            wire:model.lazy="filter_category"
                        </x-slot>
                        <option value="">--</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->title }}
                            </option>
                        @endforeach
                    </x-admin.atoms.select>
                </div>
            </div>
            <div>
                <x-admin.atoms.link href="{{ route('LaravelShopping.create') }}">
                    ADD
                </x-admin.atoms.link>
                <x-admin.atoms.link href="{{ route('LaravelShoppingCategory') }}">
                    Category
                </x-admin.atoms.link>
                <x-admin.atoms.button wire:click="order">
                    Order
                </x-admin.atoms.button>
            </div>
        </div>
        
        <div>
            <x-admin.atoms.index-header>
                <div class="flex-1">Title</div>
                <div class="flex-1">Category</div>
                <div class="w-20">Quantity</div>
                <div class="flex-1"></div>
            </x-admin.atoms.index-header>

            @foreach ($collections as $item)
                <div class="flex items-center space-x-2 p-3 text-gray-800" ref="ref_{{ $item->id }}">
                    <div class="flex-1">{{ $item->title }}</div>
                    <div class="flex-1">
                        @foreach ($item->categories as $category)
                            <span>({{ $category->title }})</span>
                        @endforeach
                    </div>
                    <div class="w-20">
                        {{ $item->quantity }}
                    </div>
                    <div class="flex-1">
                        <x-admin.atoms.link href="{{ route('LaravelShopping.edit', $item->id) }}">
                            Edit
                        </x-admin.atoms.link>
                        <x-admin.atoms.button class="remove" data-id="{{ $item->id }}">
                            Delete
                        </x-admin.atoms.button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

   {{--  @if ($mode === "create" || $mode === "edit")
        <form id="myForm" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-70" x-init="$('.select2').select2();uploadImageCopper.destroy();uploadImageCopper.init();tinymce.remove();tinymceInit();">
            <div class="container mx-auto relative top-10 bg-gray-100 rounded-xl overflow-y-auto p-8 h-5/6">
                <h2 class="uppercase text-xl font-bold">{{ $mode }} Product</h2>

                <x-admin.atoms.row>
                    <x-admin.atoms.label for="banner" class="required mb-2">
                        Banner
                    </x-admin.atoms.label>
                    <div wire:ignore>
                        <input
                            type="file"
                            name="data.banner"
                            id="banner"
                            class="upload-image-copper field"
                            data-uic-display-width="768"
                            data-uic-display-height="375"
                            data-uic-target-width="1920"
                            data-uic-target-height="937"
                            data-uic-title="Size: 1920(w) x 937(h)"
                        />
                    </div>
                    @error("data.banner")
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </x-admin.atoms.row>

                
                <x-admin.atoms.row>
                    <x-admin.atoms.label for="category" class="mb-2">Category</x-admin.atoms.label>
                    <div wire:ignore>
                        <x-admin.atoms.select id="category" name="data.category" multiple class="select2 field">
                            <option value="">--Please Select--</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </x-admin.atoms.select>
                    </div>
                    @error("data.category")
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
                        <x-admin.atoms.text wire:model.lazy="data.title_{{$locale}}" id="title_{{$locale}}" />
                        @error("data.title_{$locale}")
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
                        <x-admin.atoms.textarea wire:model.lazy="data.excerpt_{{$locale}}" id="excerpt_{{$locale}}" />
                        @error("data.excerpt_{$locale}")
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
                        <div wire:ignore>
                            <x-admin.atoms.textarea wire:model.lazy="data.text_{{$locale}}" id="text_{{$locale}}" class="tinymce-textarea" />
                        </div>
                        @error("data.text_{$locale}")
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
                    <x-admin.atoms.number id="price" wire:model.lazy="data.price" />
                    @error('data.price')
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </x-admin.atoms.row>

                <x-admin.atoms.row>
                    <x-admin.atoms.label for="quantity" class="required">
                        Quantity
                    </x-admin.atoms.label>
                    <x-admin.atoms.number id="quantity" wire:model.lazy="data.quantity" />
                    @error('data.quantity')
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </x-admin.atoms.row>

                <hr class="my-10">

                <div class="text-right mt-4">
                    <x-admin.atoms.button type="button" wire:click.prevent="close">Cancel</x-admin.atoms.button>
                    <x-admin.atoms.button type="button" wire:click.prevent="save">Save</x-admin.atoms.button>
                </div>
            </div>
        </form>
    @endif --}}

    @if ($mode === "order")
        <form id="orderForm" class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-70" x-init="$('#order').sortable();">
            <div class="container mx-auto relative top-10 bg-gray-100 rounded-xl overflow-hidden p-8">
                <h2 class="uppercase text-xl font-bold">{{ $mode }} Category</h2>
                <div id="order">
                    @foreach ( GMJ\LaravelShopping\Models\Product::orderBy("display_order")->pluck("title", 'id') as $key => $value)
                        <x-admin.atoms.display-order-item>
                            <div>{{ $value }}</div>
                            <input type="hidden" name="id[]" value="{{ $key }}">
                        </x-admin.atoms.display-order-item>
                    @endforeach
                </div>

                <hr class="my-10">

                <div class="text-right mt-4">
                    <x-admin.atoms.button type="button" wire:click.prevent="close">
                        Cancel
                    </x-admin.atoms.button>
                    <x-admin.atoms.button type="button" id="save">
                        Save
                    </x-admin.atoms.button>
                </div>
            </div>
        </form>
    @endif


    @push('js')
        <script>
            $(document).delegate("#save", "click", function(e){
                const data = $("#orderForm").serializeArray();
                Livewire.emit('saveChnageDisplayOrder', data);
            });

            $(".remove").on("click", function(event) {
                const $this = $(this);
                const id = $this.attr("data-id");
                event.preventDefault();

                Swal.fire({
                    title: 'Are you sure to Delete?',
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                }).then(res => {
                    if (res.isConfirmed) {
                        Livewire.emit('delete', id);
                    }
                });
            });
        </script>
    @endpush
</div>