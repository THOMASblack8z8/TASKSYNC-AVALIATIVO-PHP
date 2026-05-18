<!-- Thomas Devb -->
<?php
require_once '../config/database.php';

$mensagem = '';
$tipo_mensagem = '';

// Buscar usuários para o select
try {
    $usuarios = $pdo->query("SELECT id_usuario, nome, setor FROM usuarios ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erro ao buscar usuários: " . $e->getMessage());
}

// Verificar se é edição
if (!isset($_GET['id'])) {
    header('Location: gerenciar.php?error=ID não informado');
    exit;
}

$id = $_GET['id'];

// Buscar dados da tarefa
try {
    $stmt = $pdo->prepare("SELECT t.*, u.nome as usuario_nome 
                          FROM tarefas t 
                          JOIN usuarios u ON t.id_usuario = u.id_usuario 
                          WHERE t.id_tarefa = :id");
    $stmt->execute([':id' => $id]);
    $tarefa = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    header('Location: gerenciar.php?error=Erro ao buscar tarefa');
    exit;
}

if (!$tarefa) {
    header('Location: gerenciar.php?error=Tarefa não encontrada');
    exit;
}

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tarefa = $_POST['id_tarefa'];
    $id_usuario = $_POST['id_usuario'];
    $descricao = trim($_POST['descricao']);
    $setor = $_POST['setor'];
    $prioridade = $_POST['prioridade'];
    
    // Validações
    if (empty($id_usuario) || empty($descricao) || empty($setor) || empty($prioridade)) {
        $mensagem = '⚠️ Todos os campos são obrigatórios!';
        $tipo_mensagem = 'error';
    } else {
        try {
            $sql = "UPDATE tarefas SET 
                    id_usuario = :id_usuario,
                    descricao = :descricao,
                    setor = :setor,
                    prioridade = :prioridade
                    WHERE id_tarefa = :id_tarefa";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':descricao' => $descricao,
                ':setor' => $setor,
                ':prioridade' => $prioridade,
                ':id_tarefa' => $id_tarefa
            ]);
            
            $mensagem = '✅ Tarefa atualizada com sucesso!';
            $tipo_mensagem = 'success';
            
            // Recarregar dados da tarefa
            $stmt = $pdo->prepare("SELECT t.*, u.nome as usuario_nome 
                                  FROM tarefas t 
                                  JOIN usuarios u ON t.id_usuario = u.id_usuario 
                                  WHERE t.id_tarefa = :id");
            $stmt->execute([':id' => $id_tarefa]);
            $tarefa = $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            $mensagem = '❌ Erro ao atualizar tarefa: ' . $e->getMessage();
            $tipo_mensagem = 'error';
        }
    }
}

// Definir ícone e classe do status
$status_info = [
    'a fazer' => ['icon' => '📋', 'class' => 'status-a-fazer', 'label' => 'A Fazer'],
    'fazendo' => ['icon' => '🔄', 'class' => 'status-fazendo', 'label' => 'Em Andamento'],
    'concluído' => ['icon' => '✅', 'class' => 'status-concluido', 'label' => 'Concluído']
];

$status_atual = $status_info[$tarefa['status']];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa - TaskSync</title>
    <link rel="stylesheet" href="../css/editar-tarefa.css">
