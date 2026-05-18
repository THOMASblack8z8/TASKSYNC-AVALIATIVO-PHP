<!-- Thomas Devb -->
<?php
// Este arquivo está em: C:\xampp\htdocs\php_avaliativo\tarefas\excluir.php
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $sql = "DELETE FROM tarefas WHERE id_tarefa = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        header('Location: gerenciar.php?msg=Tarefa excluída com sucesso!');
    } catch(PDOException $e) {
        header('Location: gerenciar.php?error=Erro ao excluir tarefa: ' . $e->getMessage());
    }
} else {
    header('Location: gerenciar.php?error=ID não informado');
}
exit;
?>