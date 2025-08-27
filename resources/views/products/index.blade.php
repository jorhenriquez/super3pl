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
                    <div class="mb-6 flex items-center">
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

                        <!-- Botón Importar productos -->
                        <form method="POST" action="{{ route('products.import') }}" enctype="multipart/form-data" class="ml-4 flex justify-end">
                            @csrf
                            <input type="file" name="file" class="hidden" id="fileInput" accept=".xlsx,.xls,.csv" required>
                            <button
                                type="button"
                                onclick="document.getElementById('fileInput').click()"
                                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-md"
                            >
                                Seleccionar archivo
                            </button>
                            <button
                                type="submit"
                                class="ml-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md"
                            >
                                Importar
                            </button>
                        </form>
                    </div>

                    
                    <!-- Inicio de Tabla -->
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Cod. WMS</th>
                                    <th scope="col" class="px-6 py-3">Cod. Cliente</th>
                                    <th scope="col" class="px-6 py-3">Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr class="bg-white border-b border-gray-200">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{$product->wms_code}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{$product->internal_code}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$product->name}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $products->links() }}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
