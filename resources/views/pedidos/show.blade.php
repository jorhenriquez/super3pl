<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pedidos') }}:{{$pedido->referencia}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
             
                <div class="border border-gray-200 rounded-t-lg p-4 bg-gray-50">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">Datos del Pedido</h3>
                
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-800">
                        
                        <div>
                            <div class="font-semibold text-gray-700">Referencia</div>
                            <div>{{ $pedido->referencia }}</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-700">Destino</div>
                            <div>{{ $pedido->destino }}</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-700">Dirección</div>
                            <div>{{ $pedido->direccion }}</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-700">Comuna</div>
                            <div>{{ $pedido->comuna }}</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-700">Estado</div>
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
                        </div>
                        <div>
                            <div class="font-semibold text-gray-700">Usuario</div>
                            <div>
                                @if ($pedido->user)
                                    <a href="{{ route('users.show', $pedido->user->id) }}" class="text-blue-600 hover:underline">
                                        {{ $pedido->user->name }}
                                    </a>
                                @else
                                    <span class="text-gray-500">No asignado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- put here progress bar -->
                @php
                    $total = $pedido->lineas->sum('cantidad_total');
                    $revisado = $pedido->lineas->sum('cantidad_revisada');
                    $progress = $total > 0 ? round(($revisado / $total) * 100) : 0;
                    if($progress < 0.5) {
                        $color_fondo = 'bg-red-50';
                        $color_borde = 'border-red-400';
                        $color_barra = 'bg-red-400';
                        $color_texto = 'text-red-800';
                    } elseif($progress < 0.8) {
                        $color_fondo = 'bg-yellow-50';
                        $color_borde = 'border-yellow-400';
                        $color_barra = 'bg-yellow-400';
                        $color_texto = 'text-yellow-800';
                    } else {
                        $color_fondo = 'bg-green-50';
                        $color_borde = 'border-green-400';
                        $color_barra = 'bg-green-400';
                        $color_texto = 'text-green-800';
                    }

                @endphp
                <div class="mb-3">
                    <div class="pt-3 pb-3 w-full {{ $color_fondo }} {{ $color_borde}} border-1">
                        <div class="{{ $color_barra }} h-4" style="width: {{ $progress }}%">
                            <span class="border-1 boder-grey-8000 justify-center pb-3 text-md font-medium {{ $color_texto }}">
                                {{ $revisado }}/{{$total}}
                            </span>
                        </div>
                     </div>
                </div>

                
                <div class="pt-3 p-6 text-gray-900">
                    <!-- Inicio de Tabla -->
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Linea</th>
                                    <th scope="col" class="px-6 py-3">Codigo</th>
                                    <th scope="col" class="px-6 py-3">Descripcion</th>
                                    <th scope="col" class="px-6 py-3">Cantidad</th>
                                    <th scope="col" class="px-6 py-3">Revisado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedido->lineas as $linea)
                                <tr class="bg-white border-b border-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{$linea->id}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{$linea->product->codigo}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$linea->product->descripcion}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$linea->cantidad_total}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$linea->cantidad_revisada}}
                                    </td>
                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                       

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
