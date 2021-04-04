<div x-data="{ opened: false }"
     class="relative inline-block text-left mx-1">
    <div>
        <button @click="opened = !opened"
                type="button"
                class="inline-flex justify-center w-full rounded-md border-2 border-gray-300 focus:border-gray-400 bg-white shadow-sm px-4 py-2 text-sm font-medium text-gray-700 focus:outline-none"
                id="options-menu" aria-haspopup="true" aria-expanded="true">
            <div class="ml-2 flex items-center">
                <p>{{ $option !== null ? $options[$option]['name'] : __('Sort By') }}</p>
                @if ($option !== null)
                    @if ($options[$option]['order'] === 'desc')
                        <x-icons.sort_descending class="h-4 w-4 ml-2"/>
                    @else
                        <x-icons.sort_ascending class="h-4 w-4 ml-2"/>
                    @endif
                @endif
                <x-icons.chevron_down ::class="{'rotate-180': opened}" class="-mr-1 ml-2 h-5 w-5 transform"/>
            </div>
        </button>
    </div>
    <div x-show="opened"
         x-cloak
         @click.away="opened = false"
         class="z-10 origin-top-right absolute right-0 mt-2 w-36 rounded-md shadow-xl bg-white ring-1 ring-black ring-opacity-5">
        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
            @foreach($options as $index => $value)
                <div class="flex m-2 rounded-md hover:bg-gray-200 cursor-pointer" @click="$wire.sortBy({{ $index }}); opened = false">
                    <div class="flex justify-center items-center px-4 py-2 text-sm text-gray-700" role="menuitem">
                        <a>{{ $value['name'] }}</a>
                        @if ($value['order'] === 'desc')
                            <x-icons.sort_descending class="h-4 w-4 ml-2"/>
                        @else
                            <x-icons.sort_ascending class="h-4 w-4 ml-2"/>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
