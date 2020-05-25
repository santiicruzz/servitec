<?php
session_start();
require 'database.php';

$conn = new Database;
$id = $_SESSION['user_id'];
$imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
$sql = "UPDATE usuarios SET imagen='$imagen' WHERE id = '$id'";
$res = $conn->connect()->prepare($sql);
$img = $res->fetch(PDO::FETCH_ASSOC);
$res->execute();
header('location: foto.php');

