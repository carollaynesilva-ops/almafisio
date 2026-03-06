<?php
$conn = new mysqli("localhost","root","","almafisio",3307);

if($conn->connect_error){
    die("Erro: " . $conn->connect_error);
}
session_start();
?>