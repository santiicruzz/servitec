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
    $stmt = $conn->connect()->prepare('SELECT * FROM estado');
    $stmt->execute();

    $sql = $conn->connect()->prepare('SELECT solicitud.id, estado.nombre "estado", solicitud.descripcion, solicitud.direccion, solicitud.fecha FROM solicitud INNER JOIN estado on solicitud.fk_estado = estado.id ORDER BY fecha ASC');
    $sql->execute();

    if (isset($_POST['estados'])) {
        $estados = $_POST['estados'];
        switch ($estados) {
            case 1:
                $sql = $conn->connect()->prepare('SELECT solicitud.id, estado.nombre "estado", solicitud.descripcion, solicitud.direccion, solicitud.fecha FROM solicitud INNER JOIN estado on solicitud.fk_estado = estado.id
                    ORDER BY estado.id ASC');
                $sql->execute();
                break;
            case 2:
                $sql = $conn->connect()->prepare('SELECT solicitud.id, estado.nombre "estado", solicitud.descripcion, solicitud.direccion, solicitud.fecha FROM solicitud INNER JOIN estado on solicitud.fk_estado = estado.id WHERE fk_estado = "2" ORDER BY fecha ASC');
                $sql->execute();
                break;
            case 3:
                $sql = $conn->connect()->prepare('SELECT solicitud.id, estado.nombre "estado", solicitud.descripcion, solicitud.direccion, solicitud.fecha FROM solicitud INNER JOIN estado on solicitud.fk_estado = estado.id WHERE fk_estado = "3" ORDER BY fecha ASC');
                $sql->execute();
                break;
            case 4:
                $sql = $conn->connect()->prepare('SELECT solicitud.id, estado.nombre "estado", solicitud.descripcion, solicitud.direccion, solicitud.fecha FROM solicitud INNER JOIN estado on solicitud.fk_estado = estado.id WHERE fk_estado = "4" ORDER BY fecha ASC');
                $sql->execute();
                break;
            case 5:
                $sql = $conn->connect()->prepare('SELECT solicitud.id, estado.nombre "estado", solicitud.descripcion, solicitud.direccion, solicitud.fecha FROM solicitud INNER JOIN estado on solicitud.fk_estado = estado.id WHERE fk_estado = "5" ORDER BY fecha ASC');
                $sql->execute();
                break;
        }
    }

    if (isset($_GET['eli'])) {
        $query = $conn->connect()->prepare("DELETE FROM solicitud WHERE id = :eli");
        $query->bindParam(':eli', $_GET['eli']);
        $query->execute();
        header("location:admin.php");
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
    <title>ServiTec | Incio</title>
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
                        <li class=menu_item><a class="menu_link" id="ref" href="#">Inicio</a></li>
                        <li class=menu_item><a class="menu_link" href="usuarios.php">Usuarios</a></li>
                        <li class=menu_item><a class="menu_link" href="logout.php">Salir</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <form method="post" action="admin.php">
            <h1>Solicitudes</h1>
            <select name="estados" class="estados">
                <option value="">Selecionar</option>
                <?php
                while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                    <option value="<?= ($res['id']); ?>"><?= ($res['nombre']); ?></option>
                <?php } ?>
            </select>
            <input class="buscar" type="submit" value="Filtrar">
        </form>
        <table class="lista_solicitudes">
            <tr>
                <th>Fecha</th>

                <th>Lugar</th>

                <th>Estado</th>

                <th>Editar</th>

                <th>Eliminar</th>
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
                        <a href='#' onclick="edit(<?php echo ($resultado['id']); ?>)" class="editar">Editar</a>
                    </td>
                    <td>
                        <a href="#" onclick="pregunta(<?php echo ($resultado['id']); ?>)" class="eliminar">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </center>
    <script src="assest\js\menu.js"></script>
</body>

</html>