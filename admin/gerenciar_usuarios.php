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
        $sql = "SELECT usu_id, usu_nome, usu_email, usu_nivel_acesso FROM usuarios WHERE usu_nivel_acesso <> 'admin' ORDER BY usu_nome ASC";
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
    <link rel="stylesheet" href="../style/tab_usuarios.css">
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
                <p>Aqui você pode visualizar, editar e excluir os usuários cadastrados no sistema.</p>
                <a href="admin_pag.php" class="btn-principal">Voltar ao Painel</a>
            </div>
        </div>

        <div class="tabela-container">
            <table class="tabela-usuarios">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Nível</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['usu_id']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['usu_nome']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['usu_email']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['usu_nivel_acesso']); ?></td>
                                <td class="acoes">
                                    <a href="editar_usuario.php?id=<?php echo $usuario['usu_id']; ?>" class="btn-acao btn-editar">Editar</a>
                                    <a href="excluir_usuario.php?id=<?php echo $usuario['usu_id']; ?>" class="btn-acao btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nenhum usuário encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>

