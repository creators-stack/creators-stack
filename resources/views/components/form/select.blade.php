@props(['options'])

<select {{ $attributes->class('cursor-pointer inline-flex justify-center rounded-md border-2 border-gray-300 focus:shadow-none focus:border-gray-400 bg-white shadow-sm text-sm font-medium text-gray-700 focus:outline-none') }}>
    <option value="" disabled="disabled">{{ __('Choose an option') }}</option>
    @foreach($options as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
</select>
