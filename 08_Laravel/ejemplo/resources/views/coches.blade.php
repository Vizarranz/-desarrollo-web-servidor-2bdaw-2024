<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1>Lista de coches</h1>
        <table>
            <thead>
                <tr>
                    <th>Modelo</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coches as $coche)
                    <tr>
                        <td>{{ $coche[1] }} {{ $coche[0]}}</td>
                        <td>{{ $coche[2] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>