<div class="py-12 sm:mx-5 md:mx=10 lg:mx-20">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-lg p-5 flex justify-center items-center">
            <div class="w-full lg:w-5/6 xl:w-4/6">
                <div class="flex justify-center items-center">
                    <video
                        id="video_js_player"
                        class="video-js vjs-16-9 vjs-big-play-centered"
                        controls
                        preload="none"
                        poster="{{ asset('storage/'.$video->thumbnail) }}"
                        data-setup='{}'>
                        <source src="{{ route('stream', $video->hash) }}" type="{{ $video->mimeType }}">
                    </video>
                </div>
                <div class="mt-4 grid grid-cols-3">
                    <a href="{{ route('creators.view', $video->creator) }}">
                        <img class="rounded-full w-16 h-16 object-cover"
                             src="{{ $video->creator->profilePictureUrl(true) }}">
                        <p class="text-gray-700 md:text-lg">{{ $video->creator->name }}</p>
                    </a>
                    <h1 class="col-span-2 text-sm md:text-lg lg:text-2xl font-semibold text-gray-900 text-right overflow-hidden overflow-ellipsis">
                        {{ $video->filename }}
                    </h1>
                </div>
            </div>
        </div>
    </div>
</div>

