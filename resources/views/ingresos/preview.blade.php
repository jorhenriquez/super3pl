<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Mapeo de columnas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('ingresos.process') }}" method="POST">
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
                                <label>Entrega:</label>
                                <select name="map[referencia]" class="border rounded p-2 w-full">
                                    @foreach($data[0]['columns'] as $colIndex => $column)
                                        <option value="{{ $colIndex }}">{{ $column }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Código del producto:</label>
                                <select name="map[codigo]" class="border rounded p-2 w-full">
                                    @foreach($data[0]['columns'] as $colIndex => $column)
                                        <option value="{{ $colIndex }}">{{ $column }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Cantidad total:</label>
                                <select name="map[cantidad]" class="border rounded p-2 w-full">
                                    @foreach($data[0]['columns'] as $colIndex => $column)
                                        <option value="{{ $colIndex }}">{{ $column }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Procesar</button>
                    </form>
                </div>
            </div>

        </div>
    </div>


</x-app-layout>
