<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dogos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );    
    ?>
</head>
<body>
    <div class="container">
        <?php
            //URL de la api (literalmente la que sea, ya lo veremos)
            $apiUrl = "https://dog.ceo/api/breeds/image/random";

            //Conexión a la api
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $apiUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $respuesta = curl_exec($curl);
            curl_close($curl);

            //Convierte el código de la api en php
            $datos = json_decode($respuesta, true);
            $perros = $datos["message"];
        ?>

        <img src="<?php echo $perros?>" alt="perrete">
        <form method="get">
            <input type="submit" value="Perrete">
        </form>
    </div>
</body>
</html>