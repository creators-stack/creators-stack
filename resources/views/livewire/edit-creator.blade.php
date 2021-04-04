<div class="py-12 sm:mx-5 md:mx=10 lg:mx-20">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-lg p-5">
            <form wire:submit.prevent="save" class="ml-5">
                <div class="flex flex-col md:flex-row space-x-4 justify-between mb-4">
                    <div class="md:w-1/2">
                        <x-form.input
                            wire:model.defer="creator.name"
                            class="md:w-2/3"
                            id="creator.name"
                            :label=" __('Name')"
                            :errors="$errors"
                        />
                        <x-form.input
                            wire:model.defer="creator.username"
                            class="md:w-2/3"
                            id="creator.username"
                            :label=" __('Username')"
                            :errors="$errors"
                        />
                        <x-form.input-autocomplete
                            wire:model.defer="path"
                            class="w-full"
                            placeholder="/creator/path"
                            :label="__('Content path')"
                            :help="__('Press tab to apply suggestion')"
                        />
                    </div>
                    <div x-data>
                        <img class="width:auto max-h-1/2" src="{{ $profile_picture ? $profile_picture->temporaryUrl() : $creator->profilePictureUrl() }}">
                        <div class="inline-flex flex-col">
                            <input class="mt-2 hidden" type="file" accept="image/x-png,image/gif,image/jpeg" wire:model="profile_picture" x-ref="profile_picture">
                            <x-jet-secondary-button class="mt-2 mr-2 w-auto" type="button" @click="$refs.profile_picture.click()">
                                {{ __('Select A New Profile Picture') }}
                            </x-jet-secondary-button>

                            @error('creator.profile_picture')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <x-jet-button>
                    {{ __('Save') }}
                </x-jet-button>
            </form>
        </div>
    </div>
</div>
