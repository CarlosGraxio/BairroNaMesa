<div class="widget-avaliacoes">
    <h3>Avaliações Recentes</h3>
    <div class="avaliacoes-lista">
        <?php
        $sql = "SELECT a.nota, a.comentario, a.data_avaliacao, r.nome as restaurante_nome, r.id as restaurante_id
                FROM avaliacoes a
                JOIN restaurantes r ON a.restaurante_id = r.id
                ORDER BY a.data_avaliacao DESC
                LIMIT 3";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($avaliacoes) {
                foreach ($avaliacoes as $avaliacao) {
                    echo '<div class="avaliacao-item">';
                    echo '<h4><a href="detalhes.php?id=' . $avaliacao['restaurante_id'] . '">' . htmlspecialchars($avaliacao['restaurante_nome']) . '</a></h4>';
                    echo '<div class="estrelas">' . str_repeat('★', $avaliacao['nota']) . str_repeat('☆', 5 - $avaliacao['nota']) . '</div>';
                    echo '<p class="comentario">' . htmlspecialchars($avaliacao['comentario']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhuma avaliação recente.</p>';
            }
        } catch (PDOException $e) {
            echo '<p>Erro: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
    </div>
</div>