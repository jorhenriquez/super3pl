<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Validar Pedido #{{ $pedido->referencia }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">              
                <div class="py-6 px-4"
                    x-data="validacionApp({{ $pedido->id }}, @js($pedido->lineas), @js($last_validated_id ?? null))"
                    x-init="init(); $nextTick(() => { $refs.input.focus(); })">
                    
                    <div class="mb-4">
                        <input type="text" 
                            x-model="codigo"
                            x-ref="input"
                            @keyup.enter="enviarCodigo"
                            placeholder="Escanea o escribe el código del producto"
                            class="w-full p-2 border rounded" />

                        <form action="{{ route('validacion.finalizar', $pedido->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de finalizar la validación?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="mt-6 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 text-lg font-semibold">
                                Finalizar Validación
                            </button>
                        </form>
                    </div>
                    <div class="mt-6">

                        <div class="mt-6 overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2">Código</th>
                                        <th class="px-4 py-2">Nombre</th>
                                        <th class="px-4 py-2">Revisado</th>
                                        <th class="px-4 py-2">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="linea in lineas" :key="linea.id">
                                        <tr
                                            :class="{
                                                'bg-yellow-50': linea.cantidad_revisada > 0 && linea.cantidad_revisada < linea.cantidad_total,
                                                'bg-white': linea.cantidad_revisada === 0
                                            }"
                                        >
                                            <td class="px-4 py-2" x-text="linea.product.codigo"></td>
                                            <td class="px-4 py-2" x-text="linea.product.descripcion"></td>
                                            <td class="px-4 py-2" x-text="linea.cantidad_revisada"></td>
                                            <td class="px-4 py-2" x-text="linea.cantidad_total"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validacionApp(pedidoId, lineasIniciales, lastId) {
            return {
                codigo: '',
                mensaje: '',
                status: '',
                lineas: [],
                lastValidatedId: lastId,

                init() {
                    this.updateLineas(lineasIniciales);
                },

                updateLineas(nuevasLineas) {
                    this.lineas = nuevasLineas
                        .filter(l => l.cantidad_revisada < l.cantidad_total)
                        .sort((a, b) => {
                            if (a.id === this.lastValidatedId) return -1;
                            if (b.id === this.lastValidatedId) return 1;
                            return 0;
                        });
                },

                async enviarCodigo() {
                    if (!this.codigo) return;

                    try {
                        const response = await fetch(`/validacion/${pedidoId}/producto`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ codigo: this.codigo })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw data;
                        }

                        this.status = data.status;
                        this.mensaje = data.message;

                        // 🟢 Reproducir sonido de éxito
                        const successSound = new Audio('/sounds/success.mp3');
                        successSound.play();

                        if (data.lineas_pedido) {
                            this.lastValidatedId = data.last_validated_id ?? null;
                            this.updateLineas(data.lineas_pedido);
                        }

                    } catch (error) {
                        this.status = 'error_val';
                        this.mensaje = error.message || 'Ocurrió un error';

                        // 🔴 Reproducir sonido de error
                        const errorSound = new Audio('/sounds/error.mp3');
                        errorSound.play();
                    } finally {
                        this.codigo = '';
                        this.$refs.input.focus();
                    }
                }
            }
        }
    </script>

</x-app-layout>
