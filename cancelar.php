<?php
include "config.php";

if(!isset($_SESSION['user'])){
    header("Location: login.php");
}

$id = $_GET['id'];

$conn->query("UPDATE agendamentos 
              SET status='cancelado' 
              WHERE id=$id");

header("Location: meus_agendamentos.php");
?>