<?php

session_start();

require 'database.php';

if (isset($_SESSION['user_id'])) {
    $conn = new Database;
    $records = $conn->connect()->prepare('SELECT id, username, password, email FROM usuarios WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if (count($results) > 0) {
        $user = $results;
    }
    $stmt = $conn->connect()->prepare('SELECT * FROM rol WHERE id > "1"');
    $stmt->execute();
}

$registrado = '';

if (isset($_POST['rol']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {

    $correo = $_POST['email'];

    $conn = new Database();

    $buscarCorreo = "SELECT * FROM usuarios WHERE email = '$correo'";
    $resultado = $conn->connect()->prepare($buscarCorreo);
    $resultado->execute();
    $res = $resultado->fetch(PDO::FETCH_ASSOC);

    $message = '';

    if ($res == 0) {
        $password = 'password';
        $sql = "INSERT INTO usuarios (username, password, rol_id, email) VALUES
                    (:username, :password, :rol, :email)";
        $conn = new Database();
        $stmt = $conn->connect()->prepare($sql);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->bindParam(':rol', $_POST['rol']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password);



        if ($_POST['password'] == $_POST['confirmacion_password']) {
            if ($stmt->execute()) {
                    $Correo = "SELECT * FROM usuarios WHERE email = '$_POST[email]'";
                    $resultad = $conn->connect()->prepare($Correo);
                    $resultad->execute();
                    $res = $resultad->fetch(PDO::FETCH_ASSOC);
                    if ($resultad->rowCount() > 0) {
                        $message = 'Se ha registrado exitosamente';
                        $registrado = 'foto_tecnico.php?id='. $res['id'];
                    }
                } else {
                    $message = 'Lo siento, ha ocurrido un error al registrarse';
                } 
            }else {
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
    <link rel="stylesheet" href="assest\css\nueva.css">
    <link rel="shortcut icon" href="img\logo_size_invert.jpg" />
    <title>Servitec | Registro</title>
</head>

<body background="img\fondo_pagina.jpg">
    <center>
        <header class="cabecera">
            <div class="contenedor">
                <img class="punto_cabecera" src="img\servtec.png" alt="">
                <div class="cont_usuario">
                    <?php if (!empty($user)) : ?>
                        <li class="menu-usuario">
                            <h3 class="nombre"><?= ucwords($user['username']); ?></h3>
                        </li>
                        <li class="menu-usuario1">
                            <div class="div_perfil" id="btn-menu">
                                <?php if (!empty($user['imagen'])) : ?>
                                    <div class="pre_entorno_perfil">
                                        <img src="data:image/jpg;base64,<?php echo base64_encode($user['imagen']) ?>" class="imagen_perfil" alt="">
                                    </div>
                                    <?php else : { ?>
                                        <div class="pre_entorno_perfil">
                                            <img src="img\usuario.png" class="imagen_perfil" alt="">
                                        </div>
                                    <?php } ?>
                                <?php endif; ?>
                                <p class="flecha" id="flecha">&#9660</p>
                            </div>
                        </li>
                    <?php endif; ?>
                </div>
                <nav class="opciones_perfil" id="nav">
                    <ul class="menu">
                        <li class=menu_item><a class="menu_link" href="admin.php">Inicio</a></li>
                        <li class=menu_item><a class="menu_link" id="ref" href="usuarios.php">Usuarios</a></li>
                        <li class=menu_item><a class="menu_link" href="logout.php">Salir</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <div class="contenedor_registro">
            <div class="div_registro">
                <center>
                    <h1 class="titulo_registro">Registro</h1>
                </center>
                <?php if (!empty($message)) : ?>
                    <div class="div_sombra">
                        <center>
                            <div class="div_alerta">
                                <p class="alerta"><?= $message ?></p>
                                <a href="<?=$registrado?>" class="aceptar">Aceptar</a>
                            </div>
                        </center>
                    </div>
                <?php endif; ?>
                <form action="nueva_cuenta.php" method="post">
                    <select name="rol" class="rol">
                        <?php
                        while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                            <option value="<?= ($res['id']); ?>"><?= ($res['nombre']); ?></option>
                        <?php } ?>
                    </select>
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
                        <input type="submit" value="Registrar"><br>
                        <a href="index.php" class="volver_index">&#8617 Volver</a>
                    </center>
                </form>
            </div>
        </div>
    </center>
    <script src="assest\js\menu.js"></script>
</body>

</html>