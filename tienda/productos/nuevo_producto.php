<!-- regex del precio [0-9]{1,4}(\.[0-9]{1,2})? -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );    

        require('../util/conexion.php');

        session_start();
        if(isset($_SESSION["usuario"])) {
            echo "<h2>Sesión de: " . $_SESSION["usuario"] . "</h2>";
        }else{
            header("location: ../index.php");
            exit;
        }
    ?>
    <style>
        .error {
            color: red;
        }
    </style>
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
            $tmp_nombre = depurar($_POST["nombre"]);
            $tmp_precio = depurar($_POST["precio"]);
            $tmp_stock = depurar($_POST["stock"]);
            $tmp_descripcion = depurar($_POST["descripcion"]);
            if (isset($_POST["categoria"])) {
                $tmp_categoria = depurar($_POST["categoria"]);
            }
            else {
                $tmp_categoria = "";
            }
            $nombre_imagen = $_FILES["imagen"]["name"];
            $ubicacion_temporal = $_FILES["imagen"]["tmp_name"];
            $ubicacion_final = "../imagenes/$nombre_imagen";

            move_uploaded_file($ubicacion_temporal, $ubicacion_final);

            if($tmp_categoria == '') {
                $err_categoria = "La categoría es obligatoria";
            }
            else{
                if($resultado -> num_rows != 0) {
                    $err_categoria = "La categoría $tmp_categoria ya existe.";
                } else {
                    //patrón que permite todos los caracteres alfanuméricos y espacios en blanco.
                    $patron = "/^[a-zA-ZáéíóúÁÉÍÓÚäëïöüÄËÏÖÜñÑ ]{2,30}$/";
                    if(!preg_match($patron, $tmp_categoria)) {
                        $err_categoria = "La categoría debe tener máximo 30 caracteres únicamente alfabéticos";
                    } else {
                        $categoria = $tmp_categoria;
                    }
                }
            }

            if($tmp_descripcion == '') {
                $descripcion = $tmp_descripcion;
            } else {
                //patrón que permite todos los caracteres entre 0 y 255 caracteres.
                $patron = "/^.{0,255}$/";
                if(!preg_match($patron, $tmp_categoria)) {
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
        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input class="form-control" id="nombre" type="text" placeholder="Elige un nombre para tu producto" name="nombre">
                <?php if(isset($err_nombre)) echo "<span class='error'>$err_nombre</span>" ?>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label><br>
                <textarea name="descripcion" id="descripcion" placeholder="Esta es la descripción del producto"></textarea>
                <?php if(isset($err_descripcion)) echo "<span class='error'>$err_descripcion</span>" ?>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label><br>
                <input type="number" name="precio" id="precio" placeholder="Precio en euros">
                <?php if(isset($err_precio)) echo "<span class='error'>$err_precio</span>" ?>
            </div>
            <label for="categoria">Categoría</label><br>
            <select name="categoria" id="categoria">
                <option hidden selected disabled>--- Elija una categoría ---</option>
                <?php
                    foreach ($categorias as $categoria) {?>
                        <option value="<?php echo $categoria ?>"><?php echo $categoria ?></option>
                    <?php } ?>
            </select>
            <br><br>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label><br>
                <input type="number" name="stock" id="stock" placeholder="Cantidad del producto">
                <?php if(isset($err_stock)) echo "<span class='error'>$err_stock</span>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen</label>
                <input class="form-control" type="file" name="imagen">
            </div>
            <div class="mb-3">
                <input class="btn btn-primary" type="submit" value="Crear">
                <a class="btn btn-secondary" href="index.php">Volver</a>
            </div>
        </form>
    </div>
    <?php
        //Insertamos los valores una vez se han validado todos los que eran necesarios.
        if (isset($nombre) && isset($precio) && isset($descripcion) && isset($categoria) && isset($ubicacion_final) ) {
            $send = "INSERT INTO productos (nombre, precio, categoria, stock, imagen, descripcion) 
            VALUES ('$nombre'. $precio. '$categoria'. $stock. '$ubicacion_final'. '$descripcion');";
            $resultado = $_conexion -> query($send);
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>