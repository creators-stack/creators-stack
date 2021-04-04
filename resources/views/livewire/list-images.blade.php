<div class="py-12 sm:mx-5 md:mx=10 lg:mx-20">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-5">
            <div class="py-5 flex flex-col md:flex-row justify-between content-center items-center space-y-5 md:space-y-0">
                <x-search wire:model.debounce.250ms="search" :placeholder="__('Search')" class="ml-10 px-5 pr-16"/>
                <div class="flex justify-center items-center">
                    <livewire:sort-dropdown-select :field="$this->sort_by" :order="$this->sort_order"/>
                    <livewire:creators-dropdown-select :creator="$this->creator" :has="$this->has"/>
                </div>
            </div>
            <div>
                @if ($images->isNotEmpty())
                    <div id="lightgallery" class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
                        @foreach($images as $image)
                            <a href="{{ route('image', $image) }}" class="rounded-lg lazy-load">
                                <img class="mx-auto rounded-lg"
                                     width="{{ \App\Models\File::PREVIEW_HEIGHT }}"
                                     height="{{ \App\Models\File::PREVIEW_WIDTH }}"
                                     src="{{ asset('storage/'.$image->thumbnail) }}"
                                     loading="lazy">
                            </a>
                        @endforeach
                    </div>
                @else
                    <x-help class="text-center text-xl text-gray-600 mb-8">{{ __('No images found') }}</x-help>
                @endif
            </div>
        </div>
        @if ($images->isNotEmpty())
            <div class="m-5">
                {{ $images->links() }}
            </div>
        @endif
    </div>
</div>
