<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Validar Ingreso por Pallet #{{ $ingreso->referencia }}
        </h2>
    </x-slot>

    @if(session('status'))
        <div class="mb-4 px-4 py-4 bg-red-100 text-red-800 border border-red-400 rounded text-center text-lg font-semibold">
            {{ session('status') }}
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="py-6 px-4"
                    x-data="validacionPalletApp({{ $ingreso->id }}, @js($lineas))"
                    x-init="init(); $nextTick(() => { $refs.input.focus(); })">

                    <!-- Input de escaneo -->
                    <div class="mb-4">
                        <input type="text"
                            x-model="codigo"
                            x-ref="input"
                            @keyup.enter="enviarCodigo"
                            placeholder="Escanea o escribe el código del producto"
                            class="w-full p-2 border rounded" />
                    </div>

                    <!-- Tabla de productos -->
                    <div class="mt-6 overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2">Código</th>
                                    <th class="px-4 py-2">Nombre</th>
                                    <th class="px-4 py-2">Pallet</th>
                                    <th class="px-4 py-2">Por revisar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="linea in lineas" :key="linea.id">
                                    <tr>
                                        <td class="px-4 py-2" x-text="linea.product.codigo"></td>
                                        <td class="px-4 py-2" x-text="linea.product.descripcion"></td>
                                        <td class="px-4 py-2" x-text="linea.product.cantidad_palet"></td>
                                        <td class="px-4 py-2" x-text="linea.cantidad_total - linea.cantidad_valida"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Botón finalizar -->
                    <form action="{{ route('validacion.finalizar.ingreso', $ingreso->id) }}" method="POST"
                        onsubmit="return confirm('¿Estás seguro de finalizar la validación por pallet?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 text-lg font-semibold mt-6">
                            Finalizar Validación
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script --}}
<script>
    function validacionPalletApp(ingresoId, lineasIniciales) {
        return {
            codigo: '',
            lineas: [],
            mensaje: '',
            status: '',

            init() {
                // Filtrar productos con cantidad_palet > 0 y con cantidad restante >= cantidad_palet
                this.lineas = lineasIniciales.filter(
                    l => l.product.cantidad_palet &&
                         l.product.cantidad_palet > 0 &&
                         (l.cantidad_total - l.cantidad_valida) >= l.product.cantidad_palet
                );
            },

            async enviarCodigo() {
                if (!this.codigo) return;

                try {
                    const response = await fetch(`/validacion/${ingresoId}/pallet`, {
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

                    const successSound = new Audio('/sounds/success.mp3');
                    successSound.play();

                    if (data.lineas_ingreso) {
                        // Filtrar nuevamente los productos válidos después de la validación
                        this.lineas = data.lineas_ingreso.filter(
                            l => l.product.cantidad_palet &&
                                 l.product.cantidad_palet > 0 &&
                                 (l.cantidad_total - l.cantidad_valida) >= l.product.cantidad_palet
                        );
                    }

                } catch (error) {
                    this.status = 'error_val';
                    this.mensaje = error.message || 'Ocurrió un error';
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
