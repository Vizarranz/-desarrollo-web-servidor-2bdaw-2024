<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1);
        
        require("../util/conexion.php");
        
        session_start();
        if(isset($_SESSION["usuario"])) {
            echo "<p class='text-primary text-opacity-50'>Sesión iniciada como: " . $_SESSION["usuario"] . "</p>";
        }else{
            header("location: ../usuario/iniciar_sesion.php");
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
        function depurar($entrada) {
            if ($entrada == null) {
                return "";
            }
            $salida = htmlspecialchars($entrada);
            $salida = trim($salida);
            $salida = stripslashes($salida);
            $salida = preg_replace('!\s+!', ' ', $salida);
            return $salida;
        }
    ?>

    <div class="container">
        <h1 class="mb-4">Editar producto</h1>
        <?php
            $id_producto = $_GET["id_producto"];
            $sql = "SELECT * FROM productos WHERE id_producto = $id_producto";
            $resultado = $_conexion -> query($sql);
            
            while($fila = $resultado -> fetch_assoc()) {
                $categoria = $fila["categoria"];
                $nombre = $fila["nombre"];
                $precio = $fila["precio"];
                $descripcion = $fila["descripcion"];
                $stock = $fila["stock"];
                $imagen = $fila["imagen"];
            }

            $sql = "SELECT * FROM categorias ORDER BY categoria";
            $resultado = $_conexion -> query($sql);
            $categorias = [];

            while ($fila = $resultado -> fetch_assoc()) {
                array_push($categorias, $fila["categoria"]);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $tmp_categoria = depurar($_POST["categoria"]);
                $tmp_nombre = depurar($_POST["nombre"]);
                $tmp_precio = depurar($_POST["precio"]);
                $tmp_descripcion = depurar($_POST["descripcion"]);
                $tmp_stock = depurar($_POST["stock"]);
            

                /* Validaciones */

                /* Nombre */
                if ($tmp_nombre == '') {
                    $err_nombre = "Introduzca un nombre para el producto.";
                }
                else {
                    if (strlen($tmp_nombre) < 2 || strlen($tmp_nombre) > 50) {
                        $err_nombre = "El nombre debe contener entre 2 y 50 caracteres.";
                    }
                    else {
                        $patron = "/^[a-zA-Z0-9 ]+$/";
                        if (!preg_match($patron, $tmp_nombre)) {
                            $err_nombre = "El nombre sólo puede constar de caracteres alfanuméricos y espacios en blanco.";
                        }
                        else {
                            $nombre = $tmp_nombre;
                        }
                    }
                }

                /* Categoría */
                if($tmp_categoria == '') {
                    $err_categoria = "La categoría es obligatoria";
                }
                else{
                    //patrón que permite todos los caracteres alfanuméricos y espacios en blanco.
                    $patron = "/^[a-zA-ZáéíóúÁÉÍÓÚäëïöüÄËÏÖÜñÑ ]{2,30}$/";
                    if(!preg_match($patron, $tmp_categoria)) {
                        $err_categoria = "La categoría debe tener máximo 30 caracteres únicamente alfabéticos";
                    } 
                    elseif (!in_array($tmp_categoria, $categorias)) {
                        $err_categoria = "La categoría no se encuentra en la base de datos";
                    }
                    else {
                        $categoria = $tmp_categoria;
                    }
                    
                }

                /* Imagen */
                $nombre_imagen = $_FILES["imagen"]["name"];
                $ubicacion_temporal = $_FILES["imagen"]["tmp_name"];
                $tipo_imagen = $_FILES["imagen"]["type"];

                if (strlen($nombre_imagen) > 60) {
                    $err_imagen = "El nombre de la imagen debe comprender entre 1 y 60 caracteres.";
                }
                else {
                    $formato_imagen = ["image/webp","image/png","image/jpeg","image/jpg","image/apng","image/svg"];
                    if (!in_array($tipo_imagen,$formato_imagen)) {
                        $err_imagen = "El formato de imagen no se encuentra entre los soportados.";
                    }
                    else {
                        $ubicacion_final = "../imagenes/$nombre_imagen";
                        move_uploaded_file($ubicacion_temporal, $ubicacion_final);
                    }
                }

                /* Precio */

                if ($tmp_precio == '') {
                    $err_precio = "El precio del producto es obligatorio.";
                }
                else{
                    if (!is_numeric($tmp_precio)) {
                        $err_precio = "El precio debe ser un número.";
                    }
                    else {
                        if ($tmp_precio < 0 || $tmp_precio > 9999.99) {
                            $err_precio = "El valor debe comprenderse entre 0€ y 9999'99€.";
                        }
                        else {
                            $patron = "/^[0-9]{1,4}(\.[0-9]{1,2})?$/";
                            if (!preg_match($patron, $tmp_precio)) {
                                $err_precio = "El precio debe tener el siguiente formato: XXXX'XX";
                            }
                            else {
                                $precio = $tmp_precio;
                            }
                        }
                    }
                        
                }

                /* Descripción */
                
                if ($tmp_descripcion == '') {
                    $err_descripcion = "La descripción es obligatoria.";
                }
                else {
                    if (strlen($tmp_descripcion) > 255) {
                        $err_descripcion = "La descripción debe contener menos de 255";
                    }
                    else {
                        $descripcion = $tmp_descripcion;
                    }
                }

                /* Stock */

                if ($tmp_stock == '') {
                    $stock = 0;
                }
                else {
                    if (!is_numeric($tmp_stock)) {
                        $err_stock = "El stock debe ser un valor numérico";
                    }
                    else {
                        if ($tmp_stock < 0 || $tmp_stock > 2147483647) {
                            $err_stock = "El stock debe estar comprendido entre 0 y 2147483647";
                        }
                        else {
                            if (filter_var($tmp_stock,FILTER_VALIDATE_INT) === false) {
                                $err_stock = "El número debe ser un entero";
                                var_dump($tmp_stock);
                                echo $tmp_stock;
                            }
                            else {
                                $stock = $tmp_stock; 
                            }
                        }
                    }
                }
            }

        
        $sql = "SELECT * FROM categorias ORDER BY categoria";
        $resultado = $_conexion -> query($sql);
        $categorias = [];

        while ($fila = $resultado -> fetch_assoc()) {
            array_push($categorias, $fila["categoria"]);
        }

        ?>
        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select class="form-select" name="categoria">
                    <option selected hidden><?php echo $categoria ?></option>
                    <?php
                    foreach ($categorias as $categoria_selected) { ?>
                        <option value="<?php echo $categoria_selected ?>" 
                        <?php if ($categoria_selected === $categoria) echo 'selected';?>>
                            <?php echo $categoria_selected ?>
                        </option>
                    <?php } ?>
                    
                </select>
                <?php if(isset($err_categoria)) echo "<span class='error'>$err_categoria</span>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input class="form-control" type="text" value="<?php echo $nombre ?>" name="nombre">
                <?php if(isset($err_nombre)) echo "<span class='error'>$err_nombre</span>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input class="form-control" type="text" value="<?php echo $precio ?>" name="precio">
                <?php if(isset($err_precio)) echo "<span class='error'>$err_precio</span>" ?>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label><br>
                <textarea name="descripcion" id="descripcion"><?php echo $descripcion ?></textarea><br>
                <?php if(isset($err_descripcion)) echo "<span class='error'>$err_descripcion</span>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input class="form-control" type="text" value="<?php echo $stock ?>" name="stock">
                <?php if(isset($err_stock)) echo "<span class='error'>$err_stock</span>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen</label>
                <input class="form-control" type="file" name="imagen">
            </div>
            <div class="mb-3">
                <input class="btn btn-primary" type="submit" value="Editar">
                <a class="btn btn-secondary" href="index.php">Volver</a>
            </div>
        </form>
    </div>

    <?php
        /* Enviar a la BBDD */
        if (isset($nombre, $precio, $categoria, $stock, $descripcion)) {
            if (isset($ubicacion_final)) {
                $sql = "UPDATE productos SET
                nombre = '$nombre',
                precio = '$precio',
                categoria = '$categoria',
                stock = '$stock',
                descripcion = '$descripcion',
                imagen = '$ubicacion_final'
                WHERE id_producto = '$id_producto'";
            $_conexion -> query($sql);
            }
            else {
                $sql = "UPDATE productos SET
                nombre = '$nombre',
                precio = '$precio',
                categoria = '$categoria',
                stock = '$stock',
                descripcion = '$descripcion'
                WHERE id_producto = '$id_producto'";
            $_conexion -> query($sql);    
            }
            
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>