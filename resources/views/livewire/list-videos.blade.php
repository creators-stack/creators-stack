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
                @if ($videos->isNotEmpty())
                    <div x-data="initVideoList()" class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach($videos as $video)
                            <a href="{{ route('video', $video->hash) }}"
                               wire:key="wire_video_{{ $video->id }}"
                               class="lazy-load lazy-load-video"
                               @if ($video->preview)
                                   @mouseenter="playVideoAfter('video_{{ $video->id }}', $event.target, 50)"
                                   @focusin="playVideo('video_{{ $video->id }}')"
                                   @touchstart="playVideo('video_{{ $video->id }}')"
                                   @mouseleave="pauseVideo('video_{{ $video->id }}')"
                                   @focusout="pauseVideo('video_{{ $video->id }}')"
                                   @touchstart.away="pauseVideo('video_{{ $video->id }}')"
                               @endif
                            >
                                @if ($video->preview)
                                    <video id="video_{{ $video->id }}"
                                           poster="{{ asset('storage/'.$video->thumbnail) }}"
                                           width="{{ \App\Models\File::PREVIEW_WIDTH }}"
                                           height="{{ \App\Models\File::PREVIEW_HEIGHT }}"
                                           class="mx-auto rounded-lg event pointer-events-none"
                                           src="{{ asset('storage/'.$video->preview) }}"
                                           loop
                                           @if ($settings->mute_videos_previews)
                                               muted="muted"
                                           @endif
                                           preload="none">
                                    </video>
                                @else
                                    <img class="mx-auto rounded-lg"
                                    src="{{ asset('storage/'.$video->thumbnail) }}"
                                    width="{{ \App\Models\File::PREVIEW_WIDTH }}"
                                    height="{{ \App\Models\File::PREVIEW_HEIGHT }}"
                                    loading="lazy">
                                @endif
                            </a>
                        @endforeach
                    </div>
                @else
                    <x-help class="text-center text-xl text-gray-600 mb-8">{{ __('No videos found') }}</x-help>
                @endif
            </div>
        </div>
        @if ($videos->isNotEmpty())
            <div class="m-5">
                {{ $videos->links() }}
            </div>
        @endif
    </div>
</div>
<script>
    function initVideoList() {
        return {
            playVideoAfter(id, el, duration) {
                const self = this;

                setTimeout(() => {
                    if (el.matches(':hover')) {
                        self.playVideo(id);
                    }
                }, duration);
            },
            playVideo(id) {
                document.getElementById(id).play();
            },
            pauseVideo(id) {
                document.getElementById(id).pause();
            },
        }
    }
</script>
