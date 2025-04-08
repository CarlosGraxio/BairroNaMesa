<?php
require_once 'config.php';

$restaurante_id = $_GET['id'] ?? null;
$default_image = 'image/default.jpg';

if (!$restaurante_id) {
    header('HTTP/1.1 404 Not Found');
    if (file_exists($default_image)) {
        header('Content-Type: image/jpeg');
        readfile($default_image);
    } else {
        header('Content-Type: text/plain');
        echo "Imagem padrão não encontrada.";
    }
    exit();
}

try {
    $sql = "SELECT imagem FROM restaurantes WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$restaurante_id]);
    $imagem_caminho = $stmt->fetchColumn();

    if ($imagem_caminho && file_exists($imagem_caminho)) {
        header('Content-Type: ' . mime_content_type($imagem_caminho));
        readfile($imagem_caminho);
    } else {
        if (file_exists($default_image)) {
            header('Content-Type: image/jpeg');
            readfile($default_image);
        } else {
            header('Content-Type: text/plain');
            echo "Imagem padrão não encontrada.";
        }
    }
} catch (PDOException $e) {
    if (file_exists($default_image)) {
        header('Content-Type: image/jpeg');
        readfile($default_image);
    } else {
        header('Content-Type: text/plain');
        echo "Erro no banco de dados e imagem padrão não encontrada.";
    }
}
exit();