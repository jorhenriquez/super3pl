<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inventario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Filtros y botón Importar -->
                    <form method="GET" action="{{ route('inventories.index') }}" class="mb-6">
                        <div class="flex flex-wrap items-end justify-between gap-4 w-full">
                            <!-- Filtros y acciones -->
                            <div class="flex flex-wrap items-end gap-4">
                                <!-- Buscador -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Buscar</label>
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Referencia, destino, comuna..."
                                        class="border border-gray-300 rounded-md px-4 py-2"
                                    />
                                </div>
                                <!-- Botones de acción -->
                                <div class="flex gap-2">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Filtrar
                                    </button>
                                    <a href="{{ route('inventories.index') }}"
                                       class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                                        Limpiar
                                    </a>
                                </div>
                            </div>
                            
                        </div>
                    </form>

                    <!-- Tabla -->
                    @if($inventories->isEmpty())
                        <p class="text-center text-gray-500">No hay inventario cargado.</p>
                    @else
                        <x-tabla 
                            :headers="['Bodega', 'Almacen','Producto','Cantidad','Lote', 'Fecha de Venc.','Acciones']"
                            :fields="['warehouse', 'store','product.name','quantity','lote', 'fecha_caducidad',1]"
                            :rows="$inventories" 
                        />
                        <div class="mt-4">
                            {{ $inventories->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
