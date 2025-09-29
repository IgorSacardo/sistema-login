<?php
    session_start();
    require_once '../conexao/conexao.php';

    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../index.html");
        exit;
    }

    if ($_SESSION['usuario_nivel'] != 'admin') {
        header("Location: ../site.php");
        exit;
    }

    $nome_admin = htmlspecialchars($_SESSION['usuario_nome']);

    try {
        $sql = "SELECT usu_id, usu_nome, usu_email, usu_nivel_acesso FROM usuarios ORDER BY usu_nome ASC";
        // São selecionados somente informações necessárias, seguindo a LGPD.
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Todos os usuários são alocados em um Array.
    } catch (PDOException $e) {
        die("Erro ao buscar usuários: ".$e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body class="site-body">
    <header class="nav-bar">
        <div class="info-usu">
            <div class="det-usu">
                <span class="nome-usu">Bem-vindo, Administrador</span>
            </div>
            <div>
                <a href="../logout.php" class="btn-sair">Sair</a>
            </div>
        </div>
    </header>

    <main class="cont-principal">
        <div class="apresentacao" style="align-items: flex-start;">
            <div class="txt-apresentacao">
                <h1>Gerenciamento de <span class="destaque">Usuários</span></h1>
                <p>Aqui você pode visualizar todos os usuários cadastrados no sistema.</p>
                <a href="admin_pag.php" class="btn-principal">Voltar ao Painel</a>
            </div>
        </div>

    </main>
</body>
</html>