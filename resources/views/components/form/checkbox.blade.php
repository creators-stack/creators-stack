@props(['label', 'help'])

<div class="col-span-6 sm:col-span-4 flex my-2">
    <x-jet-checkbox {{ $attributes }} />
    <div class="ml-4">
        <x-jet-label :for="$attributes->get('id')">{{ $label }}</x-jet-label>
        @if (!empty($help))
            <x-help>{{ $help }}</x-help>
        @endif
    </div>

</div>
