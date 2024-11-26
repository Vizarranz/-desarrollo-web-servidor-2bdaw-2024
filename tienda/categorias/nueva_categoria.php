<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );    

        require('../util/conexion.php');

        /* session_start();
        if(isset($_SESSION["usuario"])) {
            echo "<h2>Bienvenid@ " . $_SESSION["usuario"] . "</h2>";
        }else{
            header("location: usuario/iniciar_sesion.php");
            exit;
        } */
    ?>
</head>
<body>
    <?php
        function depurar(string $entrada) : string {
            $salida = htmlspecialchars($entrada);
            $salida = trim($salida);
            $salida = stripslashes($salida);
            $salida = preg_replace('!\s+!', ' ', $salida);
            return $salida;
        }
    ?>
    <div class="container">
        <h1>Nueva categoría</h1>
        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_categoria = depurar($_POST["categoria"]);
            $tmp_descripcion = depurar($_POST["descripcion"]);

            if($tmp_categoria == '') {
                $err_categoria = "La categoría es obligatoria";
            } else {
                if(strlen($tmp_categoria) > 30) {
                    $err_categoria = "La categoría debe tener máximo 30 caracteres";
                } else {
                    $categoria = $tmp_categoria;
                }
            }

            if($tmp_descripcion == '') {
                $err_descripcion = "La descripción es obligatoria";
            } else {
                if(strlen($tmp_descripcion) > 255) {
                    $err_descripcion = "La descripción debe tener máximo 255 caracteres";
                } else {
                    $descripcion = $tmp_descripcion;
                }
            }
        }

        $sql = "SELECT * FROM categorias ORDER BY categoria";
        $resultado = $_conexion -> query($sql);
        $categorias = [];

        while($fila = $resultado -> fetch_assoc()) {
            array_push($categorias, $fila["categoria"]);
        }
        //print_r($estudios);
 
        ?>
        <form class="col-6" action="" method="post">
            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <input class="form-control" type="text" placeholder="Electrónica III..." name="categoria">
                <?php if(isset($err_categoria)) echo "<span class='error'>$err_categoria</span>" ?>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label><br>
                <textarea name="descripcion" id="descripcion" placeholder="Cosas que hacen chispas..."></textarea>
                <?php if(isset($err_descripcion)) echo "<span class='error'>$err_descripcion</span>" ?>
            </div>
            <div class="mb-3">
                <input class="btn btn-primary" type="submit" value="Crear">
                <a class="btn btn-secondary" href="index.php">Volver</a>
            </div>
        </form>
    </div>
    <?php
        if (isset($categoria) && isset($descripcion)) {
            $sql = "INSERT INTO categorias (categoria, descripcion) 
            VALUES ('$categoria', '$descripcion')";
            $_conexion -> query($sql);
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>