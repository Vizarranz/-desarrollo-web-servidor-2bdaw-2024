<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );    

        require('./util/conexion.php');
        
        
    ?>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Productos</h1>
        <a class="btn btn-secondary" href="./categorias/index.php">Categorías</a>
        <a class="btn btn-secondary" href="./productos/index.php">Productos</a>
        <a class="btn btn-secondary" href="./usuario/iniciar_sesion.php">Iniciar sesión</a>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $id_producto = $_POST["id_producto"];
                /* echo "<h1>$categoria</h1>"; */
                //  borrar el anime
                $sql = "DELETE FROM productos WHERE id_producto = '$id_producto'";
                $_conexion -> query($sql);
            }

            $sql = "SELECT * FROM productos";
            $resultado = $_conexion -> query($sql);
            /**
             * Aplicamos la función query a la conexión, donde se ejecuta la sentencia SQL hecha
             * 
             * El resultado se almacena $resultado, que es un objeto con una estructura parecida
             * a los arrays
             */
        ?>
        <a class="btn btn-secondary" href="nuevo_producto.php">Crear nuevo producto</a><br><br>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($fila = $resultado -> fetch_assoc()) {    // trata el resultado como un array asociativo
                        echo "<tr>";
                        echo "<td>" . $fila["nombre"] . "</td>";
                        echo "<td>" . $fila["precio"] . "</td>";
                        echo "<td>" . $fila["categoria"] . "</td>";
                        echo "<td>" . $fila["stock"] . "</td>";
                        echo "<td>" . $fila["descripcion"] . "</td>";
                        ?>
                        <td>
                            <img width="200" height="150" src="<?php echo $fila["imagen"] ?>">
                        </td>
                        <td>
                            <a class="btn btn-primary" 
                               href="editar_producto.php?id_producto=<?php echo $fila["id_producto"] ?>">Editar</a>
                        </td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="id_producto" value="<?php echo $fila["id_producto"] ?>">
                                <input class="btn btn-danger" type="submit" value="Borrar">
                            </form>
                        </td>
                        <?php
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
        <a class="btn btn-secondary" href="../index.php">Volver al inicio</a>
    </div>
</body>
</html>