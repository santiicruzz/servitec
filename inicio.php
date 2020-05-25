<?php
session_start();

require 'database.php';

if (isset($_SESSION['user_id'])) {
    $idh = 2;
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

        $sql = "INSERT INTO solicitud (fk_tecnico, fk_cliente, fk_estado, descripcion, direccion, fecha, numero) VALUES
                ('4','$_SESSION[user_id]', '2', :desc, :direccion, :fecha, :telefono)";
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

<body>
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
                        <li class=menu_item><a class="menu_link" id="ref" href="">Inicio</a></li>
                        <li class=menu_item><a class="menu_link" href="perfil.php">Perfil</a></li>
                        <li class=menu_item><a class="menu_link" href="foto.php">Editar perfil</a></li>
                        <li class=menu_item><a class="menu_link" href="logout.php">Salir</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <div id="principio">
            <img src="img\fondo_servicio.jpg" class="img_servicio" alt="">
            <div class="contenedor_inicio">
                <div class="titulo">
                    <h1 class="titulo1">¿Necesitas un servicio técnico?</h1>
                    <h1 class="titulo2">¡Pidelo!</h1>
                    <a onclick="mostrar();" class="crear">Crear</a>
                </div>
            </div>
        </div>
        <!-----------------------------------------tipo de servicio---------------------------------------->
        <div class="tipo_solicitud" id="tipo_solicitud">
            <img class="img_tipo" src="img\fondo_pagina.jpg" alt="">
            <center>
                <div class="contenido_tipo_solicitud">
                    <h1>Tipo de servicio</h1>
                    <div class="volver">
                        <a href="inicio.php" class="volver_txt">
                            < volver</a> </div> <div class="tipo_container">
                                <a href="crear_solicitud_hardware.php">
                                    <section class="tipo_item">
                                        <img src="img\hardware.jpg" alt="" class="tipo_img">
                                        <section class="tipo_txt">
                                            <h2>Hardware</h2>
                                            <p>En esta area nos encargamos de mantenimiento limpieza y prevencion del equipo en lo fisico como el monitor, la cpu etc... contamos con los repuestos que sean necesarios</p>
                                        </section>
                                    </section>
                                </a>
                                <a href="crear_solicitud_software.php">
                                    <section class="tipo_item">
                                        <img src="img\software.jpg" alt="" class="tipo_img">
                                        <section class="tipo_txt">
                                            <h2>Software</h2>
                                            <p>En esta area nos encargamos de mantenimiento limpieza y prevencion del equipo en lo operacional como formatear,intalaciones, actualizaciones etc...</p>
                                        </section>
                                    </section>
                                </a>
                    </div>
                </div>
            </center>
        </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="assest\js\menu.js"></script>
        <script src="assest\js\solicitud.js"></script>
</body>

</html>