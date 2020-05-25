<?php
require 'database.php';

$registrado = '';//se inicializa variable

if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {// se valida que los campos esten llenos para iniciar el registro

    $correo = $_POST['email'];//se recibe el corre y se guarda en la variable $correo

    $conn = new Database();//se instancia la clase database

    $buscarCorreo = "SELECT * FROM usuarios WHERE email = '$correo'";//dentro de la variable $buscarCorreo se guarda una consulta

    $resultado = $conn->connect()->prepare($buscarCorreo);//dentro de la variable $resultado se prepara la ejeccuion de la conuslta
    $resultado->execute();// se ejecuta la consulta 
    $res = $resultado->rowCount();//en la variable  $res se realiza un conteo de datos existentes

    $message = '';//se inicializa variable

    if ($res == 0) {//si no existe el correo ingresado prosigue a ingresar contraseña
        $password = 'password';//se guarda la contraseña que ingreso el usario en la variable $password
        $sql = "INSERT INTO usuarios (username, password, rol_id, email) VALUES
                    (:username, :password, '1', :email)";//en la variable $sql se guarda la insercion en la bd como rol 1
        $conn = new Database(); // se instancia la clase Database
        $stmt = $conn->connect()->prepare($sql);
        $stmt->bindParam(':email', $_POST['email']);//se le da valor al parametro de sustitucion por lo que ingreso el usuario!
        $stmt->bindParam(':username', $_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);//passwor_hash encripta lo que recibe por el POST
        $stmt->bindParam(':password', $password);//

        if ($_POST['password'] == $_POST['confirmacion_password']) {// se verifica que las contraseñas  sean iguales al momento de registrarse
            

            if ($stmt->execute()) {//
                $message = 'Se ha registrado exitosamente';
                $registrado = 'login.php';
            } else {
                $message = 'Lo siento, ha ocurrido un error al registrarse';
            }
        } else {
            $message = 'Las contraseñas deben coincidir';
        }
    } else {
        $message = 'El correo ya existe';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assest\css\registro.css">
    <link rel="shortcut icon" href="img\logo_size_invert.jpg" />
    <title>ServiTec | Registro</title>
</head>

<body background="img\fondo_pagina.jpg">
    <center>
        <header class="cabecera">
            <a href="index.php"><img class="punto_cabecera" src="img\servtec.png" alt=""></a>
        </header>
    </center>
    <div class="contenedor">
        <div class="div_registro">
            <center>
                <h1 class="titulo_registro">Registro</h1>
            </center>
            <?php if (!empty($message)) : ?>
                <div class="div_sombra">
                    <center>
                        <div class="div_alerta">
                            <p class="alerta"><?= $message ?></p>
                            <a href="<?= $registrado ?>" class="aceptar">Aceptar</a>
                        </div>
                    </center>
                </div>
            <?php endif; ?>
            <form action="registrarse.php" method="post">
                <table>
                    <tr>
                        <td>
                            <label for="text">Nombre y apellido:</label><br>
                            <input class="text" name="username" type="text" placeholder="Nombre Apellido">
                        </td>
                        <td>
                            <label for="text">Email:</label><br>
                            <input class="text" name="email" type="text" placeholder="correo@email.com">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="text">Contraseña:</label><br>
                            <input name="password" type="password">
                        </td>
                        <td>
                            <label for="text">Confirmar contraseña:</label><br>
                            <input name="confirmacion_password" type="password">
                        </td>
                    </tr>
                </table>
                <center>
                    <p class="texto_iniciar_sesion">¿Ya tienes una cuenta?</p><br>
                    <a href="login.php" class="enlace_iniciar_sesion">Inicia sesión</a><br>
                    <input type="submit" value="Registrarse"><br>
                    <a href="index.php" class="volver_index">&#8617 Volver</a>
                </center>
            </form>
        </div>
    </div>
</body>

</html>