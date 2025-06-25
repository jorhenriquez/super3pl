@switch($name)
    @case('edit')
        <x-heroicon-o-pencil class="size-6" />
        @break
    @case('trash')
        <x-heroicon-o-trash class="size-6" />
        @break
    @case('refresh')
        {{-- Manual SVG fallback for refresh (arrow-path) --}}
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
        </svg>
        @break
    @case('user-minus')
        {{-- Manual SVG fallback for user-minus --}}
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 19.25a7.5 7.5 0 0 1 15 0v.25a2.25 2.25 0 0 1-2.25 2.25h-10.5A2.25 2.25 0 0 1 4.5 19.5v-.25ZM16 11.25h6" />
        </svg>
        @break

    @case('user-plus')
        {{-- Manual SVG fallback for user-plus --}}
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 19.25a7.5 7.5 0 0 1 15 0v.25a2.25 2.25 0 0 1-2.25 2.25h-10.5A2.25 2.25 0 0 1 4.5 19.5v-.25ZM18 10.75v6m3-3h-6" />
        </svg>
        @break

    @default
        <!-- Si no hay ícono definido -->
        <span class="text-xs text-gray-500">[icono]</span>
@endswitch
