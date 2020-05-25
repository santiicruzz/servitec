<?php
session_start();

require 'database.php';

if (isset($_SESSION['user_id'])) {
    $conn = new Database;
    $records = $conn->connect()->prepare('SELECT id, username, email, imagen FROM usuarios WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;
    $message = "";
    if (count($results) > 0) {
        $user = $results;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assest\css\inicio.css">
    <link rel="shortcut icon" href="img\logo_size_invert.jpg" />
    <title>ServiTec | Inicio</title>
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
                        <li class=menu_item><a class="menu_link" href="perfil.php">Perfil</a></li>
                        <li class=menu_item><a class="menu_link" id="ref" href="foto.php">Editar perfil</a></li>
                        <li class=menu_item><a class="menu_link" href="logout.php">Salir</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <?php if (!empty($message)) : ?>
            <div class="div_sombra">
                <center>
                    <div class="div_alerta">
                        <p class="alerta"><?= $message ?></p>
                        <a href="<?= $recarga ?>" class="aceptar">Aceptar</a>
                    </div>
                </center>
            </div>
        <?php endif; ?>
        <div class='todo' id="todo_foto">
            <div>
                <div class="pre_entorno">
                    <?php if (!empty($user['imagen'])) : ?>
                        <img src="data:image/jpg;base64,<?php echo base64_encode($user['imagen']) ?>" class="imagen_perfil" alt="">
                        <?php else : { ?>
                            <img src="img\usuario.png" class="imagen_perfil" alt="">
                        <?php } ?>
                    <?php endif; ?>
                </div>
                <form action="guardar_foto.php" method="POST" enctype="multipart/form-data">
                    <span>
                        <input type="file" REQUIRED name="imagen" id="file-input" class="imagen">
                    </span>
                    <label class="subir" name="label" for="file-input">
                        <span class="file_txt" id="file-name">Cambiar foto</span>
                    </label><br><br>
                    <a href="eliminar_foto.php" class="delete" onclick="eliminar();">Eliminar foto</a><br><br>
                    <input type="submit" value="Subir" class="save">
                </form>
            </div>
        </div>
        <script src="assest\js\menu.js"></script>
</body>
</html>