<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f0f9ff;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            width: 100%;
            padding: 20px;
            background: #0ea5e9;
            color: white;
            font-size: 22px;
            font-weight: bold;
            text-align: center;
        }

        .container {
            background: white;
            margin-top: 60px;
            padding: 40px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #0ea5e9;
        }

        button {
            padding: 12px;
            border: none;
            background: #0ea5e9;
            color: white;
            font-size: 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            background: #0284c7;
        }

        a {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #0ea5e9;
            text-decoration: none;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
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
        if (isset($_POST['login'])) {
            $email = $_POST['email'];
            $senha = md5($_POST['senha']);

            $sql = $conn->query("SELECT * FROM usuarios WHERE email='$email' AND senha='$senha'");
            if ($sql->num_rows > 0) {
                $_SESSION['user'] = $sql->fetch_assoc();
                header("Location: dashboard.php");
            } else {
                echo "Login inválido.";
            }
        }
        ?>
    </div>
</body>

</html>