<?php include "config.php"; ?>

<link rel="stylesheet" href="css/style.css">
<header>AlmaFisio</header>

<div class="container">
<h2>Cadastro</h2>

<form method="POST">
<input type="text" name="nome" placeholder="Nome" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="senha" placeholder="Senha" required>
<button name="cadastrar">Cadastrar</button>
</form>

<a href="login.php">Voltar</a>

<?php
if(isset($_POST['cadastrar'])){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = md5($_POST['senha']);

    $verifica = $conn->query("SELECT * FROM usuarios WHERE email='$email'");
    if($verifica->num_rows > 0){
        echo "Email já cadastrado.";
    } else {
        $conn->query("INSERT INTO usuarios(nome,email,senha) 
                      VALUES('$nome','$email','$senha')");
        echo "Cadastro realizado! <a href='login.php'>Entrar</a>";
    }
}
?>
</div>