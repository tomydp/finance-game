@props(['text'])

<td class="px-6 py-4 max-w-xs relative group overflow-visible">
    <div class="truncate">
        {{ $text }}
    </div>

    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 bg-white border border-gray-300 shadow-xl rounded-lg p-3 text-sm text-gray-800 hidden group-hover:block z-50 whitespace-normal break-words">
        {{ $text }}
    </div>
</td>
