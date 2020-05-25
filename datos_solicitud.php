<?php
session_start();

require 'database.php';

if (isset($_SESSION['user_id'])) {
    $conn = new Database;
    $records = $conn->connect()->prepare('SELECT * FROM usuarios WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if (count($results) > 0) {
        $user = $results;
    }

    if (isset($_GET['dat'])) {
        $stmt = $conn->connect()->prepare('SELECT solicitud.id, estado.nombre "estado", solicitud.descripcion, solicitud.direccion, solicitud.fecha, solicitud.numero FROM solicitud INNER JOIN estado on solicitud.fk_estado = estado.id
        WHERE solicitud.id = :dat');
        $stmt->bindParam(':dat', $_GET['dat']);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $dato = null;

        if (count($resultado) > 0) {
            $dato = $resultado;
        }
    }
    $sql = $conn->connect()->prepare('SELECT solicitud.id, usuarios.username "tecnico", solicitud.descripcion, solicitud.direccion, solicitud.fecha, solicitud.numero FROM solicitud INNER JOIN usuarios on solicitud.fk_tecnico = usuarios.id WHERE solicitud.id = :dato');
    $sql->bindParam(':dato', $_GET['dat']);
    $sql->execute();
    $res = $sql->fetch(PDO::FETCH_ASSOC);

    $tec = null;

    if (($res) > 0) {
        $tec = $res;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assest\css\datos.css">
    <link rel="shortcut icon" href="img\logo_size_invert.jpg" />
    <title>Servitec|Datos de solicitud</title>
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
                        <li class=menu_item><a class="menu_link" href="inicio.php">Inicio</a></li>
                        <li class=menu_item><a class="menu_link" id="ref" href="perfil.php">Perfil</a></li>
                        <li class=menu_item><a class="menu_link" href="foto.php">Editar perfil</a></li>
                        <li class=menu_item><a class="menu_link" href="logout.php">Salir</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <div class="vista_datos" id="div_sombra">
            <h2>Datos de la solicitud</h2>
            <table class="datos_tabla">
                <tr>
                    <td>
                        <h3>Usuario:</h3>
                    </td>
                    <td>
                        <h3>Correo:</h3>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><?= ucwords($user['username']); ?></p>
                    </td>
                    <td>
                        <p><?= ($user['email']); ?></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3>Numero:</h3>
                    </td>
                    <td>
                        <h3>Estado:</h3>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= ($dato['numero']); ?>
                    </td>
                    <td>
                        <?= ($dato['estado']); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3>Tecnico asignado:</h3>
                    </td>
                    <td>
                        <h3>Fecha:</h3>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php if (!empty($tec)) : ?>
                            <?= ucwords($tec['tecnico']); ?>
                            <?php else : { ?>
                                <p>N/N</p>
                            <?php } ?>
                        <?php endif ?>
                    </td>
                    <td>
                        <?= ($dato['fecha']); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3>Descripcion:</h3>
                    </td>
                </tr>
            </table>
        </div>
    </center>
    <div class="espacio_desc">
        <?= ($dato['descripcion']); ?>
    </div>

    <a href="perfil.php" class="volver_perfil">&#8617 Volver</a>

    <script src="assest\js\menu.js"></script>
</body>

</html>