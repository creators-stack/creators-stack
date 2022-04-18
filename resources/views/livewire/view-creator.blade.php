<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-7 relative">
            <div class="absolute top-2 right-2 flex">
                @if (null !== $creator->url)
                <button wire:click="sync">
                    <x-icons.cloud_download class="w-5 h-5 m-1 opacity-75 cursor-pointer"/>
                </button>
                @endif
                <button wire:click="crawl">
                    <x-icons.server class="w-5 h-5 m-1 opacity-75 cursor-pointer"/>
                </button>
                <a href="{{ route('creators.edit', $creator) }}">
                    <x-icons.pencil class="w-5 h-5 m-1 opacity-75"/>
                </a>
                <button wire:click="$toggle('opened')">
                    <x-icons.trash class="w-5 h-5 m-1 opacity-75 cursor-pointer"/>
                </button>
            </div>
            <x-jet-confirmation-modal wire:model="opened">
                <x-slot name="title">
                    {{ __('Delete Creator') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you would like to delete this creator ?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$toggle('opened')" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-jet-secondary-button>

                    <x-jet-danger-button class="ml-2" wire:click="deleteCreator" wire:loading.attr="disabled">
                        {{ __('Delete') }}
                    </x-jet-danger-button>
                </x-slot>
            </x-jet-confirmation-modal>

            <div wire:poll class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <img class="w-full object-cover rounded-md max-h-1/2"
                         src="{{ $creator->profilePictureUrl() }}"
                         alt="{{ __('Profile picture') }}"/>
                </div>
                <div class="col-span-2 text-center">
                    <h1 class="text-4xl">{{ $creator->name }}</h1>
                    <p>{{ '@'.$creator->username }}</p>
                    <p class="text-sm">{!! $creator->totalHumanSize() !!}</p>
                    <div class="mt-4 py-5 px-3 text-center grid grid-cols-2 lg:grid-cols-4">
                        <a class="lg:col-start-2" href="{{ route('images', ['creator_username' => $creator->username]) }}">
                            <p class="text-xl">{{ $creator->images_count }}</p>
                            <p class="text-gray-700 text-md">{{ __('Images') }}</p>
                        </a>
                        <a href="{{ route('videos', ['creator_username' => $creator->username]) }}">
                            <p class="text-xl">{{ $creator->videos_count }}</p>
                            <p class="text-gray-700 text-md">{{ __('Videos') }}</p>
                        </a>
                    </div>
                    @if ($batch)
                        @if ($batch->cancelled() === false && $batch->finished() === false)
                            <div class="flex flex-col justify-center items-center mt-4">
                                <div class="w-5/6">
                                    <p class="text-left text-sm font-italic text-gray-600 mb-1">{{ __('Generating content...') }}</p>
                                    <div class="flex justify-center items-center">
                                        <div class="shadow w-full bg-grey-light rounded shadow">
                                            <div
                                                class="bg-indigo-500 text-xs leading-none py-1 text-center text-white rounded"
                                                style="width: {{ $batch->progress() }}%">
                                                {{ $batch->progress() }}%
                                            </div>
                                        </div>
                                        <a class="cursor-pointer" wire:click="cancelBatch">
                                            <x-icons.close class="w-4 h-4 ml-1"/>
                                        </a>
                                    </div>
                                    @if ($batch->hasFailures())
                                        <p class="text-left text-xs text-red-500 mt-1">{{ __(':count failed jobs', ['count' => $last_batch->failedJobs]) }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center mt-10">
                                <p wire:loading.remove wire:target="crawl" class="text-gray-600 text-md">Last crawled {{ $batch->finishedAt->diffForHumans() }}</p>
                                <p wire:loading wire:target="crawl" class="text-gray-600 text-md">Crawling...</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
