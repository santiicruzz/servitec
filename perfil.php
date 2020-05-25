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
    //Datos de la tabla
    $stmt = $conn->connect()->prepare('SELECT solicitud.id, estado.nombre "estado", solicitud.descripcion, solicitud.direccion, solicitud.fecha FROM solicitud INNER JOIN estado on solicitud.fk_estado = estado.id
        WHERE fk_cliente = :id_user ORDER BY fecha ASC');
    $stmt->bindParam(':id_user', $_SESSION['user_id']);
    $stmt->execute();

    //Funcion de cancelar solicitud
    if (isset($_GET['del'])) {
        $sql = $conn->connect()->prepare('UPDATE solicitud SET fk_estado = 5
            WHERE id = :del');
        $sql->bindParam(':del', $_GET['del']);
        $sql->execute();
        header("location:perfil.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assest\css\perfil.css">
    <link rel="shortcut icon" href="img\logo_size_invert.jpg" />
    <title>ServiTec | Perfil</title>
</head>

<body background="img\fondo_pagina.jpg">
    <div class="centrado" id="load">
        <div class="lds-default">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
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
                        <li class=menu_item><a class="menu_link" id="ref" href="perfil_user.php">Perfil</a></li>
                        <li class=menu_item><a class="menu_link" href="foto.php">Editar perfil</a></li>
                        <li class=menu_item><a class="menu_link" href="logout.php">Salir</a></li>
                    </ul>
                </nav>
            </div>
        </header>
    </center>
    <div class="coontenedor_datos">
        <div class="info">
            <?php if (!empty($user['imagen'])) : ?>
                <div class="pre_entorno_perfil_info">
                    <img src="data:image/jpg;base64,<?php echo base64_encode($user['imagen']) ?>" class="imagen_perfil_info" alt="">
                </div>
                <h3 class="nombre"><?= ucwords($user['username']); ?></h3>
                <h4 class="correo"><?= ($user['email']); ?></h4>
                <?php else : { ?>
                    <div class="pre_entorno_perfil_info">
                        <img src="img\usuario.png" class="imagen_perfil_info" alt="">
                    </div>
                    <h3 class="nombre"><?= ucwords($user['username']); ?></h3>
                    <h4 class="correo"><?= ($user['email']); ?></h4>
                <?php } ?>
            <?php endif; ?>
        </div>
        <div class="datos">
            <h3 class="titulo_solicitud">Solicitudes</h3>
            <div class="lista">
                <div class="div_tabla">
                    <form method="post">
                        <table class="tabla_solicitud">
                            <tr>
                                <th>Fecha</th>

                                <th>Lugar</th>

                                <th>Estado</th>

                                <th>Cancelar</th>

                                <th>Ver</th>
                            </tr>
                            <?php while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                                <tr>
                                    <td>
                                        <p><?= ($res['fecha']); ?></p>
                                    </td>
                                    <td>
                                        <p><?= ($res['direccion']); ?></p>
                                    </td>
                                    <td>
                                        <p><?= ($res['estado']); ?></p>
                                    </td>
                                    <td>
                                        <a href='#' onclick="cambio(<?php echo ($res['id']); ?>)" class="cancelar">Cancelar</a>
                                    </td>
                                    <td>
                                        <a href="#" onclick="datos(<?php echo ($res['id']); ?>)" class="ver" id="ver">Ver</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="assest\js\menu.js"></script>
</body>

</html>