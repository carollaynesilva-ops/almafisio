<?php
include "config.php";
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}
?>

<?php
include "config.php";
if(!isset($_SESSION['user'])){
    header("Location: login.php");
}
?>

<link rel="stylesheet" href="css/style.css">
<script src="js/script.js"></script>

<header>AlmaFisio</header>

<div class="container">
<h2>Agendar Atendimento</h2>

<form method="POST" action="salvar_agendamento.php">

<label>Serviço:</label>
<select name="servico" required>
<?php
$servicos = $conn->query("SELECT * FROM servicos");
while($s=$servicos->fetch_assoc()){
    echo "<option value='{$s['id']}'>{$s['nome']} - R$ {$s['preco']}</option>";
}
?>
</select>

<label>Data:</label>
<input type="date" name="data" required>

<label>Horário:</label>
<input type="time" name="horario" required>

<button>Agendar</button>
</form>

<a href="dashboard.php">Voltar</a>
</div>