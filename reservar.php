<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$restaurante_id = filter_input(INPUT_GET, 'restaurante_id', FILTER_VALIDATE_INT);
if (!$restaurante_id) {
    header("Location: restaurantes.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $erro = "Erro de validação. Tente novamente.";
    } else {
        $data_reserva = trim($_POST['data_reserva']);
        $num_pessoas = filter_input(INPUT_POST, 'num_pessoas', FILTER_VALIDATE_INT);
        
        if (empty($data_reserva) || !$num_pessoas || $num_pessoas < 1) {
            $erro = "Preencha todos os campos corretamente.";
        } else {
            $sql = "INSERT INTO reservas (restaurante_id, user_id, data_reserva, num_pessoas, status) VALUES (?, ?, ?, ?, 'pendente')";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$restaurante_id, $_SESSION['user_id'], $data_reserva, $num_pessoas])) {
                $sucesso = "Reserva enviada com sucesso! Aguarde confirmação.";
            } else {
                $erro = "Erro ao enviar reserva.";
            }
        }
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fazer Reserva - <?= $site_name ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/logo.png" alt="Logo do <?= $site_name ?>" style="height: 50px;">
        </div>
        <h1>Fazer Reserva</h1>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="restaurantes.php">Restaurantes</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Reservar Mesa</h2>
            <?php 
            if (isset($erro)) echo "<p style='color: red;'>$erro</p>";
            if (isset($sucesso)) echo "<p style='color: green;'>$sucesso</p>";
            ?>
            <form action="" method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <label for="data_reserva">Data e Hora</label>
                <input type="datetime-local" name="data_reserva" id="data_reserva" required>
                <label for="num_pessoas">Número de Pessoas</label>
                <input type="number" name="num_pessoas" id="num_pessoas" min="1" required>
                <button type="submit">Reservar</button>
            </form>
            <p><a href="detalhes.php?id=<?= $restaurante_id ?>">Voltar</a></p>
        </section>
    </main>
    <footer>
        <p>© <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>