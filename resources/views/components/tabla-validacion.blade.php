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
                    // Revisar si se utilizó "estado" o se determina a partir de cantidad
                    $revisado = data_get($row, 'cantidad_revisada');
                    $total = data_get($row, 'cantidad_total');
                    $bg = ($revisado >= $total) ? 'bg-green-50' : 'bg-white';
                @endphp
                <tr class="{{ $bg }} border-b border-gray-200"
                    @if (isset($lastValidatedId) && data_get($row, 'id') === $lastValidatedId)
                        id="last-validated"
                    @endif
                >
                    @foreach($fields as $index => $field)
                        <td class="px-6 py-4">
                            {{ data_get($row, $field) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
