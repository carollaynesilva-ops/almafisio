<?php
include "config.php";

if($_SESSION['user']['tipo']!='admin'){
    header("Location: dashboard.php");
}

$id = $_GET['id'];

$conn->query("UPDATE agendamentos 
              SET status='confirmado' 
              WHERE id=$id");

file_put_contents("email_log.txt",
"Agendamento $id confirmado\n", FILE_APPEND);

header("Location: admin.php");
?>