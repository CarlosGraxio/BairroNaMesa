<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'config.php';

if (!isset($_SESSION['utilizador_id']) || $_SESSION['tipo'] !== 'admin') {
    echo "<script>alert('Acesso negado!'); window.location.href='index.php';</script>";
    exit();
}

$id = $_SESSION['utilizador_id'];

$sql = "SELECT * FROM utilizadores WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $apelido = trim($_POST['apelido']);
    $telefone = trim($_POST['telefone']);

    $sql = "UPDATE utilizadores SET nome=?, apelido=?, telefone=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nome, $apelido, $telefone, $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Perfil atualizado!'); window.location.href='admin_perfil.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="support-body">
<div class="add-admin-container">
    <h1>O Meu Perfil</h1>

    <form method="POST">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= $admin['nome'] ?>" required>

        <label>Apelido:</label>
        <input type="text" name="apelido" value="<?= $admin['apelido'] ?>" required>

        <label>Telefone:</label>
        <input type="text" name="telefone" value="<?= $admin['telefone'] ?>" required>

        <button type="submit">Guardar Alterações</button>
    </form>

    <a href="admin_painel.php" class="add-admin-back-link">Voltar</a>
</div>
</body>
</html>

<?php $conn->close(); ?>
