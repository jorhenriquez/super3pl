<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Cliente') }}
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

                <form method="POST" action="{{ route('clientes.update', $cliente->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="rut" class="block text-gray-700 font-bold mb-2">R.U.T.</label>
                        <input type="text" name="rut" id="rut" 
                               value="{{ old('rut', $cliente->rut) }}" 
                               class="border border-gray-300 rounded-md w-full px-3 py-2" required>
                        @error('rut')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="nombre" class="block text-gray-700 font-bold mb-2">Nombre</label>
                        <input type="text" name="nombre" id="nombre" 
                               value="{{ old('nombre', $cliente->nombre) }}" 
                               class="border border-gray-300 rounded-md w-full px-3 py-2" required>
                        @error('nombre')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="cod_meribia" class="block text-gray-700 font-bold mb-2">Cod. Meribia</label>
                        <input type="text" name="cod_meribia" id="cod_meribia" 
                               value="{{ old('cod_meribia', $cliente->cod_meribia) }}" 
                               class="border border-gray-300 rounded-md w-full px-3 py-2">
                        @error('cod_meribia')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="cod_externo" class="block text-gray-700 font-bold mb-2">Cod. Externo</label>
                        <input type="text" name="cod_externo" id="cod_externo" 
                               value="{{ old('cod_externo', $cliente->cod_externo) }}" 
                               class="border border-gray-300 rounded-md w-full px-3 py-2">
                        @error('cod_externo')
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
