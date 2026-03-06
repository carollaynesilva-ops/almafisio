<?php
include "config.php";
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}
?>

<?php include "config.php"; ?>
<header>AlmaFisio</header>
<div class="container">

<h2>Meus Agendamentos</h2>

<table class="table">
<tr><th>Serviço</th><th>Data</th><th>Hora</th><th>Status</th><th>Ação</th></tr>

<?php
$id = $_SESSION['user']['id'];

$sql = $conn->query("
SELECT a.*, s.nome 
FROM agendamentos a
JOIN servicos s ON s.id=a.servico_id
WHERE usuario_id=$id
");

while($row=$sql->fetch_assoc()){
    echo "<tr>
    <td>{$row['nome']}</td>
    <td>{$row['data']}</td>
    <td>{$row['horario']}</td>
    <td class='status-{$row['status']}'>{$row['status']}</td>
    <td><a href='cancelar.php?id={$row['id']}'>Cancelar</a></td>
    </tr>";
}
?>
</table>

</div>