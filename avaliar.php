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
        $nota = filter_input(INPUT_POST, 'nota', FILTER_VALIDATE_INT);
        $comentario = trim(filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_STRING));
        
        if (!$nota || $nota < 1 || $nota > 5) {
            $erro = "Selecione uma nota válida (1 a 5).";
        } elseif (empty($comentario)) {
            $erro = "O comentário é obrigatório.";
        } else {
            $sql = "INSERT INTO avaliacoes (restaurante_id, user_id, comentario, nota) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$restaurante_id, $_SESSION['user_id'], $comentario, $nota])) {
                $sucesso = "Avaliação enviada com sucesso!";
            } else {
                $erro = "Erro ao enviar avaliação.";
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
    <title>Avaliar Restaurante - <?= $site_name ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/logo.png" alt="Logo do <?= $site_name ?>" style="height: 50px;">
        </div>
        <h1>Avaliar Restaurante</h1>
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
            <h2>Deixe sua Avaliação</h2>
            <?php 
            if (isset($erro)) echo "<p style='color: red;'>$erro</p>";
            if (isset($sucesso)) echo "<p style='color: green;'>$sucesso</p>";
            ?>
            <form action="" method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <label for="nota">Nota (1 a 5)</label>
                <select name="nota" id="nota" required>
                    <option value="">Selecione</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <label for="comentario">Comentário</label>
                <textarea name="comentario" id="comentario" rows="5" required></textarea>
                <button type="submit">Enviar Avaliação</button>
            </form>
            <p><a href="detalhes.php?id=<?= $restaurante_id ?>">Voltar</a></p>
        </section>
    </main>
    <footer>
        <p>© <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>