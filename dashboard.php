

<?php
include "config.php";
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <header>AlmaFisio</header>

    <div class="container">
        <h2>Bem-vindo, <?= $_SESSION['user']['nome'] ?> 👋</h2>

        <a href="agendar.php"><button>Agendar Horário</button></a>
        <a href="meus_agendamentos.php"><button>Meus Agendamentos</button></a>

        <?php if ($_SESSION['user']['tipo'] == 'admin') { ?>
            <a href="admin.php"><button>Painel Admin</button></a>
        <?php } ?>

        <a href="logout.php"><button style="background:red">Sair</button></a>
    </div>
</body>

</html>