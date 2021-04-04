<div x-data="{ opened: {{ $this->opened ? 'true' : 'false' }}, creators: {{ $creators }} }"
     class="relative inline-block text-left mx-1">
    <div>
        <button @click="opened = !opened"
                type="button"
                class="inline-flex justify-center w-full rounded-md border-2 border-gray-300 focus:border-gray-400 bg-white shadow-sm px-4 py-2 text-sm font-medium text-gray-700 focus:outline-none"
                id="options-menu" aria-haspopup="true" aria-expanded="true">
            <div class="ml-2 flex items-center">
                @if (!empty($creator))
                    <img class="w-8 h-8 object-cover rounded-full mr-2" src="{{ $creator->profilePictureUrl(true) }}">
                @endif
                <p>{{ $creator->name ?? __('Creator') }}</p>
                    @if(!empty($creator))
                        <div @click.stop="$wire.resetCreator(); opened = false"
                            class="z-20">
                            <x-icons.close class="w-4 h-4 ml-1"/>
                        </div>
                    @endif
                    <x-icons.chevron_down ::class="{'rotate-180': opened}" class="-mr-1 ml-2 h-5 w-5 transform"/>
            </div>
        </button>
    </div>
    <div x-show="opened"
         x-cloak
         @click.away="opened = false"
         class="z-10 origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-xl bg-white ring-1 ring-black ring-opacity-5">
        <div class="py-1 z-10" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
            <x-search wire:model.debounce.250ms="creator_query" class="m-4 pl-2" :placeholder="__('Search Creator...')"/>
            <template x-for="creator in creators">
                <div class="flex m-2 rounded-md hover:bg-gray-200 cursor-pointer"
                     @click="$wire.selectCreator(creator.id); opened = false">
                    <img class="m-1 w-8 h-8 object-cover rounded-full"
                         :src="creator.profile_picture">
                    <a class="block px-4 py-2 text-sm text-gray-700"
                       x-text="creator.name"
                       role="menuitem"></a>
                </div>
            </template>
            <template x-if="creators.length === 0">
                <p class="text-sm text-gray-700 mx-4 mb-2">{{ __('No creators found') }}</p>
            </template>
        </div>
    </div>
</div>
