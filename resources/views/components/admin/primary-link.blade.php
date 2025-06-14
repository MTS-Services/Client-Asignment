{{-- <button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-1 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button> --}}

@props(['href' => 'javascript:void(0)', 'target' => '_self', 'secondary' => false, 'disabled' => false])

<a @disabled($disabled) href="{{ $href }}" target="{{ $target }}"
    {{ $attributes->merge(['title' => '', 'class' => 'btn btn-sm' . ($secondary ? ' btn-secondary' : ' btn-primary')]) }}>
    {{ $slot }}
</a>



{{-- @props(['error' => false])
<a
    {{ $attributes->merge(['href' => '','title' => '' , 'target' => '_self', 'class' => 'inline-flex items-center px-4 py-2 btn-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150' . ($error ? 'btn btn-sm btn-secondary' : 'btn-primary')]) }}>
    {{ $slot }}
</a> --}}
