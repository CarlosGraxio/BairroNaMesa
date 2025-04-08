<?php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $restaurante_id = $_POST['restaurante_id'] ?? null;
    $imagem = $_FILES['imagem'] ?? null;

    if (!$restaurante_id || !$imagem) {
        $erro = "Selecione um restaurante e uma imagem.";
    } elseif ($imagem['error'] !== UPLOAD_ERR_OK) {
        $erro = "Erro ao fazer upload da imagem.";
    } else {
        // Verificar o tipo de arquivo
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imagem['type'], $tipos_permitidos)) {
            $erro = "Apenas arquivos JPEG, PNG e GIF são permitidos.";
        } else {
            // Definir o caminho de destino
            $target_dir = "uploads/";
            $imagem_nome = uniqid() . '-' . basename($imagem['name']); // Nome único para evitar conflitos
            $target_file = $target_dir . $imagem_nome;

            // Mover o arquivo para a pasta uploads
            if (move_uploaded_file($imagem['tmp_name'], $target_file)) {
                // Atualizar o caminho da imagem no banco de dados
                $sql = "UPDATE restaurantes SET imagem = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$target_file, $restaurante_id]);

                $sucesso = "Imagem enviada com sucesso!";
            } else {
                $erro = "Erro ao salvar a imagem no servidor.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Imagem - <?= $site_name ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/logo.png" alt="Logo do <?= $site_name ?>" style="height: 50px;">
        </div>
        <h1>Upload de Imagem</h1>
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
            <h2>Enviar Imagem para Restaurante</h2>
            <?php 
            if (isset($erro)) echo "<p style='color: red;'>$erro</p>";
            if (isset($sucesso)) echo "<p style='color: green;'>$sucesso</p>";
            ?>
            <form action="" method="post" enctype="multipart/form-data">
                <label for="restaurante_id">Restaurante</label>
                <select name="restaurante_id" id="restaurante_id" required>
                    <option value="">Selecione um restaurante</option>
                    <?php
                    $sql = "SELECT id, nome FROM restaurantes";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['id']}'>" . htmlspecialchars($row['nome']) . "</option>";
                    }
                    ?>
                </select>
                <label for="imagem">Imagem</label>
                <input type="file" name="imagem" id="imagem" accept="image/*" required>
                <button type="submit">Enviar Imagem</button>
            </form>
        </section>
    </main>

    <footer>
        <p>© <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>