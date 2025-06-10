<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Contenedor flex para buscador + botón en la misma fila -->
                    <div class="mb-6 flex justify-between items-center space-x-4">
                        <!-- Buscador -->
                        <form method="GET" action="{{ route('products.index') }}" class="flex-grow">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Buscar por código, descripción o EAN..."
                                class="border border-gray-300 rounded-md px-4 py-2 w-full sm:w-auto"
                            />
                        </form>

                        <!-- Importar productos -->

                        <a href="{{ route('products.import.form') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Importar desde Excel
                        </a>

                        <!-- Botón Crear Producto -->
                        <a href="{{ route('products.create') }}" 
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
                    
                    <!-- Inicio de Tabla -->
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Codigo</th>
                                    <th scope="col" class="px-6 py-3">Descripcion</th>
                                    <th scope="col" class="px-6 py-3">EAN</th>
                                    <th scope="col" class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos as $producto)
                                <tr class="bg-white border-b border-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{$producto->codigo}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{$producto->descripcion}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$producto->ean}}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-3 items-center">
                                            <a href="{{ route('products.edit', $producto->id) }}" 
                                                class="text-indigo-600 hover:text-indigo-900" 
                                                title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>                                              
                                            </a>
                                            <form action="{{ route('products.destroy', $producto->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                                @csrf
                                                @method('DELETE')
                                            
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                    
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $productos->links() }}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
