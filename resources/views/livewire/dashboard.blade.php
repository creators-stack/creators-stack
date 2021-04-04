<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200 flex justify-center items-center">
                <div>
                    <x-jet-application-logo class="block h-12 w-auto" />
                </div>
                <div class="ml-5 text-3xl">Creators Stash</div>
                <p class="ml-2 text-sm">V{{ config('app.version') }}</p>
            </div>
            <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-3">
                <div class="p-6">
                    <div class="flex items-center">
                        <x-icons.user class="w-8 h-8 text-gray-400"/>
                        <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><a href="{{ route('creators') }}">{{ __('Creators') }}</a></div>
                    </div>
                    <div class="ml-12 mt-5 text-gray-800">
                        <p><span class="font-bold">{{ $creators_count }}</span>&nbsp;{{ __('Creators') }}</p>
                        <p>{!! $total_size !!} {{ __('Total') }}</p>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l">
                    <div class="flex items-center">
                        <x-icons.image class="w-8 h-8 text-gray-400"/>
                        <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><a href="{{ route('images') }}">{{ __('Images') }}</a></div>
                    </div>

                    <div class="ml-12 mt-5 text-gray-800">
                        <p><span class="font-bold">{{ $images_count }}</span>&nbsp;{{ __('Images') }}</p>
                        <p>{!! $images_size !!}</p>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l">
                    <div class="flex items-center">
                        <x-icons.video class="w-8 h-8 text-gray-400"/>
                        <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><a href="{{ route('videos') }}">{{ __('Videos') }}</a></div>
                    </div>

                    <div class="ml-12 mt-5 text-gray-900">
                        <p><span class="font-bold">{{ $videos_count }}</span>&nbsp;{{ __('Videos')}}</p>
                        <p>{!! $videos_size !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
