<!DOCTYPE html>
<html>

<head>
    <title>Listado de Vehículos</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Listado de Vehículos</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Modelo</th>
                <th>Versión</th>
                <th>Bastidor</th>
                <th>Referencia</th>
                <th>Color Ext.</th>
                <th>Color Int.</th>
                <th>Empresa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehiculos as $vehiculo)
                <tr>
                    <td>{{ $vehiculo->id }}</td>
                    <td>{{ $vehiculo->modelo }}</td>
                    <td>{{ $vehiculo->version }}</td>
                    <td>{{ $vehiculo->bastidor ?? 'N/A' }}</td>
                    <td>{{ $vehiculo->referencia ?? 'N/A' }}</td>
                    <td>{{ $vehiculo->color_externo }}</td>
                    <td>{{ $vehiculo->color_interno }}</td>
                    <td>{{ $vehiculo->empresa->nombre ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>