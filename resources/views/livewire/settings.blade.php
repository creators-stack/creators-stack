<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="ml-2 md:col-span-1">
            <h3 class="text-lg font-medium text-gray-900">
                {{ __('Crawler') }}
            </h3>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('Update the file crawler settings.') }}
            </p>
        </div>
        <form wire:submit.prevent="saveSettings" class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-4">
                            <div>
                                @if (!$last_batch || $last_batch->finished())
                                    <div class="flex items-center px-4 py-3 text-right sm:px-6">
                                        <x-jet-button wire:loading.remove
                                                      wire:target="crawl"
                                                      wire:click="crawl"
                                                      type="button"
                                                      class="mr-4">
                                            {{ __('Crawl') }}
                                        </x-jet-button>
                                        @if ($last_batch && $last_batch->finished())
                                            <p class="text-gray-600 text-md" wire:loading.remove wire:target="crawl">
                                                Last crawled {{ $last_batch->finishedAt->diffForHumans() }}</p>
                                        @endif
                                        <p wire:loading wire:target="crawl" class="text-gray-600 text-md">
                                            Crawling...</p>
                                    </div>
                                @else
                                    <div wire:poll class="mb-10">
                                        <p class="text-left text-sm font-italic text-gray-600 mb-1">{{ __('Generating content ...') }}</p>
                                        <div class="flex justify-center items-center">
                                            <div class="shadow w-full bg-grey-light rounded shadow">
                                                <div
                                                    class="bg-indigo-500 text-xs leading-none py-1 text-center text-white rounded"
                                                    style="width: {{ $last_batch->progress() }}%">
                                                    {{ $last_batch->progress() }}%
                                                </div>
                                            </div>
                                            <x-icons.close wire:click="cancelBatch"
                                                           class="w-4 h-4 ml-1 cursor-pointer"/>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <x-form.checkbox
                                wire:model.lazy="settings.crawl_based_on_file_extension"
                                id="crawl_based_on_file_extension"
                                :label="__('Base content detection on file extension')"
                                :help="__('If disabled, mime type will be used instead (slower)')"
                            />
                            @if ($settings->crawl_based_on_file_extension)
                                <div class="ml-7">
                                    <x-form.input
                                        wire:model.defer="image_extensions"
                                        id="image_extensions"
                                        :label="__('Images extensions')"
                                        :help="__('Comma separated list of accepted image extensions')"
                                    />
                                    <x-form.input
                                        wire:model.defer="video_extensions"
                                        id="video_extensions"
                                        :label="__('Videos extensions')"
                                        :help="__('Comma separated list of accepted video extensions')"
                                    />
                                </div>
                            @endif
                            <x-form.checkbox
                                wire:model.lazy="settings.generate_videos_preview"
                                id="generate_videos_preview"
                                :label="__('Generate video previews')"
                                :help="__('Previews will play on mouse hover on videos listing (slower)')"
                            />
                            @if ($settings->generate_videos_preview)
                                <div>
                                    <x-form.checkbox
                                        wire:model.defer="settings.mute_videos_preview"
                                        id="mute_videos_preview"
                                        :label="__('Mute videos preview')"
                                    />
                                    <div class="ml-7">
                                        <x-form.input
                                            wire:model.defer="settings.videos_preview_parts_count"
                                            id="settings.videos_preview_parts_count"
                                            :label="__('Preview parts count')"
                                            :help="__('Number of parts in the preview')"
                                        />
                                        <x-form.input
                                            wire:model.defer="settings.videos_preview_parts_length"
                                            id="settings.videos_preview_parts_length"
                                            :label="__('Preview parts length')"
                                            :help="__('Length of each parts in the preview in ms')"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <x-jet-action-message class="mr-3" on="saved">
                        {{ __('Saved.') }}
                    </x-jet-action-message>
                    <x-jet-button>
                        {{ __('Save') }}
                    </x-jet-button>
                </div>
            </div>
        </form>
    </div>
</div>
