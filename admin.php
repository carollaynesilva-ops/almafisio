<?php
include "config.php";
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}
?>
<?php include "config.php"; ?>

<header>AlmaFisio - Admin</header>
<div class="container">

<h2>Painel Admin</h2>

<table class="table">
<tr><th>Cliente</th><th>Serviço</th><th>Data</th><th>Status</th><th>Ação</th></tr>

<?php
$sql = $conn->query("
SELECT a.*, u.nome as cliente, s.nome as servico
FROM agendamentos a
JOIN usuarios u ON u.id=a.usuario_id
JOIN servicos s ON s.id=a.servico_id
");

while($row=$sql->fetch_assoc()){
echo "<tr>
<td>{$row['cliente']}</td>
<td>{$row['servico']}</td>
<td>{$row['data']} {$row['horario']}</td>
<td>{$row['status']}</td>
<td>
<a href='confirmar.php?id={$row['id']}'>Confirmar</a>
</td>
</tr>";
}
?>
</table>

<a href="exportar_csv.php">Exportar CSV</a>

</div>