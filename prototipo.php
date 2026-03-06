<?php
require_once "config.php";

// --- LÓGICA DE LOGOUT ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ?v=login");
    exit;
}

// --- LÓGICA DE LOGIN ---
if (isset($_POST['login_action'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha']; // Idealmente use password_verify se a senha estiver em hash

    $sql = "SELECT id, nome, tipo, senha FROM usuarios WHERE email = '$email'";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        // Verificação simples (Se usar hash no futuro, mude para password_verify)
        if ($senha === $user['senha']) {
            // No momento do login (quando validar no banco)
            $_SESSION['usuario'] = [
                'id'   => $dados_do_banco['id'],
                'nome' => $dados_do_banco['nome'],
                'tipo' => $dados_do_banco['tipo'] // aqui seria 'admin' ou 'cliente'
            ];
            header("Location: ?v=catalogo");
            exit;
        }
    }
    $erro = "E-mail ou senha incorretos.";
}

// --- LÓGICA DE AGENDAMENTO (POST via JS ou Form) ---
if (isset($_POST['confirmar_reserva'])) {
    $u_id = $_SESSION['usuario']['id'];
    $s_id = $_POST['servico_id'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];

    $sql = "INSERT INTO agendamentos (usuario_id, servico_id, data, horario, status) 
            VALUES ('$u_id', '$s_id', '$data', '$hora', 'pendente')";
    $conn->query($sql);
    header("Location: ?v=meus_agendamentos");
    exit;
}

$view = $_GET['v'] ?? 'login';
$isAdmin = (isset($_SESSION['usuario']) && $_SESSION['usuario'] === 'admin');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>AlmaFisio | Fisioterapia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* [SEU CSS ORIGINAL AQUI] */
        :root {
            --primary: #0ea5e9;
            --secondary: #f0f9ff;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --white: #ffffff;
            --success: #10b981;
            --danger: #ef4444;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--secondary);
            margin: 0;
        }

        nav {
            background: var(--white);
            padding: 1.2rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-light);
            margin-left: 20px;
            font-size: 0.9rem;
        }

        .container {
            max-width: 1100px;
            margin: 3rem auto;
            padding: 0 1.5rem;
        }

        .card {
            background: var(--white);
            padding: 2rem;
            border-radius: 16px;
            border: 1px solid rgba(14, 165, 233, 0.1);
        }

        .grid-servicos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .servico-item {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        input,
        select {
            width: 100%;
            padding: 0.8rem;
            margin: 0.5rem 0 1.2rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        button {
            cursor: pointer;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            background: var(--primary);
            color: white;
            font-weight: 600;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }

        th {
            background: #f1f5f9;
            padding: 1rem;
            text-align: left;
        }

        td {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .confirmado {
            background: #dcfce7;
            color: #166534;
        }

        .pendente {
            background: #fef9c3;
            color: #854d0e;
        }
    </style>
</head>

<body>

    <nav>
        <div class="logo"><i class="fa-solid fa-person-running"></i> AlmaFisio</div>
        <div class="nav-links">
            <?php if (isset($_SESSION['usuario'])): ?>
                <a href="?v=catalogo">Serviços</a>
                <a href="?v=meus_agendamentos">Minha Agenda</a>
                <?php if ($isAdmin): ?>
                    <a href="?v=admin" style="color: var(--primary); font-weight:bold;"><i class="fa-solid fa-lock"></i> Admin</a>
                <?php endif; ?>
                <a href="?logout=1"><i class="fa-solid fa-right-from-bracket"></i> Sair</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">

        <?php if ($view == 'login'): ?>
            <div class="card" style="max-width: 400px; margin: auto;">
                <h2 style="text-align: center;">Acesso</h2>
                <?php if (isset($erro)) echo "<p style='color:red; text-align:center;'>$erro</p>"; ?>
                <form method="POST">
                    <input type="hidden" name="login_action" value="1">
                    <label>E-mail</label>
                    <input type="email" name="email" required>
                    <label>Senha</label>
                    <input type="password" name="senha" required>
                    <button type="submit">Entrar</button>
                </form>
            </div>

        <?php elseif ($view == 'catalogo'): ?>
            <h1>Nossos Tratamentos</h1>
            <div class="grid-servicos">
                <?php
                $sql = "SELECT * FROM servicos";
                $res = $conn->query($sql);
                while ($s = $res->fetch_assoc()):
                ?>
                    <div class="servico-item">
                        <i class="fa-solid fa-kit-medical" style="font-size: 1.5rem; color: var(--primary);"></i>
                        <h3><?php echo $s['nome']; ?></h3>
                        <p>Preço: <strong>R$ <?php echo number_format($s['preco'], 2, ',', '.'); ?></strong></p>
                        <button onclick="abrirAgendamento(<?php echo $s['id']; ?>, '<?php echo $s['nome']; ?>')">Agendar</button>
                    </div>
                <?php endwhile; ?>
            </div>

            <div id="areaReserva" class="card" style="display:none; margin-top: 2rem;">
                <h3>Reservar: <span id="txtServico"></span></h3>
                <form method="POST">
                    <input type="hidden" name="confirmar_reserva" value="1">
                    <input type="hidden" name="servico_id" id="inputServicoId">
                    <label>Data</label>
                    <input type="date" name="data" required>
                    <label>Hora</label>
                    <select name="hora">
                        <option>08:00</option>
                        <option>09:00</option>
                        <option>14:00</option>
                    </select>
                    <button type="submit">Confirmar Agora</button>
                </form>
            </div>

        <?php elseif ($view == 'meus_agendamentos'): ?>
            <div class="card">
                <h2>Minha Agenda</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Serviço</th>
                            <th>Data/Hora</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $u_id = $_SESSION['usuario']['id'];
                        $sql = "SELECT a.*, s.nome FROM agendamentos a 
                            JOIN servicos s ON a.servico_id = s.id 
                            WHERE a.usuario_id = '$u_id' ORDER BY a.data DESC";
                        $res = $conn->query($sql);
                        while ($ag = $res->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?php echo $ag['nome']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($ag['data'])); ?> às <?php echo $ag['horario']; ?></td>
                                <td><span class="status <?php echo $ag['status']; ?>"><?php echo strtoupper($ag['status']); ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($view == 'admin' && $isAdmin): ?>
            <div class="card">
                <h2>Gestão Geral - AlmaFisio</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Serviço</th>
                            <th>Data</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT a.*, s.nome as serv_nome, u.nome as pac_nome 
                            FROM agendamentos a 
                            JOIN servicos s ON a.servico_id = s.id 
                            JOIN usuarios u ON a.usuario_id = u.id";
                        $res = $conn->query($sql);
                        while ($ag = $res->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?php echo $ag['pac_nome']; ?></td>
                                <td><?php echo $ag['serv_nome']; ?></td>
                                <td><?php echo $ag['data'] . " " . $ag['horario']; ?></td>
                                <td><span class="status <?php echo $ag['status']; ?>"><?php echo $ag['status']; ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>

    <script>
        function abrirAgendamento(id, nome) {
            document.getElementById('areaReserva').style.display = 'block';
            document.getElementById('txtServico').innerText = nome;
            document.getElementById('inputServicoId').value = id;
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });
        }
    </script>
</body>

</html>