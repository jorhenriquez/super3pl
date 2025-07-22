<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pedidos') }}: {{$pedido->referencia}}
            </h2>
            @if($pedido->estado_pedido->nombre == 'En revision')
                <form method="POST" action="{{ route('pedidos.finalizar', $pedido) }}">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-white text-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Finalizar
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    @php
        $motivos = \App\Models\MotivoObservacion::all();
    @endphp

    <div class="py-12" x-data="modalObservacion()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <!-- Datos del Pedido -->
                <div class="border border-gray-200 rounded-t-lg p-4 bg-gray-50">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">Datos del Pedido</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-800">
                        <div><div class="font-semibold text-gray-700">Referencia</div><div>{{ $pedido->referencia }}</div></div>
                        <div><div class="font-semibold text-gray-700">Destino</div><div>{{ $pedido->destino }}</div></div>
                        <div><div class="font-semibold text-gray-700">Dirección</div><div>{{ $pedido->direccion }}</div></div>
                        <div><div class="font-semibold text-gray-700">Comuna</div><div>{{ $pedido->comuna }}</div></div>
                        <div><div class="font-semibold text-gray-700">Estado</div>@include('components.estado-badge', ['estado' => $pedido->estado_pedido->nombre])</div>
                        <div>
                            <div class="font-semibold text-gray-700">Usuario</div>
                            <div>
                                @if ($pedido->user)
                                    <a href="{{ route('users.show', $pedido->user->id) }}" class="text-blue-600 hover:underline">
                                        {{ $pedido->user->name }}
                                    </a>
                                @else
                                    <span class="text-gray-500">No asignado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barra de progreso -->
                @php
                    $total = $pedido->lineas->sum('cantidad_total');
                    $revisado = $pedido->lineas->sum('cantidad_revisada');
                    $progress = $total > 0 ? round(($revisado / $total) * 100) : 0;
                    if($progress < 0.5) {
                        $color_fondo = 'bg-red-50';
                        $color_borde = 'border-red-400';
                        $color_barra = 'bg-red-400';
                        $color_texto = 'text-red-800';
                    } elseif($progress < 0.8) {
                        $color_fondo = 'bg-yellow-50';
                        $color_borde = 'border-yellow-400';
                        $color_barra = 'bg-yellow-400';
                        $color_texto = 'text-yellow-800';
                    } else {
                        $color_fondo = 'bg-green-50';
                        $color_borde = 'border-green-400';
                        $color_barra = 'bg-green-400';
                        $color_texto = 'text-green-800';
                    }
                @endphp
                <div class="mb-3">
                    <div class="pt-3 pb-3 w-full {{ $color_fondo }} {{ $color_borde}} border-1">
                        <div class="{{ $color_barra }} h-4" style="width: {{ $progress }}%">
                            <span class="border-1 justify-center pb-3 text-md font-medium {{ $color_texto }}">
                                {{ $revisado }}/{{$total }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div x-data="{ tab: 'lineas' }" class="p-6">
                    <div class="flex border-b mb-4">
                        <button :class="tab === 'lineas' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
                            class="px-4 py-2 font-semibold focus:outline-none"
                            @click="tab = 'lineas'">
                            Líneas del Pedido
                        </button>

                        <button :class="tab === 'historial' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
                            class="px-4 py-2 font-semibold focus:outline-none"
                            @click="tab = 'historial'">
                            Historial
                        </button>
                    </div>

                    <!-- Tab: Lineas -->
                    <div x-show="tab === 'lineas'">
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">Linea</th>
                                        <th class="px-6 py-3">Codigo</th>
                                        <th class="px-6 py-3">Descripcion</th>
                                        <th class="px-6 py-3">Cantidad</th>
                                        <th class="px-6 py-3">Revisado</th>
                                        <th class="px-6 py-3">Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedido->lineas as $linea)
                                        @php 
                                            $color_fila = $linea->cantidad_revisada == $linea->cantidad_total ? 'bg-green-50' : 'bg-white';
                                        @endphp
                                        <tr class="border-b border-gray-200 {{ $color_fila }} hover:bg-gray-100">
                                            <td class="px-6 py-4 font-medium text-gray-900">{{$linea->id}}</td>
                                            <td class="px-6 py-4">{{$linea->product->codigo}}</td>
                                            <td class="px-6 py-4">{{$linea->product->descripcion}}</td>
                                            <td class="px-6 py-4">{{$linea->cantidad_total}}</td>
                                            <td class="px-6 py-4">{{$linea->cantidad_revisada}}</td>
                                            <td class="px-6 py-4">
                                                @if(
                                                    in_array($pedido->estado_pedido->nombre, ['En revision', 'En proceso']) &&
                                                    $linea->cantidad_revisada < $linea->cantidad_total &&
                                                    empty($linea->observaciones)
                                                )
                                                    <button
                                                        @click="openModal({{ $linea->id }}, '{{ addslashes($linea->product->descripcion) }}', '{{ addslashes($linea->observaciones) }}')"
                                                        class="text-blue-600 hover:underline">
                                                        Agregar
                                                    </button>

                                                @elseif(!empty($linea->observaciones))
                                                    <button
                                                        @click="openReadOnlyModal(
                                                            '{{ addslashes($linea->product->descripcion) }}',
                                                            '{{ addslashes($linea->observaciones) }}',
                                                            '{{ $linea->motivoObservacion->nombre ?? '' }}'
                                                        )"
                                                        class="text-gray-700 underline hover:text-gray-900">
                                                        Ver
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab: Historial -->
                    <div x-show="tab === 'historial'">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2 text-gray-700">Historial del Pedido</h3>
                            <ul class="text-sm text-gray-600 list-disc pl-6">
                                {{-- Aquí va tu historial --}}
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- MODAL AGREGAR OBSERVACION -->
                <div x-show="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">Agregar observación</h3>
                        <p class="text-sm text-gray-600 mb-2">Producto: <span x-text="producto" class="font-medium text-gray-800"></span></p>
                        <form method="POST" :action="`/lineas/${lineaId}/observacion`">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Motivo</label>
                                    <select name="motivo_observacion_id" required class="w-full border border-gray-300 rounded-md p-2">
                                        <option value="" disabled selected>Seleccione un motivo</option>
                                        @foreach ($motivos as $motivo)
                                            <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                                        @endforeach
                                    </select>
                            </div>

                            <textarea x-model="observacion" name="observaciones" rows="4"
                                class="w-full border border-gray-300 rounded-md p-2 mb-4"
                                placeholder="Escribe la observación..."></textarea>

                            <div class="flex justify-end space-x-2">
                                <button type="button" @click="show = false"
                                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- MODAL VER OBSERVACION -->
                <div x-show="showReadOnly" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">Observación existente</h3>
                        <p class="text-sm text-gray-600 mb-2">Producto: <span x-text="producto" class="font-medium text-gray-800"></span></p>
                        <p class="text-sm text-gray-600 mb-2">Motivo: <span x-text="motivo" class="font-medium text-gray-800"></span></p>
                        <div class="bg-gray-100 border border-gray-300 rounded-md p-3 text-gray-800 mb-4 whitespace-pre-wrap" x-text="observacion"></div>
                        <div class="flex justify-end">
                            <button @click="showReadOnly = false"
                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function modalObservacion() {
            return {
                show: false,
                showReadOnly: false,
                lineaId: null,
                producto: '',
                observacion: '',
                motivo: '',

                openModal(id, producto, obs) {
                    this.lineaId = id;
                    this.producto = producto;
                    this.observacion = obs ?? '';
                    this.show = true;
                },
                openReadOnlyModal(producto, obs, motivo = '') {
                    this.producto = producto;
                    this.observacion = obs;
                    this.motivo = motivo;
                    this.showReadOnly = true;
                }
            }
        }
    </script>
</x-app-layout>
