<form wire:submit.prevent="import">
    <x-jet-dialog-modal wire:model="modalOpened">
        <x-slot name="title">
            {{ __('Import Creators From Disk') }}
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
            <x-jet-secondary-button wire:click="$toggle('modalOpened')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>
            <x-jet-button class="mr-4">
                {{ __('Import') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</form>
