<form wire:submit.prevent="import">
    <x-jet-dialog-modal wire:model="modalOpened">
        <x-slot name="title">
            {{ __('Import a creator from a URL') }}
        </x-slot>

        <x-slot name="content">
            <div class="text-gray-700 text-sm">
                <p>{{ __('Enter the name and URL of your creator') }}</p>
                <span>{{ __('The list of supported providers can be found') }}</span>&nbsp;<a class="underline" target="_blank" href="https://github.com/mikf/gallery-dl/blob/master/docs/supportedsites.md">{{ __('here') }}</a>
            </div>
            <x-form.input
                wire:model.defer="creatorName"
                id="creatorName"
                :label="__('Name')"
                :errors="$errors"
            />
            <x-form.input
                wire:model.defer="creatorUrl"
                id="creatorUrl"
                class="w-full"
                :label="__('URL')"
                :errors="$errors"
            />
            <x-form.input-autocomplete
                wire:model.defer="path"
                class="w-full"
                :label="__('Path')"
                :help="__('The creator root path')"
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
