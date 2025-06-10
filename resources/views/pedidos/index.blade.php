<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pedidos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Contenedor flex para buscador + botón en la misma fila -->
                    <div class="mb-6 flex justify-between items-center space-x-4">
                        <!-- Buscador -->
                        <form method="GET" action="{{ route('pedidos.index') }}" class="flex-grow">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Buscar por referencia, destino, comuna, estado o cantidad..."
                                class="border border-gray-300 rounded-md px-4 py-2 w-full sm:w-auto"
                            />
                        </form>

                        <!-- Botón Crear Producto -->
                        <a href="{{ route('pedidos.create') }}" 
                        class="relative inline-block bg-green-600 text-white px-4 py-2 rounded-md 
                                transition-all duration-300 ease-in-out 
                                hover:px-8 hover:text-lg hover:bg-green-700">

                            <!-- "+" visible normalmente, desaparece en hover -->
                            <span class="inline-block transition-opacity duration-300 ease-in-out hover:opacity-0">
                                +
                            </span>

                            <!-- "Agregar" oculto normalmente, aparece en hover -->
                            <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 
                                        opacity-0 pointer-events-none 
                                        transition-opacity duration-300 ease-in-out hover:opacity-100">
                                Agregar
                            </span>
                        </a>

                    </div>
                    
                    @if(session('status'))
                        <div class="mb-4 px-4 py-4 bg-green-100 text-green-800 border border-green-400 rounded text-center text-lg font-semibold">
                            {{ session('status') }}
                        </div>
                    @endif


                    <!-- Inicio de Tabla -->
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Referencia</th>
                                    <th scope="col" class="px-6 py-3">Id</th>
                                    <th scope="col" class="px-6 py-3">Destino</th>
                                    <th scope="col" class="px-6 py-3">Comuna</th>
                                    <th scope="col" class="px-6 py-3">Cajas</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                    <th scope="col" class="px-6 py-3">Asignacion</th>
                                    <th scope="col" class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedidos as $pedido)
                                <tr class="bg-white border-b border-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        <a href="{{ route('pedidos.show', $pedido->id) }}" 
                                            class="text-blue-600 hover:underline">
                                            {{ $pedido->referencia }}
                                        </a>
                                    </th>
                                    <td class="px-6 py-4">
                                        {{$pedido->idDestino}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$pedido->destino}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$pedido->comuna}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$pedido->cantidad}}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($pedido->estado_pedido->nombre == 'Creado')
                                            <span class="bg-gray-100 text-gray-800 text-xs p-2 font-medium me-2 px-2.5 py-0.5 rounded-full">
                                                {{$pedido->estado_pedido->nombre}}
                                            </span>
                                            @elseif($pedido->estado_pedido->nombre == 'En revision')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs p-2 font-medium me-2 px-2.5 py-0.5 rounded-full">
                                                {{$pedido->estado_pedido->nombre}}
                                            </span>
                                        @elseif($pedido->estado_pedido->nombre == 'Asignado')
                                        <span class="bg-blue-100 text-blue-800 text-xs p-2 font-medium me-2 px-2.5 py-0.5 rounded-full">
                                                {{$pedido->estado_pedido->nombre}}
                                            </span>
                                        @elseif($pedido->estado_pedido->nombre == 'Revisado')
                                            <span class="bg-green-100 text-green-800 text-xs p-2 font-medium me-2 px-2.5 py-0.5 rounded-full">
                                                {{$pedido->estado_pedido->nombre}}
                                            </span>
                                        @elseif($pedido->estado_pedido->nombre == 'Anulado')
                                            <span class="bg-red-100 text-red-800 text-xs p-2 font-medium me-2 px-2.5 py-0.5 rounded-full">
                                                {{$pedido->estado_pedido->nombre}}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($pedido->user)
                                            <a href="{{ route('users.show', $pedido->user->id) }}" 
                                               class="text-blue-600 hover:underline">
                                                {{ $pedido->user->name }}
                                            </a>
                                        @else
                                            <span class="text-gray-500">No asignado</span>
                                        @endif
                                    </td>
                                    

                                    <td class="px-6 py-4">
                                        <div class="flex space-x-3 items-center">

                                            @if($pedido->estado_pedido->nombre == "Creado")

                                                <a href="{{ route('pedidos.edit', $pedido->id) }}" 
                                                    class="text-indigo-600 hover:text-indigo-900" 
                                                    title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>                                              
                                                </a>
                                            
                                                <form action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este pedido?');">
                                                    @csrf
                                                    @method('DELETE')
                                                
                                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                        
                                                    </button>
                                                </form>
                                                
                                                <a href="{{ route('pedidos.assign', $pedido->id) }}" 
                                                    class="text-indigo-600 hover:text-indigo-900" 
                                                    title="Asignar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                                    </svg>                                                
                                                </a>

                                                @if($pedido->user)
                                                    <a href="{{ route('pedidos.send', $pedido->id) }}" 
                                                        class="text-indigo-600 hover:text-indigo-900" 
                                                        title="Asignar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                                        </svg>                                                 
                                                    </a>
                                                @endif
                                            @endif
                                            
                                            @if($pedido->estado_pedido->nombre == "En revision") <!-- Estado "En revisión" -->
                                                <form method="POST" action="{{ route('pedidos.reasignar', $pedido->id) }}" onsubmit="return confirm('¿Estás seguro de devolver el pedido a Asignado?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" title="Volver a Asignado" class="text-red-600 hover:text-red-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                                            fill="none" viewBox="0 0 24 24" 
                                                            stroke-width="1.5" stroke="currentColor" 
                                                            class="w-6 h-6 inline">
                                                            <path stroke-linecap="round" stroke-linejoin="round" 
                                                                d="M4.5 12a7.5 7.5 0 0 1 13.258-4.258M4.5 12H3m1.5 0l1.5-1.5M3 12l1.5 1.5m14.25 0A7.5 7.5 0 0 1 6.75 19.5M21 12h-1.5m1.5 0-1.5-1.5m1.5 1.5-1.5 1.5" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($pedido->estado_pedido->nombre == "Asignado" && $pedido->user_id)
                                                <form method="POST" action="{{ route('pedidos.quitarUsuario', $pedido->id) }}" onsubmit="return confirm('¿Deseas quitar el usuario asignado?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" title="Quitar usuario asignado" class="text-yellow-600 hover:text-yellow-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                                          </svg>                                                          
                                                    </button>
                                                </form>
                                            @endif

                                            
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $pedidos->links() }}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
