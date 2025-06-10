<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ver usuario: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Contenedor flex para buscador + botón en la misma fila -->
                    <div class="mb-6 flex justify-between items-center space-x-4">
                        <!-- Buscador -->
                        <form method="GET" action="{{ route('users.show', $user->id) }}" class="flex-grow">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Buscar por referencia, destino, comuna, estado o cantidad..."
                                class="border border-gray-300 rounded-md px-4 py-2 w-full sm:w-auto"
                            />
                        </form>

                    </div>
                    
                    <!-- Inicio de Tabla -->
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Referencia</th>
                                    <th scope="col" class="px-6 py-3">Destino</th>
                                    <th scope="col" class="px-6 py-3">Comuna</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->pedidos as $pedido)
                                <tr class="bg-white border-b border-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{$pedido->referencia}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{$pedido->destino}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$pedido->comuna}}
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
