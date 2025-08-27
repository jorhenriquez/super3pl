<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe Ingreso {{ $ingreso->referencia }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Informe de Ingreso</h1>
    <p><strong>Referencia:</strong> {{ $ingreso->referencia }}</p>
    <p><strong>Estado:</strong> {{ $ingreso->estado_pedido->nombre }}</p>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Cantidad Total</th>
                <th>Recepcionado</th>
                <th>Faltante</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lineas as $linea)
                <tr>
                    <td>{{ $linea['codigo'] }}</td>
                    <td>{{ $linea['descripcion'] }}</td>
                    <td>{{ $linea['cantidad_total'] }}</td>
                    <td>{{ $linea['cantidad_valida'] }}</td>
                    <td>{{ $linea['faltante'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
