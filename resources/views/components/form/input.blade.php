@props(['label', 'help'])

<div class="mt-4">
    <x-jet-label :for="$attributes->get('id')">{{ $label }}</x-jet-label>
    <div class="mt-1">
        <x-jet-input {{ $attributes->merge(['type' => 'text']) }}/>
    </div>
    @error($attributes->get('id'))
        <x-jet-input-error :for="$attributes->get('id')"/>
    @enderror

    @if (!empty($help))
        <x-help>{{ $help }}</x-help>
    @endif
</div>
