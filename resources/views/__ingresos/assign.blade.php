<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignar Pedido #'.$ingreso->id) }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            <form method="GET" action="{{ route('ingresos.assign', $ingreso->id) }}" class="mb-4">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar por nombre o correo..."
                    class="border border-gray-300 rounded-md px-4 py-2 w-full sm:w-1/2"
                />
            </form>
    
            
            <form action="{{ route('ingresos.updateAssign', $ingreso->id) }}" method="POST">
                @csrf
                @method('PUT')
                @if(request('search_ingreso'))
                    <input type="hidden" name="search_ingreso" value="{{ request('search_ingreso') }}">
                @endif
            
                <p class="mb-4 text-gray-700 font-semibold">Selecciona un usuario para asignar el pedido:</p>
            
                <div class="border border-gray-200 rounded-lg divide-y divide-gray-200">
                    @foreach ($usuarios as $usuario)
                        <label for="usuario_{{ $usuario->id }}" class="block px-4 py-3 hover:bg-gray-100 cursor-pointer flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="usuario_id" id="usuario_{{ $usuario->id }}" value="{{ $usuario->id }}"
                                       class="text-blue-600 focus:ring-blue-500"
                                       {{ $ingreso->usuario_id == $usuario->id ? 'checked' : '' }}>
                                <div>
                                    <p class="text-gray-800 font-medium">{{ $usuario->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $usuario->email }} | {{ $usuario->role }}</p>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            
                <div class="mt-6 text-right">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Asignar Pedido
                    </button>
                </div>
            </form>
            
            <div class="mt-4">
                {{ $usuarios->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
