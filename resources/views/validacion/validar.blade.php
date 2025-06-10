<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Validar Pedido #{{ $pedido->referencia }}</h2>
    </x-slot>

    <div class="py-6 px-4" x-data="validacionApp({{ $pedido->id }})">
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

        <template x-if="mensaje">
            <div class="mb-4 p-3 rounded font-semibold"
                 :class="status === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                <span x-text="mensaje"></span>
            </div>
        </template>

        <div class="mt-6">
            <!-- Aquí puedes mostrar las líneas de pedido si quieres -->
        </div>
    </div>

    <script>
        function validacionApp(pedidoId) {
            return {
                codigo: '',
                mensaje: '',
                status: '',

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

                        if (!response.ok) throw data;

                        this.status = data.status;
                        this.mensaje = data.message;
                    } catch (error) {
                        this.status = 'error';
                        this.mensaje = error.message || 'Ocurrió un error';
                    } finally {
                        this.codigo = '';
                        this.$refs.input.focus(); // vuelve a enfocar el input
                    }
                }


            }
        }
    </script>
</x-app-layout>
