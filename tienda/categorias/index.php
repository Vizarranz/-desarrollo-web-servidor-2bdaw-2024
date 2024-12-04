<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );    

        require('../util/conexion.php');

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
    <div class="container">
        <a class="btn btn-warning" href="usuario/cerrar_sesion.php">Cerrar sesión</a>
        <h1>Tabla de Categorías</h1>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $categoria = $_POST["categoria"];
                /* echo "<h1>$categoria</h1>"; */
                //  Mandamos un mensaje explicando por qué no puede borrar la categoría
                // en caso de intentar realizar el borrado aún existiendo una categoría
                // asociada a un producto.
                $check = "SELECT * from productos where categoria = '$categoria'";
                $resultado = $_conexion -> query($check);

                if ($resultado -> num_rows > 0) {
                    $err_borrado = "La categoría tiene productos asociados, por favor bórralos primero
                    antes de borrar la categoría.";
                }
                else {
                    $sql = "DELETE FROM categorias WHERE categoria = '$categoria'";
                    $_conexion -> query($sql);
                }
            }

            $sql = "SELECT * FROM categorias";
            $resultado = $_conexion -> query($sql);
            /**
             * Aplicamos la función query a la conexión, donde se ejecuta la sentencia SQL hecha
             * 
             * El resultado se almacena $resultado, que es un objeto con una estructura parecida
             * a los arrays
             */
        ?>
        <a class="btn btn-secondary" href="nueva_categoria.php">Crear nueva categoría</a><br>
        <?php if(isset($err_borrado)) echo "<h5 class='error'>$err_borrado</h5>" ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Categorías</th>
                    <th>Descripción</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($fila = $resultado -> fetch_assoc()) {    // trata el resultado como un array asociativo
                        echo "<tr>";
                        echo "<td>" . $fila["categoria"] . "</td>";
                        echo "<td>" . $fila["descripcion"] . "</td>";
                        ?>
                        <td>
                            <a class="btn btn-primary" 
                               href="editar_categoria.php?categoria=<?php echo $fila["categoria"] ?>">Editar</a>
                        </td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="categoria" value="<?php echo $fila["categoria"] ?>">
                                <input class="btn btn-danger" type="submit" value="Borrar">
                            </form>
                        </td>
                        <?php
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
        <a class="btn btn-secondary" href="../index.php">Volver al inicio</a><br>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>