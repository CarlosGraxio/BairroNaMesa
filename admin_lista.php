<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'config.php';

if (!isset($_SESSION['utilizador_id']) || $_SESSION['tipo'] !== 'admin') {
    echo "<script>alert('Acesso negado!'); window.location.href='index.php';</script>";
    exit();
}

$sql = "SELECT id, nome, apelido, email, foto_perfil FROM utilizadores WHERE tipo='admin' ORDER BY nome";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Lista de Administradores</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="support-body">

<div class="add-admin-container">
    <h1>Lista de Administradores</h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="add-admin-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="add-admin-item">
                    <img src="<?php echo $row['foto_perfil'] ?: 'img/default-profile.jpg'; ?>" class="admin-small-photo">

                    <div class="add-admin-info">
                        <p><strong>Nome:</strong> <?= htmlspecialchars($row['nome']) ?></p>
                        <p><strong>Apelido:</strong> <?= htmlspecialchars($row['apelido']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                    </div>

                    <div class="add-admin-actions">
                        <a href="admin_verPerfil.php?id=<?= $row['id']; ?>" class="add-admin-edit">Ver Perfil</a>
                        <a href="editar_admin.php?id=<?= $row['id']; ?>" class="add-admin-edit">Editar</a>
                        <a onclick="return confirm('Tem a certeza?')" 
                           href="add_admin.php?delete_admin=<?= $row['id']; ?>" 
                           class="add-admin-delete">Eliminar</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Nenhum administrador encontrado.</p>
    <?php endif; ?>

    <a href="admin_painel.php" class="add-admin-back-link">Voltar</a>
</div>

</body>
</html>

<?php $conn->close(); ?>
