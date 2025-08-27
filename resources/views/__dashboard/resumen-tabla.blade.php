<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Resumen de Pedidos (por Usuario y Estado)
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Filtros -->
            <form method="GET" class="flex flex-wrap gap-4 items-end mb-6">
                <div>
                    <label class="block text-sm font-medium">Desde</label>
                    <input type="date" name="fecha_inicio" value="{{ $fecha_inicio }}"
                           class="border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium">Hasta</label>
                    <input type="date" name="fecha_fin" value="{{ $fecha_fin }}"
                           class="border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Filtrar
                    </button>
                </div>
            </form>

            <!-- Tabla resumen Pedidos -->
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-800 border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">Usuario \ Estado</th>
                                @foreach($estados as $estado)
                                    <th class="px-4 py-2 border text-center">
                                        @include('components.estado-badge', ['estado' => $estado->nombre])
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tabla as $fila)
                                <tr class="border-t">
                                    <td class="px-4 py-2 font-semibold border">
                                        @if($fila['id_usuario'])
                                            <a href="{{ route('users.show', $fila['id_usuario']) }}" 
                                               class="text-blue-600 hover:underline">
                                                {{ $fila['usuario'] }}
                                            </a>
                                        @else
                                            <span class="text-gray-500 italic">{{ $fila['usuario'] }}</span>
                                        @endif
                                    </td>
                                    @foreach($fila['valores'] as $cantidad)
                                        <td class="px-4 py-2 text-center border">{{ $cantidad }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla resumen Productos -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- Productos sin EAN (1/3) -->
                <div class="md:col-span-1 bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4">Productos sin EAN</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-800 border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 border">Código</th>
                                    <th class="px-4 py-2 border">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productosSinEan as $producto)
                                    <tr class="border-t">
                                        <td class="px-4 py-2 border text-blue-600 hover:underline">
                                            <a href="{{ route('products.show', $producto->id) }}">
                                                {{ $producto->codigo }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 border">{{ $producto->descripcion }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-2 border text-center text-gray-500 italic">
                                            No hay productos sin EAN.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Productos con Observaciones (2/3) -->
                <div class="md:col-span-2 bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4">Productos con Observaciones</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-800 border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 border">Pedido</th>
                                    <th class="px-4 py-2 border">Código</th>
                                    <th class="px-4 py-2 border">Nombre</th>
                                    <th class="px-4 py-2 border">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productosObservados as $item)
                                    <tr class="border-t {{ $productosSinEanIds->contains($item['producto_id']) ? 'bg-red-100' : '' }}">
                                        <td class="px-4 py-2 border">{{ $item['referencia'] }}</td>
                                        <td class="px-4 py-2 border text-blue-600 hover:underline">
                                            <a href="{{ route('products.show', $item['producto_id']) }}">
                                                {{ $item['codigo'] }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 border">{{ $item['descripcion'] }}</td>
                                        <td class="px-4 py-2 border">{{ $item['observaciones'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 border text-center text-gray-500 italic">
                                            No hay productos con observaciones en este rango de fechas.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
