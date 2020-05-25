<?php 
    require 'database.php';

    if(isset($_POST['tecnicos']) && isset($_POST['estado'])){ 
        $conn= new Database;
        $s = $conn->connect()->prepare('UPDATE solicitud
        SET fk_tecnico = :tecnicos, fk_estado= :estado
        WHERE id = :da');
        $s->bindParam(':da', $_GET['edi']);
        $s->bindParam(':tecnicos', $_POST['tecnicos']);
        $s->bindParam(':estado', $_POST['estado']);
        $s->execute();
        header ("location: admin.php");

        $message = "Guardado Exitoxamente";
}
?>