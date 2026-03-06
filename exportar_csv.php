<?php
include "config.php";

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=agenda.csv');

$output = fopen("php://output", "w");

fputcsv($output, ['Cliente','Serviço','Data','Hora','Status']);

$sql = $conn->query("
SELECT u.nome, s.nome as servico, a.data, a.horario, a.status
FROM agendamentos a
JOIN usuarios u ON u.id=a.usuario_id
JOIN servicos s ON s.id=a.servico_id
");

while($row=$sql->fetch_assoc()){
    fputcsv($output,$row);
}

fclose($output);
?>