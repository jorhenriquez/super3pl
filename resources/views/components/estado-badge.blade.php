@php
    $estilos = [
        'Creado' => 'bg-gray-100 text-gray-800',
        'En revision' => 'bg-yellow-100 text-yellow-800',
        'Asignado' => 'bg-blue-100 text-blue-800',
        'Revisado' => 'bg-green-100 text-green-800',
        'Observaciones' => 'bg-orange-100 text-orange-800',
        'Anulado' => 'bg-red-100 text-red-800',
    ];

    $clases = $estilos[$estado] ?? 'bg-gray-200 text-gray-900';
@endphp

<span class="{{ $clases }} text-xs p-2 font-medium me-2 px-2.5 py-0.5 rounded-full">
    {{ $estado }}
</span>