</head>
<body>
    <div class="editar-container">
        <div class="editar-header">
            <div class="icon">✏️</div>
            <h1>Editar Tarefa</h1>
            <p>Modifique as informações da tarefa</p>
            <div class="status-badge">
                <?php echo $status_atual['icon']; ?> 
                Status: <?php echo $status_atual['label']; ?>
            </div>
        </div>
        
        <div class="editar-body">
            <?php if($mensagem): ?>
                <div class="alert alert-<?php echo $tipo_mensagem; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>
            
            <!-- Informações atuais -->
            <div class="info-card">
                <div class="info-card-title">📌 Informações da Tarefa</div>
                <div class="info-card-content">
                    <strong>ID:</strong> #<?php echo $tarefa['id_tarefa']; ?> | 
                    <strong>Criada em:</strong> <?php echo date('d/m/Y H:i', strtotime($tarefa['data_cadastro'])); ?>
                </div>
            </div>
            
            <form method="POST" action="">
                <input type="hidden" name="id_tarefa" value="<?php echo $tarefa['id_tarefa']; ?>">
                
                <div class="form-group">
                    <label class="form-label">
                        Usuário Responsável <span class="required">*</span>
                    </label>
                    <select name="id_usuario" class="form-select" required>
                        <option value="">Selecione um usuário</option>
                        <?php foreach($usuarios as $usuario): ?>
                            <option value="<?php echo $usuario['id_usuario']; ?>" 
                                <?php echo $usuario['id_usuario'] == $tarefa['id_usuario'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($usuario['nome']); ?> - <?php echo $usuario['setor']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        Descrição da Tarefa <span class="required">*</span>
                    </label>
                    <textarea name="descricao" 
                              class="form-textarea" 
                              placeholder="Descreva a tarefa em detalhes..."
                              required><?php echo htmlspecialchars($tarefa['descricao']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        Setor <span class="required">*</span>
                    </label>
                    <select name="setor" class="form-select" required>
                        <option value="">Selecione o setor</option>
                        <?php 
                        $setores = [
                            'TI' => '💻 Tecnologia da Informação',
                            'Comercial' => '💼 Comercial',
                            'Marketing' => '📢 Marketing',
                            'RH' => '👥 Recursos Humanos',
                            'Financeiro' => '💰 Financeiro'
                        ];
                        foreach($setores as $valor => $label): 
                        ?>
                            <option value="<?php echo $valor; ?>" 
                                <?php echo $valor == $tarefa['setor'] ? 'selected' : ''; ?>>
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        Prioridade <span class="required">*</span>
                    </label>
                    <div class="prioridade-group">
                        <div class="prioridade-option prioridade-baixa">
                            <input type="radio" 
                                   name="prioridade" 
                                   value="baixa" 
                                   id="baixa"
                                   <?php echo $tarefa['prioridade'] == 'baixa' ? 'checked' : ''; ?>>
                            <label for="baixa">
                                <span class="prioridade-icon">🟢</span>
                                Baixa
                            </label>
                        </div>
                        
                        <div class="prioridade-option prioridade-media">
                            <input type="radio" 
                                   name="prioridade" 
                                   value="média" 
                                   id="media"
                                   <?php echo $tarefa['prioridade'] == 'média' ? 'checked' : ''; ?>>
                            <label for="media">
                                <span class="prioridade-icon">🟡</span>
                                Média
                            </label>
                        </div>
                        
                        <div class="prioridade-option prioridade-alta">
                            <input type="radio" 
                                   name="prioridade" 
                                   value="alta" 
                                   id="alta"
                                   <?php echo $tarefa['prioridade'] == 'alta' ? 'checked' : ''; ?>>
                            <label for="alta">
                                <span class="prioridade-icon">🔴</span>
                                Alta
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Status Atual</label>
                    <input type="text" 
                           value="<?php echo $status_atual['icon'] . ' ' . $status_atual['label']; ?>" 
                           class="form-input" 
                           disabled>
                    <small style="color: #999; display: block; margin-top: 0.25rem;">
                        Para alterar o status, use o quadro Kanban
                    </small>
                </div>
                
                <div class="btn-actions">
                    <button type="submit" class="btn btn-salvar">
                        💾 Salvar Alterações
                    </button>
                    <a href="gerenciar.php" class="btn btn-cancelar">
                        ❌ Cancelar
                    </a>
                </div>
            </form>
            
            <a href="gerenciar.php" class="btn-voltar">← Voltar para o quadro Kanban</a>
        </div>
    </div>
</body>
</html>