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
<header>AlmaFisio</header>

<div class="container">
<h2>Bem-vindo, <?=$_SESSION['user']['nome']?> 👋</h2>

<a href="agendar.php"><button>Agendar Horário</button></a>
<a href="meus_agendamentos.php"><button>Meus Agendamentos</button></a>

<?php if($_SESSION['user']['tipo']=='admin'){ ?>
<a href="admin.php"><button>Painel Admin</button></a>
<?php } ?>

<a href="logout.php"><button style="background:red">Sair</button></a>
</div>