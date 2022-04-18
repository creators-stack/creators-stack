<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="py-5 flex flex-col md:flex-row justify-between content-center items-center space-y-5 md:space-y-0">
                <x-search wire:model.debounce.250ms="search" :placeholder="__('Search')" class="ml-4 md:ml-10 px-5 pr-16"/>
                <div class="flex justify-center items-center">
                    <x-jet-secondary-button wire:click="$emitTo('import-creator-from-url', 'toggleModal')" type="button" class="mr-4">
                        {{ __('Import From URL') }}
                    </x-jet-secondary-button>
                    <x-jet-secondary-button wire:click="$emitTo('import-creator-from-disk', 'toggleModal')" type="button" class="mr-4">
                        {{ __('Mass Import From Disk') }}
                    </x-jet-secondary-button>
                    <a href="{{ route('creators.create') }}">
                        <x-jet-secondary-button type="button" class="mr-4">
                            {{ __('New') }}
                        </x-jet-secondary-button>
                    </a>
                </div>
            </div>
            @livewire('import-creator-from-disk')
            @livewire('import-creator-from-url')
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
