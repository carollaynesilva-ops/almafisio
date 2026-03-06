<?php
include "config.php";

$usuario = $_SESSION['user']['id'];
$servico = $_POST['servico'];
$data = $_POST['data'];
$horario = $_POST['horario'];

$verifica = $conn->query("
SELECT * FROM agendamentos 
WHERE data='$data' AND horario='$horario'
AND status != 'cancelado'
");

if($verifica->num_rows > 0){
    echo "Horário já ocupado.";
    exit;
}

$conn->query("
INSERT INTO agendamentos (usuario_id,servico_id,data,horario)
VALUES ($usuario,$servico,'$data','$horario')
");

file_put_contents("email_log.txt",
"Novo agendamento para $data $horario\n", FILE_APPEND);

header("Location: meus_agendamentos.php");
?>