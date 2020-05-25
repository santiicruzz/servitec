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
    //Datos usuario
    if (isset($_GET['idu'])) {
        $recos = $conn->connect()->prepare('SELECT * FROM usuarios WHERE id = :idu');
        $recos->bindParam(':idu', $_GET['idu']);
        $recos->execute();
        $resuts = $recos->fetch(PDO::FETCH_ASSOC);

        $usua = null;

        if (count($resuts) > 0) {
            $usua = $resuts;
        }
        if (empty($_POST['password_actual']) && empty($_POST['password_nueva']) && empty($_POST['password_confirmacion_nueva'])) {

            $sql = "UPDATE usuarios SET username = :nombre, email = :email WHERE id = :dat";
            $conn = new Database();
            $stmt = $conn->connect()->prepare($sql);
            $stmt->bindParam(':dat', $_POST['idu']);
            $stmt->bindParam(':nombre', $_POST['nombre']);
            $stmt->bindParam(':email', $_POST['email']);
            header('location: usuarios.php');
        }
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
    </center>
    <div class="coontenedor_datos">
        <div class="info">
            <form method="post" action="detalles_user.php">
                <?php if (!empty($usua['imagen'])) : ?>
                    <div class="pre_entorno_perfil_info">
                        <img src="data:image/jpg;base64,<?php echo base64_encode($usua['imagen']) ?>" class="imagen_perfil_info" alt="">
                    </div>
                    <?php else : { ?>
                        <div class="pre_entorno_perfil_info">
                            <img src="img\usuario.png" class="imagen_perfil_info" alt="">
                        </div>
                    <?php } ?>
                <?php endif; ?>
        </div>
        <div class="datos">
            <h3 class="titulo_solicitud">Datos</h3>
            <div class="lista">
                <div class="div_tabla">
                    <table class="tabla_solicitud">
                        <tr>
                            <td>
                                <h3>Nombre:</h3>
                                <input type="text" name="nombre" value="<?= $usua['username'] ?>">
                                <h3>Email:</h3>
                                <input type="text" name="email" value="<?= $usua['email'] ?>">
                                <h3>Contraseña actual:</h3>
                                <input type="text" name="password_actual" value="">
                                <h3>Contraseña nueva:</h3>
                                <input type="text" name="password_nueva" value="">
                                <h3>Confrimacion contraseña nueva:</h3>
                                <input type="text" name="password_confirmacion_nueva" value="">
                                <input type="submit" value="Guardar cambios">
                        </tr>
                    </table>
                    </form>
                    <a href="usuarios.php">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
    <script src="assest\js\menu.js"></script>
</body>

</html>