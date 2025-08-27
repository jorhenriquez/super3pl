<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('clientes.store') }}" method="POST">
                    @csrf

                    <!-- Rut -->
                    <div class="mb-4">
                        <label for="rut" class="block text-sm font-medium text-gray-700">R.U.T.</label>
                        <input type="text" name="rut" id="rut" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('rut') }}" required>
                        @error('rut')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre -->
                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" id="nombre" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cod. Meribia -->
                    <div class="mb-4">
                        <label for="cod_meribia" class="block text-sm font-medium text-gray-700">Cod. Meribia</label>
                        <input type="text" name="cod_meribia" id="cod_meribia" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('cod_meribia') }}">
                        @error('cod_meribia')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Cod. Externo -->
                    <div class="mb-4">
                        <label for="cod_externo" class="block text-sm font-medium text-gray-700">Cod. Externo</label>
                        <input type="text" name="cod_externo" id="cod_externo" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('cod_externo') }}">
                        @error('cod_externo')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-between items-center mt-6">
                        <a href="{{ route('clientes.index') }}" 
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
