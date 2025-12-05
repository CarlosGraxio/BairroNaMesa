<?php
session_start();
require 'config.php';

// Verificar sessão
if (!isset($_SESSION['utilizadores_id'])) {
    echo "<script>alert('É necessário estar logado para acessar o perfil.'); window.location.href='login.php';</script>";
    exit();
}

$utilizadores_id = $_SESSION['utilizadores_id'];
$mensagem = "";

// Criar token CSRF se não existir
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/* ==========================================================
   BUSCAR DADOS DO UTILIZADOR
   ========================================================== */

$sql = "SELECT nome, apelido, foto_perfil FROM utilizadores WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$utilizadores_id]);
$utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilizador) {
    echo "<script>alert('Conta não encontrada.'); window.location.href='logout.php';</script>";
    exit();
}

/* ==========================================================
   REMOVER FOTO DE PERFIL
   ========================================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remover_foto'])) {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $mensagem = "Erro de validação.";
    } else {
        if ($utilizador['foto_perfil'] && file_exists($utilizador['foto_perfil'])) {
            unlink($utilizador['foto_perfil']);
        }

        $sql = "UPDATE utilizadores SET foto_perfil = NULL WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$utilizadores_id]);

        $mensagem = "Foto removida com sucesso.";
        $utilizador['foto_perfil'] = null;
    }
}

/* ==========================================================
   ATUALIZAR PERFIL
   ========================================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salvar_perfil'])) {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $mensagem = "Erro de validação.";
    } else {

        $nome = trim($_POST['nome']);
        $apelido = trim($_POST['apelido']);

        $erros = [];

        if (empty($apelido)) $erros[] = "O nome de utilizador é obrigatório.";
        if (empty($nome))    $erros[] = "O nome completo é obrigatório.";

        // Verificar se apelido já existe
        $sql = "SELECT id FROM utilizadores WHERE apelido = ? AND id != ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$apelido, $utilizadores_id]);
        if ($stmt->rowCount() > 0) {
            $erros[] = "Este nome de utilizador já está em uso.";
        }

        if (empty($erros)) {

            // Atualizar nome e apelido
            $sql = "UPDATE utilizadores SET nome = ?, apelido = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $apelido, $utilizadores_id]);

            // Upload da foto
            if (!empty($_FILES['foto_perfil']['name'])) {
                $foto = $_FILES['foto_perfil'];
                $ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
                $permitidos = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($ext, $permitidos)) {

                    $dir = "img/perfil/";
                    if (!is_dir($dir)) mkdir($dir, 0777, true);

                    $novo_nome = $dir . "perfil_" . $utilizadores_id . "_" . time() . "." . $ext;

                    if (move_uploaded_file($foto['tmp_name'], $novo_nome)) {

                        // apagar anterior
                        if ($utilizador['foto_perfil'] && file_exists($utilizador['foto_perfil']))
                            unlink($utilizador['foto_perfil']);

                        $sql = "UPDATE utilizadores SET foto_perfil = ? WHERE id = ?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$novo_nome, $utilizadores_id]);

                        $mensagem .= " Foto atualizada com sucesso!";
                        $utilizador['foto_perfil'] = $novo_nome;

                    } else {
                        $mensagem .= " Erro ao enviar foto.";
                    }

                } else {
                    $mensagem .= " Tipos permitidos: JPG, JPEG, PNG, GIF.";
                }
            }

            if (!$mensagem) $mensagem = "Perfil atualizado com sucesso!";
        } else {
            $mensagem = implode("<br>", $erros);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Configurações - Bairro na Mesa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="client-perfil-container">
    <h1>Configurações</h1>

    <?php if ($mensagem): ?>
        <p class="client-perfil-message"><?= $mensagem ?></p>
    <?php endif; ?>

    <!-- FOTO -->
    <div class="client-perfil-photo-section">
        <?php if ($utilizador['foto_perfil']): ?>
            <img src="<?= htmlspecialchars($utilizador['foto_perfil']) ?>" class="client-perfil-photo">

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <button type="submit" name="remover_foto">Remover Foto</button>
            </form>

        <?php else: ?>
            <p>Sem foto de perfil</p>
        <?php endif; ?>
    </div>

    <form method="POST" enctype="multipart/form-data" class="client-perfil-form">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <label>Nova Foto:</label>
        <input type="file" name="foto_perfil" accept="image/*">

        <label>Nome Completo:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($utilizador['nome']) ?>" required>

        <label>Nome de Utilizador:</label>
        <input type="text" name="apelido" value="<?= htmlspecialchars($utilizador['apelido']) ?>" required>

        <button type="submit" name="salvar_perfil">Salvar Alterações</button>
    </form>

    <a href="configuracoes.php">Editar Endereços</a><br>
    <a href="index.php">Voltar ao Início</a>
</div>

</body>
</html>
