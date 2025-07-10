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
            
                        
                        @if($pedidos->isEmpty())
                            <p class="text-center text-gray-500">No hay pedidos que coincidan con los filtros.</p>
                        @else
                            <x-tabla 
                                :headers="['Referencia', 'Id','Destino','Comuna','Cajas', 'Estado']"
                                :fields="['referencia', 'idDestino','destino','comuna','cantidad', 'estado_pedido.nombre']"
                                :rows="$pedidos" 
                            />
                            <div class="mt-4">
                                {{ $pedidos->appends(request()->query())->links() }}
                            </div>
                        @endif

                     

                        <div class="mt-4">
                            
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
