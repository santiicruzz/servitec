<?php
session_start();
require 'database.php';
    $conn = new Database;
    $id = $_SESSION['user_id'];
    $sql = "UPDATE usuarios SET imagen = Null  WHERE id = '$id'";
    $res = $conn->connect()->prepare($sql);
    $img = $res->fetch(PDO::FETCH_ASSOC);
    $res->execute();
    header('location: foto.php');
