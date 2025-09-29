<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.html");
    exit;
}

if ($_SESSION['usuario_nivel'] != 'admin') {
    header("Location: ../site.php");
}

$nome_admin = htmlspecialchars($_SESSION['usuario_nome']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrador</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body class="site-body">
        <header class="nav-bar">
        <div class="info-usu">
            <div class="det-usu">
                <span class="nome-usu">Painel Admin</span>
            </div>
            <div>
                <a href="../logout.php" class="btn-sair">Sair</a>
            </div>
        </div>
    </header>
    <main class="cont-principal">
        <div class="apresentacao" style="align-items: flex-start;">
            <div class="txt-apresentacao">
                <h1>Página do <span class="destaque">Administrador</span></h1>
                <p>Esta é a sua central de controle. A partir daqui, pode gerir os utilizadores, ver relatórios e configurar as definições do sistema.</p>
            </div>
        </div>
        <div class="cards-informativos">
            <h2>Ferramentas de Gestão</h2>
            <div class="cards-container">
                <div class="card-info">
                    <h3>Criar Novo Utilizador</h3>
                    <p>Registar uma nova conta de utilizador ou de administrador no sistema.</p>
                    <a href="#" class="btn-principal" class="btn-principal" style="margin-top: 15px;">Acessar</a>
                </div>
                <div class="card-info">
                    <h3>Gerir Utilizadores</h3>
                    <p>Ver a lista completa de utilizadores, editar as suas permissões, redefinir senhas ou apagar contas.</p>
                    <a href="gerenciar_usuarios.php" class="btn-principal" class="btn-principal" style="margin-top: 15px;">Acessar</a>
                </div>
                 <div class="card-info">
                    <h3>Relatórios de Atividade</h3>
                    <p>Analisar os logs de acesso, registos recentes e outras métricas importantes do sistema.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>