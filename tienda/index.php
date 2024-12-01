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
        session_start();
        if (isset($_SESSION["usuario"])) {
            echo "<h2>Sesión iniciada como " . $_SESSION["usuario"] . "</h2>";
        }
        
        
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
        <!-- <a class="btn btn-secondary" href="./categorias/index.php">Categorías</a>
        <a class="btn btn-secondary" href="./productos/index.php">Productos</a>
        <a class="btn btn-secondary" href="./usuario/iniciar_sesion.php">Iniciar sesión</a> -->
        <nav>
        <ul class="nav nav-underline">
            <?php
            if (isset($_SESSION["usuario"])) {
                echo '<li class="nav-item">
                    <a class="nav-link" aria-current="page" href="./categorias/index.php">Categorías</a>
                    </li>';
                echo '<li class="nav-item">
                    <a class="nav-link" aria-current="page" href="./productos/index.php">Productos</a>
                    </li>';
                echo '<li class="nav-item">
                    <a class="nav-link" aria-current="page" href="./usuario/cerrar_sesion.php">Cerrar sesión</a>
                    </li>';
                echo '<li class="nav-item">
                    <a class="nav-link" aria-current="page" href="./usuario/cambiar_credenciales.php">Cambiar contraseña</a>
                    </li>';
            } else {
                echo '<li class="nav-item">
                    <a class="nav-link" href="./usuario/iniciar_sesion.php">Iniciar Sesión</a>
                    </li>';
            }
            ?>
        </ul>
        </nav>
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
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
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
                            <img width="200" height="150" src="./imagenes/<?php echo $fila['imagen'] ?>">
                        </td>
                        <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>