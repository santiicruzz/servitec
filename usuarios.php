<?php
// usuarios.php
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
    $stmt = $conn->connect()->prepare('SELECT * FROM rol WHERE id > 1');
    $stmt->execute();

    $sql = $conn->connect()->prepare('SELECT usuarios.id, rol.nombre "nombre-rol", usuarios.username, usuarios.imagen FROM usuarios INNER JOIN rol on usuarios.rol_id = rol.id ORDER BY usuarios.id ASC');
    $sql->execute();

    if (isset($_POST['estados'])) {
        $estados = $_POST['estados'];
        switch ($estados) {
            case 2:
                $sql = $conn->connect()->prepare('SELECT usuarios.id, rol.nombre "nombre-rol", usuarios.username, usuarios.imagen FROM usuarios INNER JOIN rol on usuarios.rol_id = rol.id WHERE rol_id = 2 ORDER BY usuarios.id ASC');
                $sql->execute();
                break;
            case 3:
                $sql = $conn->connect()->prepare('SELECT usuarios.id, rol.nombre "nombre-rol", usuarios.username, usuarios.imagen FROM usuarios INNER JOIN rol on usuarios.rol_id = rol.id  WHERE rol_id = 3 ORDER BY usuarios.id ASC');
                $sql->execute();
                break;
        }
    }

    if (isset($_GET['dele'])) {
        $query = $conn->connect()->prepare("DELETE FROM usuarios WHERE usuarios.id = :dele");
        $query->bindParam(':dele', $_GET['dele']);
        $query->execute();
        header("location:usuarios.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assest\css\admin.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                        <li class=menu_item><a class="menu_link" href="admin.php">Inicio</a></li>
                        <li class=menu_item><a class="menu_link" id="ref" href="usuarios.php">Usuarios</a></li>
                        <li class=menu_item><a class="menu_link" href="logout.php">Salir</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <form method="post" action="usuarios.php">
            <h1>Usuarios</h1>
                <a href="nueva_cuenta.php" class="add">
                <p class="add_txt">Agregar</p>
                    <span class="material-icons">
                        person_add 
                    </span>
                </a>
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
                <th>Foto</th>

                <th>Nombre</th>

                <th>Cargo</th>

                <th>Eliminar</th>
            </tr>
            <?php
            while ($resultado = $sql->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td>
                        <div class="pre_entorno_user">
                            <?php if (!empty($resultado['imagen'])) : ?>
                                <img src="data:image/jpg;base64,<?php echo base64_encode($resultado['imagen']) ?>" class="imagen_perfil" alt="">
                                <?php else : { ?>
                                    <img src="img\usuario.png" class="imagen_perfil" alt="">
                                <?php } ?>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <p><?= ucwords($resultado['username']); ?></p>
                    </td>
                    <td>
                        <p><?= ($resultado['nombre-rol']); ?></p>
                    </td>
                    <td>
                        <a href="#" onclick="delete_user(<?php echo ($resultado['id']); ?>)" class="eliminar">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </center>
    <script src="assest\js\menu.js"></script>
</body>

</html>