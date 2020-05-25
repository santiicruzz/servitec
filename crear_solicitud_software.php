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

        if (
            !empty($_POST['direccion']) && !empty($_POST['fecha']) && !empty($_POST['telefono'])
            && !empty($_POST['desc'])
        ) {

            $sql = "INSERT INTO solicitud (fk_tecnico, fk_cliente, fk_estado, fk_tipo, descripcion, direccion, fecha, numero) VALUES (null,'$_SESSION[user_id]', 2, 1, :desc, :direccion, :fecha, :telefono)";
            $conn = new Database();
            $stmt = $conn->connect()->prepare($sql);
            $stmt->bindParam(':desc', $_POST['desc']);
            $stmt->bindParam(':direccion', $_POST['direccion']);
            $stmt->bindParam(':fecha', $_POST['fecha']);
            $stmt->bindParam(':telefono', $_POST['telefono']);

            $message = '';
            if ($stmt->execute()) {
                $message = 'Se ha creado exitosamente';
            } else {
                $message = 'Lo siento ha ocurrido un error al crearse';
            }
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
                        <li class=menu_item><a class="menu_link" href="foto.php">Editar perfil</a></li>
                        <li class=menu_item><a class="menu_link" href="logout.php">Salir</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        
        <div class="cont_todo">
            <h1 class="titulo_solicitud">Solicitud Nueva</h1>
            </center>
            <section class="volver1">
                <a class="volver_txt" href="inicio.php">
                < volver</a> </section> <?php if (!empty($message)) : ?> <div class="div_sombra">
                    <center>
                        <div class="div_alerta">
                            <p class="alerta"><?= $message ?></p>
                            <a href="inicio.php" class="aceptar">Aceptar</a>
                        </div>
                    </center>
                    </div>
                <?php endif; ?>
                <div class="div-solicitud">
                    <form action="crear_solicitud_hardware.php" method="POST">
                        <table>
                            <tr>
                                <td>
                                    <h3>Dirección:</h3>
                                    <input class="direc" name="direccion" type="text">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h3>Fecha:</h3>
                                    <input type="date" name="fecha" class="direc" placeholder="AAAA/MMM/DD">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h3>Numero de contacto:</h3>
                                    <input type="text" name="telefono" class="direc" placeholder="Telefono">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="submit" class="boton" name="crear_solicitud" value="Crear">
                                </td>
                            </tr>
                        </table>
                        <div class="text_area">
                            <h3>Descripcion del servicio:</h3>
                            <textarea class="descripcion" name="desc" rows="18" cols="80"></textarea>
                        </div>

                    </form>
                </div>
        </div>
            <script src="assest\js\menu.js"></script>
            <script src="assest\js\solicitud.js"></script>
</body>

</html>