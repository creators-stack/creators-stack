<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="py-5 flex flex-col md:flex-row justify-between content-center items-center space-y-5 md:space-y-0">
                <x-search wire:model.debounce.250ms="search" :placeholder="__('Search')" class="ml-4 md:ml-10 px-5 pr-16"/>
                <div class="flex justify-center items-center">
                    <x-jet-secondary-button wire:click="$toggle('opened')" type="button" class="mr-4">
                        {{ __('Create From Disk') }}
                    </x-jet-secondary-button>
                    <a href="{{ route('creators.create') }}">
                        <x-jet-secondary-button type="button" class="mr-4">
                            {{ __('New') }}
                        </x-jet-secondary-button>
                    </a>
                </div>
            </div>
            <form wire:submit.prevent="createFromDisk">
                <x-jet-dialog-modal wire:model="opened">
                    <x-slot name="title">
                        {{ __('Create Creators From Disk') }}
                    </x-slot>

                    <x-slot name="content">
                        <div class="text-gray-700 text-sm">
                            <p>{{ __('Enter the root path of your creators, one creator will be created per folder in the given path') }}</p>
                            <p>{{ __('You can then crawl creators files and generate thumbnails / previews from the settings') }}</p>
                        </div>
                        <x-form.input-autocomplete
                            wire:model.defer="path"
                            class="w-full"
                            placeholder="/creators_path"
                            :label="__('Creators path')"
                            :help="__('Press tab to apply suggestion')"
                        />
                    </x-slot>

                    <x-slot name="footer">
                        <x-jet-secondary-button wire:click="$toggle('opened')" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-jet-secondary-button>
                        <x-jet-button class="mr-4">
                            {{ __('Create') }}
                        </x-jet-button>
                    </x-slot>
                </x-jet-dialog-modal>
            </form>
            @if ($creators->isNotEmpty())
                <div class="flex flex-wrap">
                    @foreach($creators as $creator)
                        <div class="my-1 px-1 w-full sm:w-full md:w-1/2 lg:w-1/3 xl:w-1/4 lg:my-4 lg:px-4">
                            <div class="flex justify-center items-center">
                                <div class="container mx-auto max-w-xs overflow-hidden my-2">
                                    <a href="{{ route('creators.view', $creator) }}">
                                        <div class="relative mb-6">
                                            <img class="w-full object-cover h-96 rounded-md"
                                                 src="{{ $creator->profilePictureUrl() }}"
                                                 loading="lazy"
                                                 alt="{{ __('Profile picture') }}"/>
                                            <div class="text-center absolute w-full -bottom-5">
                                                <div class="mb-10">
                                                    <a class="@if ($creator->profile_picture) text-white @else text-gray-700 @endif tracking-wide uppercase text-lg font-bold">{{ $creator->name }}</a>
                                                    <p class="@if ($creator->profile_picture) text-gray-200 @else text-gray-800 @endif text-sm">{{ '@'.$creator->username }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="py-5 px-3 text-center tracking-wide grid grid-cols-2 gap-6">
                                        <div>
                                            <p class="text-lg">{{ $creator->images_count }}</p>
                                            <p class="text-gray-500 text-sm">{{ __('Images') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-lg">{{ $creator->videos_count }}</p>
                                            <p class="text-gray-500 text-sm">{{ __('Videos') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <x-help class="text-center text-xl text-gray-600 mb-8">{{ __('No Creators found') }}</x-help>
            @endif
        </div>
        <div class="m-5">
            {{ $creators->links() }}
        </div>
    </div>
</div>
