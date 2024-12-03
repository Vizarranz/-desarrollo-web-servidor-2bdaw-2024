<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );    

        require('../util/conexion.php');
        session_start();
        if (isset($_SESSION["usuario"])) {
            header("location: iniciar_sesion.php");
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
    <?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $tmp_usuario = $_POST["usuario"];
        $contrasena_1 = depurar($_POST["nueva_contrasena"]);
        $contrasena_2 = depurar($_POST["nueva_contrasena_2"]);
        /*Vas por aquí, ibas a empezar con la validación tanto de usuario como 
        contraseña y lo que tienes ahí abajo tienes que ponerlo con el método isset de
        contrasena_cifrada y usuario, ten en cuenta que empiezas con las variables
        temporales tmp_<variable> 
        Mejora: usa el strcmp para hacer una doble validación de la contraseña en el 
        registro del usuario, y si no coinciden, pues no te deja crearlo.
        */
        $sql = "SELECT * FROM usuarios WHERE usuario = '$tmp_usuario'";
            $resultado = $_conexion -> query($sql);

        if ($tmp_usuario == '') {
            $err_usuario = "El usuario es obligatorio";
        }
        else {
            if($resultado -> num_rows != 0) {
                $err_usuario = "El usuario $tmp_usuario ya existe.";
            } 
            else {
                if (strlen($tmp_usuario) < 3 || strlen($tmp_usuario) > 15) {
                    $err_usuario = "El usuario debe tener entre 3 y 15 caracteres";
                }
                else {
                    $patron = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚäëïöüÄËÏÖÜñÑ ]{3,15}$/";
                    if (!preg_match($patron, $tmp_usuario)) {
                        $err_usuario = "El usuario sólo puede tener letras y números.";
                    }
                    else {
                        $usuario = $tmp_usuario;
                    }
                }
            }
        }
        
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
                        $contrasena_cifrada = password_hash($contrasena_1, PASSWORD_DEFAULT);
                    }
                }
            }
        }

        $contrasena_cifrada = password_hash($contrasena_1,PASSWORD_DEFAULT);
    }
    ?>
        <h1>Registro</h1>
        
        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input class="form-control" type="text" name="usuario">
                <?php if(isset($err_usuario)) echo "<span class='error'>$err_usuario</span>" ?>
            </div>
            <div class="mb-3">
                <label for="nueva_contrasena" class="form-label">Contraseña</label>
                <input class="form-control" type="password"  name="nueva_contrasena">
                <?php if(isset($err_nueva_contrasena)) echo "<span class='error'>$err_nueva_contrasena</span>" ?>
            </div>
            <div class="mb-3">
                <label for="nueva_contrasena_2" class="form-label">Confirme la nueva contraseña</label><br>
                <input class="form-control" type="password"  name="nueva_contrasena_2">
                <?php if(isset($err_nueva_contrasena_2)) echo "<span class='error'>$err_nueva_contrasena_2</span>" ?>
            </div>
            <div class="mb-3">
                <input class="btn btn-primary" type="submit" value="Registrarse">
            </div>
        </form>
        <div class="mb-3">
            <h3>O, si ya tienes cuenta, inicia sesión</h3>
            <a class="btn btn-secondary" href="iniciar_sesion.php">Iniciar sesión</a>
        </div>
    </div>
    <?php
        if (isset($usuario) && isset($contrasena_cifrada)) {
            $sql = "INSERT INTO usuarios VALUES ('$usuario','$contrasena_cifrada')";
            $_conexion -> query($sql);
            header("location: iniciar_sesion.php");
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>