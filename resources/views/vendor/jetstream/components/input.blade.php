@props(['disabled' => false])
@php($error = $errors->has($attributes->get('id')))

<input @if ($disabled) 'disabled' @endif {!! $attributes->class([
    'border-gray-300' => !$error,
    'border-red-300' => $error,
    'focus:border-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50 rounded-md shadow-sm',
])->merge(['type' => 'text']) !!}>
