<?php
require_once "config.php";

if(!isset($_SESSION['usuario'])){
    $_SESSION['usuario'] = null;
}

$view = $_GET['v'] ?? 'login';
$isAdmin = ($_SESSION['usuario']['tipo'] ?? null) === 'admin';


/* LOGIN */
if(isset($_POST['login_action'])){

$email = $_POST['email'];
$senha = md5($_POST['senha']);

$sql = "SELECT * FROM usuarios WHERE email=? AND senha=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss",$email,$senha);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){
$_SESSION['usuario'] = $result->fetch_assoc();
header("Location: ?v=catalogo");
exit;
}else{
echo "<script>alert('Login inválido')</script>";
}

}


/* LOGOUT */
if(isset($_GET['logout'])){
session_destroy();
header("Location: ?v=login");
exit;
}


/* AGENDAR */
if(isset($_POST['agendar'])){

$usuario = $_SESSION['usuario']['id'];
$servico = $_POST['servico_id'];
$data = $_POST['data'];
$hora = $_POST['hora'];

$sql = "INSERT INTO agendamentos (usuario_id,servico_id,data,horario)
VALUES (?,?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss",$usuario,$servico,$data,$hora);
$stmt->execute();

echo "<script>alert('Agendamento realizado');</script>";
}


/* LISTAR SERVIÇOS */
$servicos = $conn->query("SELECT * FROM servicos");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AlmaFisio</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>

body{
font-family:Arial;
background:#f0f9ff;
margin:0;
}

nav{
background:white;
padding:15px;
display:flex;
justify-content:space-between;
}

.container{
max-width:1000px;
margin:auto;
padding:20px;
}

.card{
background:white;
padding:20px;
border-radius:10px;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:20px;
}

button{
padding:10px;
background:#0ea5e9;
color:white;
border:none;
border-radius:6px;
cursor:pointer;
}

table{
width:100%;
border-collapse:collapse;
}

td,th{
padding:10px;
border-bottom:1px solid #eee;
}

</style>

</head>

<body>


<nav>

<div><b>AlmaFisio</b></div>

<div>

<?php if($_SESSION['usuario']): ?>

<a href="?v=catalogo">Serviços</a>

<a href="?v=meus_agendamentos">Minha Agenda</a>

<?php if($isAdmin): ?>

<a href="?v=admin">Admin</a>

<?php endif; ?>

<a href="?logout=1">Sair</a>

<?php endif; ?>

</div>

</nav>


<div class="container">


<?php if($view == "login"): ?>

<div class="card" style="max-width:400px;margin:auto">

<h2>Login</h2>

<form method="POST">

<input type="hidden" name="login_action" value="1">

<label>Email</label>

<input type="email" name="email" required>

<label>Senha</label>

<input type="password" name="senha" required>

<button>Entrar</button>

</form>

</div>


<?php elseif($view == "catalogo"): ?>


<h2>Serviços</h2>

<div class="grid">

<?php while($s = $servicos->fetch_assoc()): ?>

<div class="card">

<h3><?php echo $s['nome']; ?></h3>

<p>R$ <?php echo $s['preco']; ?></p>

<form method="POST">

<input type="hidden" name="servico_id" value="<?php echo $s['id']; ?>">

<label>Data</label>

<input type="date" name="data" required>

<label>Hora</label>

<select name="hora">

<option>08:00</option>

<option>09:00</option>

<option>10:00</option>

<option>14:00</option>

<option>16:00</option>

</select>

<button name="agendar">Agendar</button>

</form>

</div>

<?php endwhile; ?>

</div>


<?php elseif($view == "meus_agendamentos"): ?>


<div class="card">

<h2>Meus Agendamentos</h2>

<table>

<tr>
<th>Serviço</th>
<th>Data</th>
<th>Hora</th>
<th>Status</th>
</tr>

<?php

$usuario = $_SESSION['usuario']['id'];

$sql = "

SELECT a.*, s.nome AS servico
FROM agendamentos a
JOIN servicos s ON s.id = a.servico_id
WHERE a.usuario_id = ?

";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$usuario);
$stmt->execute();

$result = $stmt->get_result();

while($ag = $result->fetch_assoc()):

?>

<tr>

<td><?php echo $ag['servico']; ?></td>

<td><?php echo $ag['data']; ?></td>

<td><?php echo $ag['horario']; ?></td>

<td><?php echo $ag['status']; ?></td>

</tr>

<?php endwhile; ?>

</table>

</div>


<?php elseif($view == "admin"): ?>

<?php if(!$isAdmin): ?>

<p>Acesso negado</p>

<?php else: ?>

<div class="card">

<h2>Painel Admin</h2>

<table>

<tr>

<th>Paciente</th>
<th>Serviço</th>
<th>Data</th>
<th>Hora</th>
<th>Status</th>

</tr>

<?php

$sql = "

SELECT a.*, u.nome AS paciente, s.nome AS servico
FROM agendamentos a
JOIN usuarios u ON u.id = a.usuario_id
JOIN servicos s ON s.id = a.servico_id

";

$result = $conn->query($sql);

while($ag = $result->fetch_assoc()):

?>

<tr>

<td><?php echo $ag['paciente']; ?></td>

<td><?php echo $ag['servico']; ?></td>

<td><?php echo $ag['data']; ?></td>

<td><?php echo $ag['horario']; ?></td>

<td><?php echo $ag['status']; ?></td>

</tr>

<?php endwhile; ?>

</table>

</div>

<?php endif; ?>

<?php endif; ?>


</div>

</body>

</html>