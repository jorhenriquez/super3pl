<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Mapeo de columnas</h2>
    </x-slot>

    <form action="{{ route('import.process') }}" method="POST">
        @csrf
        <input type="hidden" name="file" value="{{ $path }}">
        
        <div class="mb-6">
            <label for="sheet" class="block font-bold mb-2">Selecciona la hoja:</label>
            <select name="sheet" id="sheet" class="border rounded p-2">
                @foreach($data as $index => $sheet)
                    <option value="{{ $index }}">{{ $sheet['sheet_name'] }}</option>
                @endforeach
            </select>
        </div>

        <h3 class="font-bold mb-4">Mapeo de columnas</h3>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label>Referencia:</label>
                <select name="map[referencia]" class="border rounded p-2 w-full">
                    @foreach($data[0]['columns'] as $colIndex => $column)
                        <option value="{{ $colIndex }}">{{ $column }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Código:</label>
                <select name="map[codigo]" class="border rounded p-2 w-full">
                    @foreach($data[0]['columns'] as $colIndex => $column)
                        <option value="{{ $colIndex }}">{{ $column }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Cantidad:</label>
                <select name="map[cantidad]" class="border rounded p-2 w-full">
                    @foreach($data[0]['columns'] as $colIndex => $column)
                        <option value="{{ $colIndex }}">{{ $column }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Procesar</button>
    </form>
</x-app-layout>
