<?php
session_start();
require 'database.php';

if (isset($_POST['id'])) {
    $conn = new Database;
    $id = $_POST['id'];
    $imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    $sql = "UPDATE usuarios SET imagen='$imagen' WHERE id = '$id'";
    $res = $conn->connect()->prepare($sql);
    $res->execute();
    header('location: usuarios.php');
}
