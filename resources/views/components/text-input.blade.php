@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'block w-full rounded-md shadow-sm
                    bg-white text-gray-900 border-gray-300 placeholder-gray-400
                    focus:border-indigo-500 focus:ring-indigo-500
                    dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700 dark:placeholder-gray-500
                    dark:focus:border-indigo-400 dark:focus:ring-indigo-400'
    ]) !!}
>
