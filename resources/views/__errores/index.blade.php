<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Errores de Validación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2">ID</th>
                                    <th class="px-4 py-2">Usuario</th>
                                    <th class="px-4 py-2">Pedido</th>
                                    <th class="px-4 py-2">Código Ingresado</th>
                                    <th class="px-4 py-2">Mensaje</th>
                                    <th class="px-4 py-2">Tipo</th>
                                    <th class="px-4 py-2">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($errores as $error)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $error->id }}</td>
                                        <td class="px-4 py-2">{{ $error->user->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $error->pedido->referencia ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $error->codigo_ingresado ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ $error->mensaje_error }}</td>
                                        <td class="px-4 py-2">
                                            @switch($error->error_type_id)
                                                @case(1) Producto incorrecto @break
                                                @case(2) Producto ya revisado @break
                                                @case(3) Validación incompleta @break
                                                @default Otro
                                            @endswitch
                                        </td>
                                        <td class="px-4 py-2">{{ $error->created_at->format('d-m-Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center px-4 py-4">No se han registrado errores.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $errores->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
