<!-- Thomas Devb -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskSync - Sistema de Gerenciamento de Tarefas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo-container">
                <span class="logo-icon">📋</span>
                <div>
                    <h1>TaskSync</h1>
                    <p>Sistema de Gerenciamento de Tarefas</p>
                </div>
            </div>
        </header>
        
        <div class="dashboard-grid">
            <a href="usuarios/cadastrar.php" class="dashboard-card card-usuario">
                <div class="card-icon">👤</div>
                <h2>Cadastrar Usuário</h2>
                <p>Registre novos colaboradores no sistema para atribuir tarefas</p>
                <span class="card-action">Acessar →</span>
            </a>
            
            <a href="tarefas/cadastrar.php" class="dashboard-card card-tarefa">
                <div class="card-icon">📝</div>
                <h2>Cadastrar Tarefa</h2>
                <p>Crie novas tarefas e atribua aos membros da sua equipe</p>
                <span class="card-action">Acessar →</span>
            </a>
            
            <a href="tarefas/gerenciar.php" class="dashboard-card card-kanban">
                <div class="card-icon">📊</div>
                <h2>Gerenciar Tarefas</h2>
                <p>Visualize o quadro Kanban e gerencie o fluxo de trabalho</p>
                <span class="card-action">Acessar →</span>
            </a>
        </div>
        
        <footer class="footer">
            <p>© 2024 TaskSync Solutions - Todos os direitos reservados</p>
        </footer>
    </div>
</body>
</html>