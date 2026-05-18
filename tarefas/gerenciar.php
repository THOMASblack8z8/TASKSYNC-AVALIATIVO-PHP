<!-- Thomas Devb -->
<?php
require_once '../config/database.php';

// Verificar mensagens da URL
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Buscar todas as tarefas com informações do usuário
try {
    $sql = "SELECT t.*, u.nome as usuario_nome, u.email as usuario_email
            FROM tarefas t 
            JOIN usuarios u ON t.id_usuario = u.id_usuario 
            ORDER BY t.prioridade DESC, t.data_cadastro DESC";
    $stmt = $pdo->query($sql);
    $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erro ao buscar tarefas: " . $e->getMessage());
}

// Organizar tarefas por status
$aFazer = array_filter($tarefas, function($t) { return $t['status'] === 'a fazer'; });
$fazendo = array_filter($tarefas, function($t) { return $t['status'] === 'fazendo'; });
$concluido = array_filter($tarefas, function($t) { return $t['status'] === 'concluído'; });

// Contar tarefas
$totalTarefas = count($tarefas);
$totalAFazer = count($aFazer);
$totalFazendo = count($fazendo);
$totalConcluido = count($concluido);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Tarefas - TaskSync</title>
    <link rel="stylesheet" href="../css/kanban.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <span class="logo-icon">📊</span>
                <div>
                    <h1>Quadro Kanban</h1>
                    <p>Total de tarefas: <?php echo $totalTarefas; ?></p>
                </div>
            </div>
            <div class="header-actions">
                <a href="cadastrar.php" class="btn btn-primary">
                    📝 Nova Tarefa
                </a>
                <a href="../usuarios/cadastrar.php" class="btn btn-success">
                    👤 Novo Usuário
                </a>
                <a href="../index.php" class="btn btn-info">
                    🏠 Início
                </a>
            </div>
        </div>
        
        <!-- Mensagens -->
        <?php if($msg): ?>
            <div class="alert alert-success">
                ✅ <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="alert alert-error">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <!-- Quadro Kanban -->
        <div class="kanban-board">
            <!-- Coluna: A Fazer -->
            <div class="kanban-column">
                <h3 class="column-header a-fazer">
                    📋 A Fazer
                    <span class="column-count"><?php echo $totalAFazer; ?></span>
                </h3>
                
                <?php if($totalAFazer > 0): ?>
                    <?php foreach($aFazer as $tarefa): ?>
                        <div class="task-card prioridade-<?php echo $tarefa['prioridade']; ?>">
                            <div class="task-header">
                                <span class="task-title">
                                    <?php echo htmlspecialchars($tarefa['descricao']); ?>
                                </span>
                                <span class="task-badge badge-<?php echo $tarefa['prioridade']; ?>">
                                    <?php echo $tarefa['prioridade']; ?>
                                </span>
                            </div>
                            
                            <div class="task-info">
                                <div class="task-info-item">
                                    <span class="task-info-icon">👤</span>
                                    <?php echo htmlspecialchars($tarefa['usuario_nome']); ?>
                                </div>
                                <div class="task-info-item">
                                    <span class="task-info-icon">🏢</span>
                                    <?php echo htmlspecialchars($tarefa['setor']); ?>
                                </div>
                                <div class="task-info-item">
                                    <span class="task-info-icon">📅</span>
                                    <?php echo date('d/m/Y', strtotime($tarefa['data_cadastro'])); ?>
                                </div>
                            </div>
                            
                            <div class="task-actions">
                                <a href="editar.php?id=<?php echo $tarefa['id_tarefa']; ?>" 
                                   class="btn btn-warning btn-sm">✏️</a>
                                <a href="excluir.php?id=<?php echo $tarefa['id_tarefa']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')">🗑️</a>
                                <a href="alterar_status.php?id=<?php echo $tarefa['id_tarefa']; ?>&status=fazendo" 
                                   class="btn btn-primary btn-sm">▶️ Iniciar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-column">
                        <div class="empty-icon">📭</div>
                        <div class="empty-text">Nenhuma tarefa pendente</div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Coluna: Fazendo -->
            <div class="kanban-column">
                <h3 class="column-header fazendo">
                    🔄 Em Andamento
                    <span class="column-count"><?php echo $totalFazendo; ?></span>
                </h3>
                
                <?php if($totalFazendo > 0): ?>
                    <?php foreach($fazendo as $tarefa): ?>
                        <div class="task-card prioridade-<?php echo $tarefa['prioridade']; ?>">
                            <div class="task-header">
                                <span class="task-title">
                                    <?php echo htmlspecialchars($tarefa['descricao']); ?>
                                </span>
                                <span class="task-badge badge-<?php echo $tarefa['prioridade']; ?>">
                                    <?php echo $tarefa['prioridade']; ?>
                                </span>
                            </div>
                            
                            <div class="task-info">
                                <div class="task-info-item">
                                    <span class="task-info-icon">👤</span>
                                    <?php echo htmlspecialchars($tarefa['usuario_nome']); ?>
                                </div>
                                <div class="task-info-item">
                                    <span class="task-info-icon">🏢</span>
                                    <?php echo htmlspecialchars($tarefa['setor']); ?>
                                </div>
                                <div class="task-info-item">
                                    <span class="task-info-icon">📅</span>
                                    <?php echo date('d/m/Y', strtotime($tarefa['data_cadastro'])); ?>
                                </div>
                            </div>
                            
                            <div class="task-actions">
                                <a href="editar.php?id=<?php echo $tarefa['id_tarefa']; ?>" 
                                   class="btn btn-warning btn-sm">✏️</a>
                                <a href="excluir.php?id=<?php echo $tarefa['id_tarefa']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')">🗑️</a>
                                <a href="alterar_status.php?id=<?php echo $tarefa['id_tarefa']; ?>&status=concluído" 
                                   class="btn btn-success btn-sm">✅ Concluir</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-column">
                        <div class="empty-icon">📭</div>
                        <div class="empty-text">Nenhuma tarefa em andamento</div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Coluna: Concluído -->
            <div class="kanban-column">
                <h3 class="column-header concluido">
                    ✨ Concluído
                    <span class="column-count"><?php echo $totalConcluido; ?></span>
                </h3>
                
                <?php if($totalConcluido > 0): ?>
                    <?php foreach($concluido as $tarefa): ?>
                        <div class="task-card prioridade-<?php echo $tarefa['prioridade']; ?>">
                            <div class="task-header">
                                <span class="task-title">
                                    <?php echo htmlspecialchars($tarefa['descricao']); ?>
                                </span>
                                <span class="task-badge badge-<?php echo $tarefa['prioridade']; ?>">
                                    <?php echo $tarefa['prioridade']; ?>
                                </span>
                            </div>
                            
                            <div class="task-info">
                                <div class="task-info-item">
                                    <span class="task-info-icon">👤</span>
                                    <?php echo htmlspecialchars($tarefa['usuario_nome']); ?>
                                </div>
                                <div class="task-info-item">
                                    <span class="task-info-icon">🏢</span>
                                    <?php echo htmlspecialchars($tarefa['setor']); ?>
                                </div>
                                <div class="task-info-item">
                                    <span class="task-info-icon">📅</span>
                                    <?php echo date('d/m/Y', strtotime($tarefa['data_cadastro'])); ?>
                                </div>
                            </div>
                            
                            <div class="task-actions">
                                <a href="editar.php?id=<?php echo $tarefa['id_tarefa']; ?>" 
                                   class="btn btn-warning btn-sm">✏️</a>
                                <a href="excluir.php?id=<?php echo $tarefa['id_tarefa']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')">🗑️</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-column">
                        <div class="empty-icon">📭</div>
                        <div class="empty-text">Nenhuma tarefa concluída</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Script para confirmação de exclusão -->
    <script>
    // Adiciona animação aos cards quando carregam
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.task-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = (index * 0.1) + 's';
        });
    });
    </script>
</body>
</html>