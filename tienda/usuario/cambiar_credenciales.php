<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );    

        require('../util/conexion.php');
        

        session_start();
        if(isset($_SESSION["usuario"])) {
            echo "<h2>Sesión iniciada como: " . $_SESSION["usuario"] . "</h2>";
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
        function depurar(string $entrada) : string {
            $salida = htmlspecialchars($entrada);
            $salida = trim($salida);
            $salida = stripslashes($salida);
            $salida = preg_replace('!\s+!', ' ', $salida);
            return $salida;
        }
    ?>
    <div class="container">
        <h1>Cambiar contraseña</h1>
        <?php
        //echo "<h1>" . $_GET["id_anime"] . "</h1>";

        $usuario = $_SESSION["usuario"];

        //echo "<h1>$titulo</h1>";

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_contrasena = depurar($_POST["contrasena"]);
            $contrasena_1 = depurar($_POST["nueva_contrasena"]);
            $contrasena_2 = depurar($_POST["nueva_contrasena_2"]);

            $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
            $resultado = $_conexion -> query($sql);
        
            while($fila = $resultado -> fetch_assoc()) {
                $contrasena = $fila["contrasena"];
            }

            /* A continuación realizaremos una confirmación del cambio de contraseña
            mediante una doble validación de los propios campos, dejando así constancia
            de que el propio usuario no se ha confundido al tratar de rellenar el 
            campo de contraseña por primera vez de forma errónea comparado con el
            campo de confirmación, al igual que cuando se crea el usuario. */

            $acceso_concedido = password_verify($tmp_contrasena, $contrasena);
            if (!$acceso_concedido) {
                $err_contrasena = "Contraseña es incorrecta";
            }
            else {
                if ($contrasena_1 == '') {
                    $err_nueva_contrasena = "El campo es obligatorio.";
                }
                elseif ($contrasena_2 == '') {
                    $err_nueva_contrasena_2 = "El campo es obligatorio.";
                }
                else {
                    if (strlen($contrasena_1) < 8 || strlen($contrasena_1) > 15) {
                        $err_nueva_contrasena = "La contraseña debe comprender entre 8 y 15 caracteres.";
                    }
                    elseif (strlen($contrasena_2) < 8 || strlen($contrasena_2) > 15) {
                        $err_nueva_contrasena_2 = "La contraseña debe comprender entre 8 y 15 caracteres.";
                    }
                    else {
                        $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,15}$/";
                        if (!preg_match($patron,$contrasena_1)) {
                            $err_nueva_contrasena = "La contraseña debe contener al menos una minúscula,
                            una mayúscula y un número.";
                        }
                        elseif (!preg_match($patron,$contrasena_2)) {
                            $err_nueva_contrasena_2 = "La contraseña debe contener al menos una minúscula,
                            una mayúscula y un número.";
                        }
                        else {
                            if (strcmp($contrasena_1,$contrasena_2) != 0) {
                                $err_nueva_contrasena = "Las contraseñas no coinciden";
                                $err_nueva_contrasena_2 = "Las contraseñas no coinciden";
                            }
                            else {
                                /* Usar strcmp para comparar que las dos cadenas son iguales, 
                                y en el caso de que no lo sean devolver un error
                                printeando por pantalla. */
                                /* else {
                                    $iguales = strcmp($)
                                } */
                                if (strcmp($tmp_contrasena,$contrasena_1) == 0) {
                                    $err_contrasena = "La contraseña no puede ser igual que la actual.";
                                }
                                else {
                                    $contrasena_cifrada = password_hash($contrasena_1, PASSWORD_DEFAULT);
                                }
                            }
                        }
                    }
                }    
            }
            
        }
        ?>
        <form class="col-6" action="" method="post">
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input class="form-control" type="password"  name="contrasena">
                <?php if(isset($err_contrasena)) echo "<span class='error'>$err_contrasena</span>" ?>
            </div>
            <div class="mb-3">
                <label for="nueva_contrasena" class="form-label">Nueva contraseña</label>
                <input class="form-control" type="password"  name="nueva_contrasena">
                <?php if(isset($err_nueva_contrasena)) echo "<span class='error'>$err_nueva_contrasena</span>" ?>
            </div>
            <div class="mb-3">
                <label for="nueva_contrasena_2" class="form-label">Confirme la nueva contraseña</label><br>
                <input class="form-control" type="password"  name="nueva_contrasena_2">
                <?php if(isset($err_nueva_contrasena_2)) echo "<span class='error'>$err_nueva_contrasena_2</span>" ?>
            </div>
            <div class="mb-3">
                <input class="btn btn-primary" type="submit" value="Editar">
                <a class="btn btn-secondary" href="index.php">Volver</a>
            </div>
        </form>
        </form>
    </div>
    <?php
        if (isset($contrasena_cifrada)) {
            $sql = "UPDATE usuarios SET
                contrasena = '$contrasena_cifrada'
                WHERE usuario = '$usuario'";
            $_conexion -> query($sql);
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>