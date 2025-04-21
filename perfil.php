<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $sql = "SELECT nome, email, telefone, foto_perfil FROM utilizadores WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT r.nome, res.data_reserva, res.num_pessoas, res.status FROM reservas res JOIN restaurantes r ON res.restaurante_id = r.id WHERE res.user_id = ? ORDER BY res.data_reserva DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar usuário: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $telefone = trim(filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING));
    $senha = trim($_POST['senha']);
    $foto_perfil = $user['foto_perfil'];

    // Validate file upload
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $target_dir = "../assets/uploads/";
        $target_file = $target_dir . $filename;

        if (!in_array($_FILES['foto_perfil']['type'], $tipos_permitidos)) {
            $mensagem = "<p style='color: red;'>Apenas JPEG, PNG ou GIF são permitidos.</p>";
        } elseif ($_FILES['foto_perfil']['size'] > $max_size) {
            $mensagem = "<p style='color: red;'>Arquivo muito grande. Máximo 2MB.</p>";
        } elseif (!move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $target_file)) {
            $mensagem = "<p style='color: red;'>Erro ao fazer upload da foto.</p>";
        } else {
            $foto_perfil = $target_file;
        }
    }

    try {
        if (!empty($senha)) {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "UPDATE utilizadores SET nome = ?, email = ?, telefone = ?, senha = ?, foto_perfil = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $email, $telefone, $senha_hash, $foto_perfil, $user_id]);
        } else {
            $sql = "UPDATE utilizadores SET nome = ?, email = ?, telefone = ?, foto_perfil = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $email, $telefone, $foto_perfil, $user_id]);
        }
        $mensagem = "<p style='color: green;'>Perfil atualizado com sucesso!</p>";
    } catch (PDOException $e) {
        $mensagem = "<p style='color: red;'>Erro ao atualizar perfil: " . $e->getMessage() . "</p>";
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
            <label>Foto de Perfil</label>
            <div style="margin-bottom: 15px;">
                <img src="<?= $user['foto_perfil'] ? htmlspecialchars($user['foto_perfil']) : 'image/default_profile.png' ?>" 
                     alt="Foto de Perfil" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                <input type="file" name="foto_perfil" accept="image/*" style="margin-top: 10px;">
                <section>
    <h2>Minhas Reservas</h2>
    <?php if ($reservas): ?>
        <table>
            <tr>
                <th>Restaurante</th>
                <th>Data</th>
                <th>Pessoas</th>
                <th>Status</th>
            </tr>
            <?php foreach ($reservas as $reserva): ?>
                <tr>
                    <td><?= htmlspecialchars($reserva['nome']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($reserva['data_reserva'])) ?></td>
                    <td><?= $reserva['num_pessoas'] ?></td>
                    <td><?= ucfirst($reserva['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Você não tem reservas.</p>
    <?php endif; ?>
</section>
            </div>
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($user['telefone'] ?? '') ?>" placeholder="Insira seu telefone">
            <label for="senha">Nova Senha (deixe em branco para não alterar)</label>
            <input type="password" id="senha" name="senha" placeholder="Insira uma nova senha">
            <button type="submit">Salvar Alterações</button>
        </form>
        <p><a href="index.php">Voltar ao Início</a></p>
    </div>
</body>
</html>


