<?php
require_once 'config.php';
if (!isset($pdo) || $pdo === null) {
    die("Erro: Conexão com o banco de dados não estabelecida.");
}

// Verificar se o ID do restaurante foi fornecido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Erro: ID do restaurante não fornecido ou inválido.");
}

$restaurante_id = (int)$_GET['id'];

// Buscar detalhes do restaurante
$sql_restaurante = "SELECT nome, descricao FROM restaurantes WHERE id = :id";
$stmt_restaurante = $pdo->prepare($sql_restaurante);
$stmt_restaurante->bindValue(':id', $restaurante_id, PDO::PARAM_INT);
$stmt_restaurante->execute();
$restaurante = $stmt_restaurante->fetch(PDO::FETCH_ASSOC);

if (!$restaurante) {
    die("Erro: Restaurante não encontrado.");
}

// Processar o envio de uma nova avaliação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nota'], $_POST['comentario'])) {
    $nota = (int)$_POST['nota'];
    $comentario = trim($_POST['comentario']);
    $user_id = 1; // Substitua por um sistema de autenticação real (ex.: ID do usuário logado)

    // Validar a nota (entre 1 a 5)
    if ($nota < 1 || $nota > 5) {
        $erro = "A nota deve estar entre 1 e 5.";
    } elseif (empty($comentario)) {
        $erro = "O comentário não pode estar vazio.";
    } else {
        // Inserir a avaliação no banco de dados
        $sql_insert = "INSERT INTO avaliacoes (restaurante_id, user_id, nota, comentario, data_avaliacao) 
                       VALUES (:restaurante_id, :user_id, :nota, :comentario, NOW())";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->bindValue(':restaurante_id', $restaurante_id, PDO::PARAM_INT);
        $stmt_insert->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_insert->bindValue(':nota', $nota, PDO::PARAM_INT);
        $stmt_insert->bindValue(':comentario', $comentario, PDO::PARAM_STR);

        try {
            $stmt_insert->execute();
            $sucesso = "Avaliação enviada com sucesso!";
        } catch (PDOException $e) {
            $erro = "Erro ao enviar a avaliação: " . $e->getMessage();
        }
    }
}

// Determinar o filtro de avaliações
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'recentes';

// Buscar avaliações existentes com base no filtro
$sql_avaliacoes = "SELECT a.nota, a.comentario, a.data_avaliacao, a.user_id 
                   FROM avaliacoes a 
                   WHERE a.restaurante_id = :restaurante_id";
if ($filtro === 'com-comentarios') {
    $sql_avaliacoes .= " AND a.comentario IS NOT NULL AND a.comentario != ''";
}
$sql_avaliacoes .= " ORDER BY a.data_avaliacao DESC";

$stmt_avaliacoes = $pdo->prepare($sql_avaliacoes);
$stmt_avaliacoes->bindValue(':restaurante_id', $restaurante_id, PDO::PARAM_INT);
$stmt_avaliacoes->execute();
$avaliacoes = $stmt_avaliacoes->fetchAll(PDO::FETCH_ASSOC);

// Buscar nomes dos usuários (corrigindo o nome da tabela para 'utilizadores')
$users = [];
if (!empty($avaliacoes)) {
    $user_ids = array_unique(array_column($avaliacoes, 'user_id'));
    // Verificar se $user_ids não está vazio antes de executar a consulta
    if (!empty($user_ids)) {
        $sql_users = "SELECT id, nome FROM utilizadores WHERE id IN (" . implode(',', array_fill(0, count($user_ids), '?')) . ")";
        $stmt_users = $pdo->prepare($sql_users);
        try {
            $stmt_users->execute($user_ids);
            $users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
            $users = array_column($users, 'nome', 'id');
        } catch (PDOException $e) {
            // Tratar erro, mas não interromper a execução
            $users = [];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Restaurante - <?= htmlspecialchars($restaurante['nome']) ?> - <?= $site_name ?></title>
    <link rel="stylesheet" href="style4.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/logo.png" alt="Logo do <?= $site_name ?>" style="height: 50px;">
        </div>
        <h1>Detalhes do Restaurante</h1>
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
        <section id="detalhes-restaurante">
            <h2><?= htmlspecialchars($restaurante['nome']) ?></h2>
            <img src="get_imagem.php?id=<?= $restaurante_id ?>" alt="<?= htmlspecialchars($restaurante['nome']) ?>">
            <p><?= htmlspecialchars($restaurante['descricao']) ?></p>

            <h3>Avaliações</h3>
            <div class="filtro-avaliacoes">
                <a href="?id=<?= $restaurante_id ?>&filtro=recentes" class="<?= $filtro === 'recentes' ? 'ativo' : '' ?>">Mais recentes</a>
                <a href="?id=<?= $restaurante_id ?>&filtro=com-comentarios" class="<?= $filtro === 'com-comentarios' ? 'ativo' : '' ?>">Apenas com comentários</a>
            </div>

            <?php if (!empty($avaliacoes)): ?>
                <div class="avaliacoes-lista">
                    <?php foreach ($avaliacoes as $avaliacao): ?>
                        <div class="avaliacao-item">
                            <div class="avaliacao-header">
                                <div class="usuario-info">
                                    <img src="image/user-placeholder.png" alt="Avatar" class="avatar">
                                    <div>
                                        <span class="usuario-nome">
                                            <?= isset($users[$avaliacao['user_id']]) ? htmlspecialchars($users[$avaliacao['user_id']]) : 'Usuário Anônimo' ?>
                                        </span>
                                        <span class="data"><?= date('d \d\e M \d\e Y', strtotime($avaliacao['data_avaliacao'])) ?></span>
                                    </div>
                                </div>
                                <div class="nota">
                                    <span class="estrelas"><?= str_repeat('★', $avaliacao['nota']) . str_repeat('☆', 5 - $avaliacao['nota']) ?></span>
                                    <span class="nota-numero"><?= $avaliacao['nota'] ?>/5</span>
                                </div>
                            </div>
                            <p class="comentario"><?= htmlspecialchars($avaliacao['comentario']) ?></p>
                            <div class="avaliacao-acoes">
                                <button class="acao gostar">Gostar <span class="contador">0</span></button>
                                <button class="acao denunciar">Denunciar</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Este restaurante ainda não tem avaliações.</p>
            <?php endif; ?>

            <h3>Deixe sua Avaliação</h3>
            <?php if (isset($erro)): ?>
                <p class="erro"><?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>
            <?php if (isset($sucesso)): ?>
                <p class="sucesso"><?= htmlspecialchars($sucesso) ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nota">Avaliação (1 a 5 estrelas):</label>
                    <select name="nota" id="nota" required>
                        <option value="">Selecione...</option>
                        <option value="1">1 ★</option>
                        <option value="2">2 ★★</option>
                        <option value="3">3 ★★★</option>
                        <option value="4">4 ★★★★</option>
                        <option value="5">5 ★★★★★</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="comentario">Comentário:</label>
                    <textarea name="comentario" id="comentario" rows="4" required></textarea>
                </div>
                <button type="submit">Enviar Avaliação</button>
            </form>
        </section>
    </main>

    <footer>
        <p>© <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>