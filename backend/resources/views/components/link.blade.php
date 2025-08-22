@php
    $classes = "text-sm text-gray-600 hover:font-bold hover:text-gray-900 rounded-md underline sm:no-underline hover:underline focus:underline"
@endphp

<a {{ $attributes->merge(['class'=> $classes] ) }}>
    {{ $slot }}
</a>