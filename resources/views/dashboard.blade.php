<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php
                        $user = auth()->user();

                        $pedidosCount = \App\Models\Pedido::where('estado_pedido_id', 2)
                                                    ->orWhere('estado_pedido_id',3);
                        $pedidosCount = $pedidosCount->where('user_id', $user->id)
                                                    ->count();
                                                    
                    @endphp

                    @if ($user->role === 'lector')
                        <p class="text-lg">
                            Tienes <span class="font-bold">{{ $pedidosCount }}</span> pedido{{ $pedidosCount !== 1 ? 's' : '' }} para validar.
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
