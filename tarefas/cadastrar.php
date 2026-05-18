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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $sql = "INSERT INTO tarefas (id_usuario, descricao, setor, prioridade) 
                    VALUES (:id_usuario, :descricao, :setor, :prioridade)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':descricao' => $descricao,
                ':setor' => $setor,
                ':prioridade' => $prioridade
            ]);
            
            $mensagem = '✅ Tarefa cadastrada com sucesso!';
            $tipo_mensagem = 'success';
            
            // Limpar campos após sucesso
            $_POST = array();
            
        } catch(PDOException $e) {
            $mensagem = '❌ Erro ao cadastrar tarefa: ' . $e->getMessage();
            $tipo_mensagem = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Tarefa - TaskSync</title>
    <link rel="stylesheet" href="../css/cadastro-tarefa.css">
</head>
<body>
    <div class="cadastro-container">
        <div class="cadastro-header">
            <div class="icon">📝</div>
            <h1>Nova Tarefa</h1>
            <p>Crie uma tarefa e atribua a um responsável</p>
        </div>
        
        <div class="cadastro-body">
            <?php if($mensagem): ?>
                <div class="alert alert-<?php echo $tipo_mensagem; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>
            
            <?php if(count($usuarios) > 0): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">
                        Usuário Responsável <span class="required">*</span>
                    </label>
                    <select name="id_usuario" class="form-select" required>
                        <option value="">Selecione um usuário</option>
                        <?php foreach($usuarios as $usuario): ?>
                            <option value="<?php echo $usuario['id_usuario']; ?>"
                                <?php echo (isset($_POST['id_usuario']) && $_POST['id_usuario'] == $usuario['id_usuario']) ? 'selected' : ''; ?>>
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
                              required><?php echo isset($_POST['descricao']) ? htmlspecialchars($_POST['descricao']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        Setor <span class="required">*</span>
                    </label>
                    <select name="setor" class="form-select" required>
                        <option value="">Selecione o setor</option>
                        <option value="TI" <?php echo (isset($_POST['setor']) && $_POST['setor'] == 'TI') ? 'selected' : ''; ?>>
                            💻 Tecnologia da Informação
                        </option>
                        <option value="Comercial" <?php echo (isset($_POST['setor']) && $_POST['setor'] == 'Comercial') ? 'selected' : ''; ?>>
                            💼 Comercial
                        </option>
                        <option value="Marketing" <?php echo (isset($_POST['setor']) && $_POST['setor'] == 'Marketing') ? 'selected' : ''; ?>>
                            📢 Marketing
                        </option>
                        <option value="RH" <?php echo (isset($_POST['setor']) && $_POST['setor'] == 'RH') ? 'selected' : ''; ?>>
                            👥 Recursos Humanos
                        </option>
                        <option value="Financeiro" <?php echo (isset($_POST['setor']) && $_POST['setor'] == 'Financeiro') ? 'selected' : ''; ?>>
                            💰 Financeiro
                        </option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        Prioridade <span class="required">*</span>
                    </label>
                    <div class="prioridade-group">
                        <div class="prioridade-option prioridade-baixa">
                            <input type="radio" name="prioridade" value="baixa" id="baixa"
                                <?php echo (isset($_POST['prioridade']) && $_POST['prioridade'] == 'baixa') ? 'checked' : ''; ?>>
                            <label for="baixa">
                                <span class="prioridade-icon">🟢</span>
                                Baixa
                            </label>
                        </div>
                        
                        <div class="prioridade-option prioridade-media">
                            <input type="radio" name="prioridade" value="média" id="media"
                                <?php echo (isset($_POST['prioridade']) && $_POST['prioridade'] == 'média') ? 'checked' : ''; ?>>
                            <label for="media">
                                <span class="prioridade-icon">🟡</span>
                                Média
                            </label>
                        </div>
                        
                        <div class="prioridade-option prioridade-alta">
                            <input type="radio" name="prioridade" value="alta" id="alta"
                                <?php echo (isset($_POST['prioridade']) && $_POST['prioridade'] == 'alta') ? 'checked' : ''; ?>>
                            <label for="alta">
                                <span class="prioridade-icon">🔴</span>
                                Alta
                            </label>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-cadastrar">
                    💾 Cadastrar Tarefa
                </button>
            </form>
            <?php else: ?>
                <div class="alert alert-warning">
                    ⚠️ Não há usuários cadastrados! 
                    Cadastre um usuário primeiro para criar tarefas.
                </div>
                <a href="../usuarios/cadastrar.php" class="btn-cadastrar" style="display: block; text-align: center; text-decoration: none; margin-top: 1rem;">
                    👤 Ir para Cadastro de Usuário
                </a>
            <?php endif; ?>
            
            <a href="../index.php" class="btn-voltar">← Voltar para o início</a>
        </div>
    </div>
</body>
</html>