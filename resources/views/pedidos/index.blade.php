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
                                name="search_pedido"
                                value="{{ request('search_pedido') }}"
                                placeholder="Buscar por referencia, destino, comuna, estado o cantidad..."
                                class="border border-gray-300 rounded-md px-4 py-2 w-full sm:w-auto"
                            />
                        </form>

                        <!-- Botón Importar Pedidos -->
                        <a href="{{ route('pedidos.import.form') }}" 
                        class="relative inline-block bg-blue-600 text-white px-4 py-2 rounded-md 
                                transition-all duration-300 ease-in-out 
                                hover:px-8 hover:text-lg hover:bg-blue-700">

                            <!-- "⇪" visible normalmente, desaparece en hover -->
                            <span class="inline-block transition-opacity duration-300 ease-in-out hover:opacity-0">
                                ⇪
                            </span>

                            <!-- "Importar" oculto normalmente, aparece en hover -->
                            <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 
                                        opacity-0 pointer-events-none 
                                        transition-opacity duration-300 ease-in-out hover:opacity-100">
                                Importar
                            </span>
                        </a>

                    </div>
                    
                    @if(session('status'))
                        <div class="mb-4 px-4 py-4 bg-green-100 text-green-800 border border-green-400 rounded text-center text-lg font-semibold">
                            {{ session('status') }}
                        </div>
                    @endif


                    <!-- Inicio de Tabla -->
                    <x-tabla 
                        :headers="['Referencia', 'Id','Destino','Comuna','Cajas', 'Estado', 'Asignacion','Acciones']"
                        :fields="['referencia', 'idDestino','destino','comuna','cantidad', 'estado_pedido.nombre','user.name']"
                        :rows="$pedidos" 
                    />

                    <div class="mt-4">
                        {{ $pedidos->links() }}
                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
