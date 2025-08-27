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
