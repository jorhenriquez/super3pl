<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <!-- Código -->
                    <div class="mb-4">
                        <label for="codigo" class="block text-sm font-medium text-gray-700">Código</label>
                        <input type="text" name="codigo" id="codigo" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('codigo') }}" required>
                        @error('codigo')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="mb-4">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <input type="text" name="descripcion" id="descripcion" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('descripcion') }}" required>
                        @error('descripcion')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- EAN -->
                    <div class="mb-4">
                        <label for="ean" class="block text-sm font-medium text-gray-700">EAN</label>
                        <input type="text" name="ean" id="ean" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('ean') }}">
                        @error('ean')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-between items-center mt-6">
                        <a href="{{ route('products.index') }}" 
                           class="text-gray-600 hover:text-gray-900 underline">
                            ← Volver
                        </a>
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
