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
                                @php 
                                    $estilos = [
                                        'Creado' => 'bg-gray-100',
                                        'En revision' => 'bg-yellow-100',
                                        'Asignado' => 'bg-blue-100',
                                        'Revisado' => 'bg-green-100',
                                        'Observaciones' => 'bg-orange-100',
                                        'En proceso' => 'bg-purple-100',
                                        'Anulado' => 'bg-red-100',
                                    ];
                                @endphp
                                <li class="border p-4 rounded flex justify-between items-center {{$estilos[$pedido->estado_pedido->nombre] ?? 'bg-gray-200'}}">
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
                    <div class="p-4"></div>
                    @if ($ingresos->isEmpty())
                        <p class="text-gray-600">No tienes pedidos pendientes para validar.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach ($ingresos as $ingreso)
                                @php 
                                    $estilos = [
                                        'Creado' => 'bg-gray-100',
                                        'En revision' => 'bg-yellow-100',
                                        'Asignado' => 'bg-blue-100',
                                        'Revisado' => 'bg-green-100',
                                        'Observaciones' => 'bg-orange-100',
                                        'En proceso' => 'bg-purple-100',
                                        'Anulado' => 'bg-red-100',
                                    ];
                                @endphp

                                <li class="border p-4 rounded flex justify-between items-center {{$estilos[$ingreso->estado_pedido->nombre] ?? 'bg-gray-200'}}">
                                    <div>
                                        {{ $ingreso->referencia }}
                                    </div>
                                    <a href="{{ route('validacion.ingreso.pallet', $ingreso->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                        Pallet
                                    </a>
                                    <a href="{{ route('validacion.validar.ingreso', $ingreso->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                        Caja
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
