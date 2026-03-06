<?php
require_once "config.php";

if (!isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = null;
}

$view = $_GET['v'] ?? 'login';
$isAdmin = ($_SESSION['usuario']['tipo'] ?? null) === 'admin';


$conn = new mysqli("localhost","root","mysql","almafisio",3306);

if($conn->connect_error){
    die("Erro: " . $conn->connect_error);
}
session_start();
?>