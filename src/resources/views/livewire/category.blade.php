<div>
    @php
        $breadcrumbs = [
            ['name' => "Product", "link" => route("LaravelShopping.index")],
            ['name' => "Category"],
        ];
    @endphp
    <x-admin.atoms.breadcrumb :breadcrumbs="$breadcrumbs" />

    <div class="relative mt-4">
        <div class="flex justify-between items-center">
            <div class="flex w-1/2 items-center">
                <div class="w-1/2">
                    <x-admin.atoms.text placeholder="title" wire:model.lazy="filter_title" />
                </div>
            </div>
            <div>
                <x-admin.atoms.button type="button" wire:click="create">
                    ADD
                </x-admin.atoms.button>
                <x-admin.atoms.button type="button" wire:click="order">
                    Order
                </x-admin.atoms.button>
            </div>
        </div>
        
        <div>
            <x-admin.atoms.index-header>
                <div class="flex-1">Title</div>
                <div class="flex-1"></div>
            </x-admin.atoms.index-header>

            @foreach ($collections as $item)
                <div class="flex items-center space-x-2 p-3 text-gray-800" ref="ref_{{ $item->id }}">
                    <div class="flex-1">{{ $item->title }}</div>
                    <div class="flex-1">
                        <x-admin.atoms.button type='button' wire:click="edit({{$item}})">
                            Edit
                        </x-admin.atoms.button>
                        <x-admin.atoms.button class="remove" data-id="{{ $item->id }}">
                            Delete
                        </x-admin.atoms.button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if ($mode === "create" || $mode === "edit")
        <div class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-70">
            <div class="container mx-auto relative top-10 bg-gray-100 rounded-xl overflow-hidden p-8">
                <h2 class="uppercase text-xl font-bold">{{ $mode }} Category</h2>
                @foreach (config("translatable.locales") as $locale)
                    <x-admin.atoms.row>
                        <x-admin.atoms.label for="title_{{$locale}}" class="required">
                            Title ({{ $locale }})
                        </x-admin.atoms.label>
                        <x-admin.atoms.text  wire:model.lazy="data.title_{{$locale}}" id="title_{{$locale}}" />
                        @error("data.title_{$locale}")
                            <x-admin.atoms.error>
                                {{ $message }}
                            </x-admin.atoms.error>
                        @enderror
                    </x-admin.atoms.row>
                @endforeach

                {{-- <x-admin.atoms.row>
                    <x-admin.atoms.label for="img_width" class="required">
                        Image Width
                    </x-admin.atoms.label>
                    <x-admin.atoms.number id="img_width" wire:model.lazy="data.img_width" />
                    @error('data.img_width')
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </x-admin.atoms.row>

                <x-admin.atoms.row>
                    <x-admin.atoms.label for="img_height" class="required">
                        Image Height
                    </x-admin.atoms.label>
                    <x-admin.atoms.number id="img_height" wire:model.lazy="data.img_height" />
                    @error("data.img_height")
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </x-admin.atoms.row> --}}

                {{-- <x-admin.atoms.row>
                    <x-admin.atoms.label for="layout" class="required">Layout</x-admin.atoms.label>
                    <x-admin.atoms.select id="layout" wire:model.lazy="data.layout">
                        <option value="">--Please Select--</option>
                        @foreach (config("gmj.laravel_shopping_config.layouts") as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </x-admin.atoms.select>
                    @error('data.layout')
                        <x-admin.atoms.error>
                            {{ $message }}
                        </x-admin.atoms.error>
                    @enderror
                </x-admin.atoms.row> --}}

                <hr class="my-10">

                <div class="text-right mt-4">
                    <x-admin.atoms.button wire:click.prevent="close">
                        Cancel
                    </x-admin.atoms.button>
                    <x-admin.atoms.button wire:click.prevent="save">
                        Save
                    </x-admin.atoms.button>
                </div>
            </div>
        </div>
    @endif

    @if ($mode === "order")
        <form id="orderForm" class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-70" x-init="$('#order').sortable();">
            <div class="container mx-auto relative top-10 bg-gray-100 rounded-xl overflow-hidden p-8">
                <h2 class="uppercase text-xl font-bold">{{ $mode }} Category</h2>
                <div id="order">
                    @foreach ( GMJ\LaravelShopping\Models\Category::orderBy("display_order")->pluck("title", 'id') as $key => $value)
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