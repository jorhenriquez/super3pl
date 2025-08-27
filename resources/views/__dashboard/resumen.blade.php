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

            <!-- Tabla resumen -->
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-800 border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">Usuario \ Estado</th>
                                @foreach($estados as $estado)
                                    <th class="px-4 py-2 border text-center">
                                        <x-estado-badge :estado="$estado->nombre" />
                                    </th>
                                @endforeach

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tabla as $fila)
                                <tr class="border-t">
                                    <td class="px-4 py-2 font-semibold border">{{ $fila['usuario'] }}</td>
                                    @foreach($fila['valores'] as $cantidad)
                                        <td class="px-4 py-2 text-center border">{{ $cantidad }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
