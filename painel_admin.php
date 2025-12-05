<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config.php'; // conexão com o banco de dados

// Verifica login e tipo
if (!isset($_SESSION['utilizadores_id']) || !isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    echo "<script>alert('Acesso negado! Apenas administradores podem aceder ao painel.'); window.location.href='index.php';</script>";
    exit();
}

$utilizador_id = $_SESSION['utilizadores_id'];

// Buscar dados do admin
$sql = "SELECT nome, apelido, email, tipo, foto_perfil FROM utilizadores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $utilizador_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

$admin['foto_perfil'] = $admin['foto_perfil'] ?? 'img/default-profile.jpg';
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Mercado Bom Preço</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="support-body">

    <div class="admin-panel-container">

        <div class="admin-panel-header">
            <div class="admin-profile-info">
                <img src="<?php echo htmlspecialchars($admin['foto_perfil']); ?>" alt="Foto do Administrador" class="admin-panel-photo">
                <div class="admin-panel-details">
                    <h2><?php echo htmlspecialchars($admin['nome'] . " " . $admin['apelido']); ?></h2>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
                    <p><strong>Tipo:</strong> Administrador</p>
                </div>
            </div>

            <div class="admin-panel-menu">
                <a href="admin_perfil.php">Configurações</a>
                <a href="admin_verPerfil.php?id=<?php echo $utilizador_id; ?>">Ver Perfil</a>
                <a href="logout.php" class="logout-btn">Sair</a>
            </div>
        </div>

        <h1 class="admin-panel-title">Painel Administrativo</h1>

        <div class="admin-panel-options">

            <a href="add_admin.php" class="admin-panel-card">
                <h3>Gestão de Administradores</h3>
                <p>Adicionar, editar ou remover administradores.</p>
            </a>

            <a href="admin_lista.php" class="admin-panel-card">
                <h3>Lista de Administradores</h3>
                <p>Consultar todos os administradores activos.</p>
            </a>

            <a href="admin_perfil.php" class="admin-panel-card">
                <h3>O Meu Perfil</h3>
                <p>Alterar dados pessoais, foto e definições.</p>
            </a>

            <a href="notificacoes.php" class="admin-panel-card">
                <h3>Notificações</h3>
                <p>Ver e gerir alertas do sistema.</p>
            </a>

            <a href="suporte_admin.php" class="admin-panel-card">
                <h3>Suporte</h3>
                <p>Aceder à área de suporte técnico.</p>
            </a>

        </div>

        <a href="index.php" class="admin-panel-back">Voltar ao Início</a>

    </div>

</body>

</html>

<?php
$conn->close();
?>
