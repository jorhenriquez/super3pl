<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Importar Productos</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                @if(session('success'))
                    <div class="mb-4 text-green-600 font-semibold">{{ session('success') }}</div>
                @endif

                <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Archivo Excel</label>
                        <input type="file" name="file" class="mt-1 block w-full border p-2 rounded">
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Importar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
