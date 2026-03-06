<?php

$conn = new mysqli("localhost","root","","almafisio",3307);

if($conn->connect_error){
    die("Erro de conexão: " . $conn->connect_error);
}

echo "Banco conectado com sucesso!";