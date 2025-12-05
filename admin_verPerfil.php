<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'config.php';

if (!isset($_SESSION['utilizador_id']) || $_SESSION['tipo'] !== 'admin') {
    echo "<script>alert('Acesso negado!'); window.location.href='index.php';</script>";
    exit();
}

$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM utilizadores WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$admin) {
    echo "<script>alert('Administrador não encontrado!'); window.location.href='admin_lista.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Administrador</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="support-body">
<div class="add-admin-container">

    <h1>Perfil do Administrador</h1>

    <img src="<?= $admin['foto_perfil'] ?: 'img/default-profile.jpg' ?>" class="admin-big-photo">

    <p><strong>Nome:</strong> <?= $admin['nome']; ?></p>
    <p><strong>Apelido:</strong> <?= $admin['apelido']; ?></p>
    <p><strong>Email:</strong> <?= $admin['email']; ?></p>
    <p><strong>Telefone:</strong> <?= $admin['telefone']; ?></p>

    <a href="admin_lista.php" class="add-admin-back-link">Voltar</a>

</div>
</body>
</html>

<?php $conn->close(); ?>
