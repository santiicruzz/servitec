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
    $sql = $conn->connect()->prepare('SELECT solicitud.id, estado.nombre "estado", solicitud.descripcion, solicitud.direccion, solicitud.fecha FROM solicitud INNER JOIN estado on solicitud.fk_estado = estado.id WHERE fk_tecnico = :id  AND estado.nombre != "Realizada" ORDER BY estado.id ASC');
    $sql->bindParam(':id', $_SESSION['user_id']);
    $sql->execute();

    if (isset($_GET['rea'])) {
        $sql = $conn->connect()->prepare('UPDATE solicitud
        SET fk_estado = "4"
        WHERE id = :rea');
        $sql->bindParam(':rea', $_GET['rea']);
        $sql->execute();
        header('location: tecnico.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assest\css\admin.css">
    <link rel="shortcut icon" href="img\logo_size_invert.jpg" />
    <title>Document</title>
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
                        <li class=menu_item><a class="menu_link" id="ref" href="admin.php">Inicio</a></li>
                        <li class=menu_item><a class="menu_link" href="logout.php">Salir</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <h1>Solicitudes agendadas</h1>
        <table class="sol_agenda">
            <tr>
                <th>Fecha</th>

                <th>Lugar</th>

                <th>Estado</th>

                <th>Marcar al </br> Realizar</th>

                <th>Detalles</th>
            </tr>
            <?php
            while ($resultado = $sql->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td>
                        <p><?= ($resultado['fecha']); ?></p>
                    </td>
                    <td>
                        <p><?= ($resultado['direccion']); ?></p>
                    </td>
                    <td>
                        <p><?= ($resultado['estado']); ?></p>
                    </td>
                    <td>
                        <a href="#" onclick="realizar(<?php echo ($resultado['id']); ?>)" class="realizar" id="ver">Realizada</a>
                    </td>
                    <td>
                        <a href="#" onclick="detalles(<?php echo ($resultado['id']); ?>)" class="ver" id="ver">Ver</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </center>
    <script src="assest\js\menu.js"></script>
</body>

</html>