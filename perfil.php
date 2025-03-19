<?php
session_start();
require_once 'config.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = new mysqli("localhost", "root", "", "bairro_mesa");

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Buscar informações do usuário
$sql = "SELECT nome, email, telefone, foto_perfil FROM utilizadores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Processar atualização do perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $senha = trim($_POST['senha']);
    $foto_perfil = $user['foto_perfil'];

    // Atualizar foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $target_dir = "uploads/";
        $foto_perfil = $target_dir . basename($_FILES["foto_perfil"]["name"]);
        move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $foto_perfil);
    }

    // Atualizar senha apenas se fornecida
    if (!empty($senha)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE utilizadores SET nome = ?, email = ?, telefone = ?, senha = ?, foto_perfil = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nome, $email, $telefone, $senha_hash, $foto_perfil, $user_id);
    } else {
        $sql = "UPDATE utilizadores SET nome = ?, email = ?, telefone = ?, foto_perfil = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nome, $email, $telefone, $foto_perfil, $user_id);
    }

    if ($stmt->execute()) {
        $mensagem = "<p style='color: green;'>Perfil atualizado com sucesso!</p>";
    } else {
        $mensagem = "<p style='color: red;'>Erro ao atualizar perfil.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - <?= $site_name ?></title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="login-container">
        <h1>Configurações do Perfil</h1>

        <?php if (isset($mensagem)) echo $mensagem; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <!-- Foto de Perfil -->
            <label>Foto de Perfil</label>
            <div style="margin-bottom: 15px;">
                <img src="<?= $user['foto_perfil'] ? $user['foto_perfil'] : 'image/default_profile.png' ?>" 
                     alt="Foto de Perfil" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                <input type="file" name="foto_perfil" accept="image/*" style="margin-top: 10px;">
            </div>

            <!-- Nome -->
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required>

            <!-- Email -->
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <!-- Telefone -->
            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($user['telefone'] ?? '') ?>" placeholder="Insira seu telefone">

            <!-- Senha -->
            <label for="senha">Nova Senha (deixe em branco para não alterar)</label>
            <input type="password" id="senha" name="senha" placeholder="Insira uma nova senha">

            <button type="submit">Salvar Alterações</button>
        </form>
        <p><a href="index.php">Voltar ao Início</a></p>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>