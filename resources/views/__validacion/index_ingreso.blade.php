<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Validación de Pedidos</h2>
    </x-slot>
 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            
                <div class="p-6">
                    @if ($pedidos->isEmpty())
                        <p class="text-gray-600">No tienes pedidos pendientes para validar.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach ($pedidos as $pedido)
                                <li class="border p-4 rounded flex justify-between items-center">
                                    <div>
                                        <strong>Pedido #{{ $pedido->id }}</strong> — {{ $pedido->referencia }}
                                    </div>
                                    <a href="{{ route('validacion.validar', $pedido->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                        Validar
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    
                    @if ($ingresos->isEmpty())
                        <p class="text-gray-600">No tienes pedidos pendientes para validar.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach ($ingresos as $ingreso)
                                <li class="border p-4 rounded flex justify-between items-center">
                                    <div>
                                        <strong>Ingreso #{{ $ingreso->id }}</strong> — {{ $ingreso->referencia }}
                                    </div>
                                    <a href="{{ route('validacion.validar.ingreso', $ingreso->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                        Validar
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
