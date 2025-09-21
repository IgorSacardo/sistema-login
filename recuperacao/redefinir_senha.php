<?php
    session_start();
    
    if (!isset($_SESSION['id_usuario_redefinir'])) {
        header("Location: esqueci_senha.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body class="auth-body">
    <div class="cadastro">
        <h1>Crie sua nova senha</h1>
        <form class="inputs" action="atualizar_senha.php" method="POST">
            <label>Nova Senha</label>
            <input type="password" name="senha" placeholder="Digite a nova senha" required>

            <label>Confirme a Nova Senha</label>
            <input type="password" name="confirmar_senha" placeholder="Digite novamente a senha" required>

            <button type="submit">Atualizar Senha</button>
        </form>
    </div>
</body>
</html>