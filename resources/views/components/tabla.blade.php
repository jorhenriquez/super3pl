<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                @foreach($headers as $header)
                    <th scope="col" class="px-6 py-3">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                @php
                    // Find the index of the 'estado' column
                    $estadoIndex = collect($headers)->map(fn($h) => strtolower($h))->search('estado');
                    $estadoValue = $estadoIndex !== false ? data_get($row, $fields[$estadoIndex]) : null;
                    $bg = match($estadoValue) {
                        'Creado' => 'bg-gray-50',
                        'En revision' => 'bg-yellow-50',
                        'Asignado' => 'bg-blue-50',
                        'Revisado' => 'bg-green-50',
                        'Observaciones' => 'bg-orange-50',
                        'Anulado' => 'bg-red-50',
                        default => 'bg-white',
                    };
                @endphp
                <tr class="{{ $bg }} border-b border-gray-200">
                    @foreach($fields as $index => $field)
                        <td class="px-6 py-4">
                            
                            @if(isset($headers[$index]) && strtolower($headers[$index]) === 'referencia')
                                <a href="{{ route('pedidos.show', $row->id) }}" 
                                        class="text-blue-600 hover:underline">
                                        {{ data_get($row, $field) }}
                                </a> 
                            @elseif($row->user && isset($headers[$index]) && strtolower($headers[$index]) === 'asignacion') 
                                <a href="{{ route('users.show', $row->user->id) }}" 
                                        class="text-blue-600 hover:underline">
                                        {{ data_get($row, $field) }}
                                </a> 
                            @elseif(isset($headers[$index]) && strtolower($headers[$index]) === 'estado')
                                <x-estado-badge :estado="data_get($row, $field)" />
                            @elseif(isset($headers[$index]) && strtolower($headers[$index]) === 'acciones')
                                <x-acciones :row="$row" />
                            @else
                                {{ data_get($row, $field) }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
