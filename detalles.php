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

        $sql = $conn->connect()->prepare('SELECT solicitud.id, usuarios.username "tecnico",
    solicitud.fk_cliente, solicitud.descripcion, solicitud.direccion, solicitud.fecha, solicitud.numero FROM solicitud INNER JOIN usuarios on solicitud.fk_tecnico = usuarios.id WHERE solicitud.id = :dato');
        $sql->bindParam(':dato', $_GET['dat']);
        $sql->execute();
        $numres = $sql->rowCount();
        $res = $sql->fetch(PDO::FETCH_ASSOC);

        $tec = null;

        if ($numres > 0) {
            $tec = $res;
            $sq = $conn->connect()->prepare('SELECT * FROM usuarios WHERE id = :correo');
            $sq->bindParam(':correo', $tec['fk_cliente']);
            $sq->execute();
            $re = $sq->fetch(PDO::FETCH_ASSOC);

            $corr = null;

            if (count($re) > 0) {
                $corr = $re;
            }
        }

        $sqli = $conn->connect()->prepare('SELECT solicitud.id, usuarios.username "username", solicitud.descripcion, solicitud.direccion, solicitud.fecha, solicitud.numero FROM solicitud INNER JOIN usuarios on solicitud.fk_cliente = usuarios.id WHERE solicitud.id = :dat');
        $sqli->bindParam(':dat', $_GET['dat']);
        $sqli->execute();
        $numres = $sqli->rowCount();
        $resu = $sqli->fetch(PDO::FETCH_ASSOC);

        $nom = null;

        if ($numres > 0) {
            $nom = $resu;
        }
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

<body>
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
        <div class="vista_datos" id="div_sombra">
            <h2>Detalles de la solicitud</h2>
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
                        <p><?= ucwords($nom['username']); ?></p>
                    </td>
                    <td>
                        <p><?= ($corr['email']); ?></p>
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
                        <?= ucwords($tec['tecnico']); ?>
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

    <a href="tecnico.php" class="volver_perfil">&#8617 Volver</a>

    </center>
    <script src="assest\js\menu.js"></script>
</body>

</html>