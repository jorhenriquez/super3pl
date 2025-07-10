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

                    <!-- Filtros y botón Importar -->
                    <form method="GET" action="{{ route('pedidos.index') }}" class="mb-6">
                        <div class="flex flex-wrap items-end justify-between gap-4 w-full">

                            <!-- Filtros y acciones -->
                            <div class="flex flex-wrap items-end gap-4">
                                <!-- Buscador -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Buscar</label>
                                    <input
                                        type="text"
                                        name="search_pedido"
                                        value="{{ request('search_pedido') }}"
                                        placeholder="Referencia, destino, comuna..."
                                        class="border border-gray-300 rounded-md px-4 py-2"
                                    />
                                </div>

                                <!-- Fecha -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Fecha</label>
                                    <input
                                        type="date"
                                        name="fecha"
                                        value="{{ request('fecha') }}"
                                        class="border border-gray-300 rounded-md px-4 py-2"
                                    />
                                </div>

                                <!-- Estado -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                                    <select name="estado" class="border border-gray-300 rounded-md px-4 py-2">
                                        <option value="">Todos</option>
                                        @foreach($estados as $estado)
                                            <option value="{{ $estado }}" {{ request('estado') === $estado ? 'selected' : '' }}>
                                                {{ $estado }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Botones de acción -->
                                <div class="flex gap-2">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Filtrar
                                    </button>
                                    <a href="{{ route('pedidos.index') }}"
                                       class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                                        Limpiar
                                    </a>
                                </div>
                            </div>

                            <!-- Botón Importar -->
                            <div class="ml-auto">
                                <a href="{{ route('pedidos.import.form') }}" 
                                   class="relative inline-block bg-blue-600 text-white px-4 py-2 rounded-md 
                                          transition-all duration-300 ease-in-out 
                                          hover:px-8 hover:text-lg hover:bg-blue-700">
                                    <span class="inline-block transition-opacity duration-300 ease-in-out hover:opacity-0">
                                        Importar
                                    </span>
                                  
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Mensaje de estado -->
                    @if(session('status'))
                        <div class="mb-4 px-4 py-4 bg-green-100 text-green-800 border border-green-400 rounded text-center text-lg font-semibold">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Tabla -->
                    @if($pedidos->isEmpty())
                        <p class="text-center text-gray-500">No hay pedidos que coincidan con los filtros.</p>
                    @else
                        <x-tabla 
                            :headers="['Referencia', 'Id','Destino','Comuna','Cajas', 'Estado', 'Asignacion','Acciones']"
                            :fields="['referencia', 'idDestino','destino','comuna','cantidad', 'estado_pedido.nombre','user.name',1]"
                            :rows="$pedidos" 
                        />
                        <div class="mt-4">
                            {{ $pedidos->appends(request()->query())->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
