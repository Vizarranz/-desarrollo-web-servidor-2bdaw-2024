<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ricardo y Mortadelo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );    
    ?>
</head>
<body>

    <?php
        if (isset($_GET["gender"])) {
            $generoEscogido = $_GET["gender"];
            if ($generoEscogido != "male" && $generoEscogido != "female") {
                $generoEscogido = "";
            }
        }
        else {
            $generoEscogido = "";
        }


        if (isset($_GET["species"])) {
            $especieEscogida = $_GET["species"];
            if ($especieEscogida != "human" && $especieEscogida != "alien") {
                $especieEscogida = "";
            }
        }
        else {
            $especieEscogida = "";
        }
    ?>


    <div class="container">
        <?php
            //URL de la api (literalmente la que sea, ya lo veremos)
            $apiUrl = "https://rickandmortyapi.com/api/character?gender=$generoEscogido&species=$especieEscogida";

            //Conexión a la api
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $apiUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $respuesta = curl_exec($curl);
            curl_close($curl);

            //Convierte el código de la api en php
            $datos = json_decode($respuesta, true);
            $personajes = $datos["results"];
        ?>

        <h1>Ricardo y Morticio en sus intrépidas aventuras</h1>


        <form method="get">

    <h4>Filtro</h4>

    <label for="mostrar">Cantidad a mostrar</label>
    <input type="text" id="mostrar" name="mostrar"><br>

    <label for="gender">Genero:</label>
    <select name="gender" id="gender">
        <option value="" hidden> -- Selecciona un genero --</option>
        <option value="female">Mujer</option>
        <option value="male">Hombre</option>
    </select><br>

    <label for="species">Especie:</label>
    <select name="species" id="species">
        <option value="" hidden> -- Selecciona una especie --</option>
        <option value="human">Humano</option>
        <option value="alien">Alienígena</option>
    </select><br>
    <input type="submit" value="Filtrar">

    </form>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th></th>
                    <th>Nombre</th>
                    <th>Género</th>
                    <th>Especie</th>
                    <th>Origen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($personajes as $personaje) {?>
                        <tr>
                            <td><img src="<?php echo $personaje["image"] ?>"></td>
                            <td><?php echo $personaje["name"] ?></td>
                            <td><?php echo $personaje["gender"] ?></td>
                            <td><?php echo $personaje["species"] ?></td>
                            <td><?php echo $personaje["origin"]["name"] ?></td>
                        </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>