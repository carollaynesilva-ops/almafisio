<?php include "config.php"; ?>

<header>AlmaFisio</header>
<div class="container">
<h2>Login</h2>

<form method="POST">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="senha" placeholder="Senha" required>
<button name="login">Entrar</button>
</form>

<a href="cadastro.php">Criar conta</a>

<?php
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $senha = md5($_POST['senha']);

    $sql = $conn->query("SELECT * FROM usuarios WHERE email='$email' AND senha='$senha'");
    if($sql->num_rows > 0){
        $_SESSION['user'] = $sql->fetch_assoc();
        header("Location: dashboard.php");
    } else {
        echo "Login inválido.";
    }
}
?>
</div>