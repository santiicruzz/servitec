<?php
require 'database.php';

session_start();

if (!empty($_POST['email']) && !empty($_POST['password'])){
  $conn = new Database;
  $records = $conn->connect()->prepare('SELECT id, email, username, password, rol_id FROM usuarios WHERE email = :email');
  $records->bindParam(':email', $_POST['email']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);

  $message = '';

  if ($records->rowCount() > 0 && password_verify($_POST['password'], $results['password'])) {
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['rol_id'] = $results['rol_id'];

    switch ($results['rol_id']) {
      case 1:
        header('location: inicio.php');
        break;
      case 2:
        header('location: admin.php');
        break;
      case 3:
        header('location: tecnico.php');
        break;
    }
  } else {
    $message = 'Email o contraseña son incorrectos';
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assest\css\login.css">
  <link rel="shortcut icon" href="img\logo_size_invert.jpg" />
  <title>ServiTec | Login</title>
</head>

<body background="img\fondo_pagina.jpg">
  <center>
    <header class="cabecera">
      <a href="index.php"><img class="punto_cabecera" src="img\servtec.png" alt=""></a>
    </header>
  </center>
  <div class="contenedor">
    <div class="div-login">
      <h1>Login</h1>
      <?php if (!empty($message)) : ?>
        <p class="alerta"><?= $message ?></p>
      <?php endif; ?>
      <form action="login.php" method="post">
        <input type="text" class="caja_login" name="email" placeholder="Ingrese su email..."><br>
        <input type="password" class="caja_login" name="password" placeholder="Ingrese su contraseña..."><br>
        <p class="texto_crear_cuenta">¿No tienes una cuenta? <br>
          <a href="registrarse.php" class="enlace_crear_cuenta">Registrate</a></p>
        <input type="submit" value="Iniciar sesion"><br>
        <a href="index.php" class="volver_index">&#8617 Volver</a>
      </form>
    </div>
  </div>
</body>

</html>