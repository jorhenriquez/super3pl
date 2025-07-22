<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Validar Ingreso #{{ $ingreso->referencia }}</h2>
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
                    x-data="validacionApp({{ $ingreso->id }}, @js($ingreso->lineas), @js($last_validated_id ?? null))"
                    x-init="init(); $nextTick(() => { $refs.input.focus(); })">

                    <div class="mb-4">
                        <input type="text" 
                            x-model="codigo"
                            x-ref="input"
                            @keyup.enter="enviarCodigo"
                            placeholder="Escanea o escribe el código del producto"
                            class="w-full p-2 border rounded" />
                    </div>


                    <div class="mt-6 overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2">Código</th>
                                    <th class="px-4 py-2">Nombre</th>
                                    <th class="px-4 py-2">Por revisar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="linea in lineas" :key="linea.id">
                                    <tr
                                        :class="{
                                            'bg-yellow-50': linea.cantidad_valida > 0 && linea.cantidad_valida < linea.cantidad_total,
                                            'bg-white': linea.cantidad_valida === 0
                                        }"
                                    >
                                        <td class="px-4 py-2" x-text="linea.product.codigo"></td>
                                        <td class="px-4 py-2" x-text="linea.product.descripcion"></td>
                                        <td class="px-4 py-2" x-text="linea.cantidad_total-linea.cantidad_valida"></td>
                                        
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <form action="{{ route('validacion.finalizar.ingreso', $ingreso->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de finalizar la validación?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 text-lg font-semibold">
                            Finalizar Validación
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validacionApp(ingresoId, lineasIniciales, lastId) {
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
                        .filter(l => l.cantidad_valida < l.cantidad_total)
                        .sort((a, b) => {
                            if (a.id === this.lastValidatedId) return -1;
                            if (b.id === this.lastValidatedId) return 1;
                            return 0;
                        });
                },

                validacionCompleta() {
                    return lineasIniciales.length > 0 &&
                        lineasIniciales.every(l => l.cantidad_valida >= l.cantidad_total);
                },

                validarPallet(linea) {
                if (!linea.product.cantidad_palet || linea.product.cantidad_palet <= 0) {
                    this.mensaje = 'El producto no tiene una cantidad de pallet válida.';
                    return;
                }

                // Validar que no supere la cantidad_total
                if (linea.cantidad_valida + linea.product.cantidad_palet > linea.cantidad_total) {
                    this.mensaje = 'No puedes validar más de la cantidad total.';
                    const errorSound = new Audio('/sounds/error.mp3');
                    errorSound.play();
                    return;
                }

                // Enviar la validación con cantidad_palet
                this.enviarCodigoConCantidad(linea.product.codigo, linea.product.cantidad_palet);
            },

                async enviarCodigo() {
                    if (!this.codigo) return;

                    try {
                        const response = await fetch(`/validacion/${ingresoId}/producto_ingreso`, {
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
                            this.lastValidatedId = data.last_validated_id ?? null;
                            this.updateLineas(data.lineas_ingreso);
                            // actualiza también lineasIniciales para que validacionCompleta() funcione correctamente
                            lineasIniciales = data.lineas_ingreso;
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
