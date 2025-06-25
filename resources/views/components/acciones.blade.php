<div class="flex space-x-3 items-center">

    @php
        $query = request()->getQueryString(); // devuelve: search=abc&page=2
        $q = $query ? ('?' . $query) : '';
        $estado = $row->estado_pedido->nombre ?? null;
        // Extra: construir query solo con search_pedido si existe
        $searchOnly = request('search_pedido') ? ('?search_pedido=' . urlencode(request('search_pedido'))) : '';
    @endphp

    @if($estado === "Creado")
        <!-- Editar -->
        <a href="{{ route('pedidos.edit', $row->id).$q }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
            <x-heroicon-o-pencil-square class="size-6" />
        </a>

        <!-- Eliminar -->
        <form action="{{ route('pedidos.destroy', $row->id).$q }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este pedido?');">
            @csrf @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar">
                <x-heroicon-o-trash class="size-6" />
            </button>
        </form>

        <!-- Asignar -->
        <a href="{{ route('pedidos.assign', $row->id).$searchOnly }}" class="text-indigo-600 hover:text-indigo-900" title="Asignar">
            <x-heroicon-o-user-plus class="size-6" />
        </a>

        @if($row->user)
            <!-- Enviar -->
            <a href="{{ route('pedidos.send', $row->id).$searchOnly }}" class="text-indigo-600 hover:text-indigo-900" title="Enviar">
                <x-heroicon-o-paper-airplane class="size-6" />
            </a>
        @endif
    @endif

    @if($estado === "En revision")
        <form method="POST" action="{{ route('pedidos.reasignar', $row->id).$q }}" onsubmit="return confirm('¿Volver a estado Asignado?')">
            @csrf @method('PATCH')
            <button type="submit" title="Reasignar" class="text-red-600 hover:text-red-800">
                <x-heroicon-o-arrow-path class="size-6" />
            </button>
        </form>
    @endif

    @if($estado === "Asignado" && $row->user_id)
        <form method="POST" action="{{ route('pedidos.quitarUsuario', $row->id).$q }}" onsubmit="return confirm('¿Quitar usuario asignado?')">
            @csrf @method('PATCH')
            <button type="submit" title="Quitar usuario" class="text-yellow-600 hover:text-yellow-800">
                <x-heroicon-o-user-minus class="size-6" />
            </button>
        </form>
    @endif
</div>
