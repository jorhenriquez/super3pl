<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 text-green-600 font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('products.update', $producto->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="codigo" class="block text-gray-700 font-bold mb-2">Código</label>
                        <input type="text" name="codigo" id="codigo" 
                               value="{{ old('codigo', $producto->codigo) }}" 
                               class="border border-gray-300 rounded-md w-full px-3 py-2" required>
                        @error('codigo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="block text-gray-700 font-bold mb-2">Descripción</label>
                        <input type="text" name="descripcion" id="descripcion" 
                               value="{{ old('descripcion', $producto->descripcion) }}" 
                               class="border border-gray-300 rounded-md w-full px-3 py-2" required>
                        @error('descripcion')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="ean" class="block text-gray-700 font-bold mb-2">EAN</label>
                        <input type="text" name="ean" id="ean" 
                               value="{{ old('ean', $producto->ean) }}" 
                               class="border border-gray-300 rounded-md w-full px-3 py-2">
                        @error('ean')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="peso" class="block text-gray-700 font-bold mb-2">Peso</label>
                        <input type="text" name="peso" id="peso" 
                               value="{{ old('peso', $producto->peso) }}" 
                               class="border border-gray-300 rounded-md w-full px-3 py-2">
                        @error('peso')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="volumen" class="block text-gray-700 font-bold mb-2">Volumen</label>
                        <input type="text" name="volumen" id="volumen" 
                               value="{{ old('volumen', $producto->volumen) }}" 
                               class="border border-gray-300 rounded-md w-full px-3 py-2">
                        @error('volumen')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" 
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Guardar Cambios
                        </button>
                        <a href="{{ route('products.index') }}" 
                           class="ml-4 text-gray-600 hover:text-gray-900">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
