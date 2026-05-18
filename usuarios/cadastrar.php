<!-- Thomas Devb -->
<?php
require_once '../config/database.php';

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $setor = $_POST['setor'];
    
    // Validações
    if (empty($nome) || empty($email) || empty($setor)) {
        $mensagem = '⚠️ Todos os campos são obrigatórios!';
        $tipo_mensagem = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = '⚠️ Formato de e-mail inválido!';
        $tipo_mensagem = 'error';
    } else {
        try {
            $sql = "INSERT INTO usuarios (nome, email, setor) VALUES (:nome, :email, :setor)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':setor' => $setor
            ]);
            
            $mensagem = '✅ Usuário cadastrado com sucesso!';
            $tipo_mensagem = 'success';
            
            // Limpar campos após sucesso
            $_POST = array();
            
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $mensagem = '❌ Este e-mail já está cadastrado no sistema!';
                $tipo_mensagem = 'error';
            } else {
                $mensagem = '❌ Erro ao cadastrar usuário: ' . $e->getMessage();
                $tipo_mensagem = 'error';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário - TaskSync</title>
    <link rel="stylesheet" href="../css/cadastro-usuario.css">
</head>
<body>
    <div class="cadastro-container">
        <div class="cadastro-header">
            <div class="icon">👤</div>
            <h1>Novo Usuário</h1>
            <p>Cadastre um colaborador no sistema</p>
        </div>
        
        <div class="cadastro-body">
            <?php if($mensagem): ?>
                <div class="alert alert-<?php echo $tipo_mensagem; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">
                        Nome Completo <span class="required">*</span>
                    </label>
                    <input type="text" 
                           name="nome" 
                           class="form-input" 
                           placeholder="Digite o nome completo"
                           value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>"
                           required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        E-mail <span class="required">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           class="form-input" 
                           placeholder="email@exemplo.com"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                           required>
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
                
                <button type="submit" class="btn-cadastrar">
                    💾 Cadastrar Usuário
                </button>
            </form>
            
            <a href="../index.php" class="btn-voltar">← Voltar para o início</a>
        </div>
    </div>
</body>
</html>