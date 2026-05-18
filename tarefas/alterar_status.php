<!-- Thomas Devb -->
<?php
// Este arquivo está em: C:\xampp\htdocs\php_avaliativo\tarefas\alterar_status.php
require_once '../config/database.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    
    // Validar status permitidos
    $status_permitidos = ['a fazer', 'fazendo', 'concluído'];
    if (!in_array($status, $status_permitidos)) {
        header('Location: gerenciar.php?error=Status inválido');
        exit;
    }
    
    try {
        $sql = "UPDATE tarefas SET status = :status WHERE id_tarefa = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':status' => $status, ':id' => $id]);
        
        header('Location: gerenciar.php?msg=Status alterado com sucesso!');
    } catch(PDOException $e) {
        header('Location: gerenciar.php?error=Erro ao alterar status: ' . $e->getMessage());
    }
} else {
    header('Location: gerenciar.php?error=Parâmetros inválidos');
}
exit;
?>