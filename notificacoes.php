<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'config.php';

if (!isset($_SESSION['utilizador_id']) || $_SESSION['tipo'] !== 'admin') {
    echo "<script>alert('Acesso negado!'); window.location.href='index.php';</script>";
    exit();
}

$id = $_SESSION['utilizador_id'];

$sql = "SELECT * FROM notificacoes WHERE admin_id=? ORDER BY data DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$notificacoes = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Notificações</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="support-body">
<div class="add-admin-container">
    <h1>Notificações</h1>

    <?php if ($notificacoes->num_rows > 0): ?>
        <?php while ($n = $notificacoes->fetch_assoc()): ?>
            <div class="notification-item">
                <p><?= htmlspecialchars($n['mensagem']); ?></p>
                <small><?= $n['data']; ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Sem notificações.</p>
    <?php endif; ?>

    <a href="admin_painel.php" class="add-admin-back-link">Voltar</a>
</div>
</body>
</html>

<?php $conn->close(); ?>
