<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body class="auth-body">
    <div class="login">
        <h1>Recuperar Acesso</h1>
        <form class="inputs" action="verificar_palavra.php" method="POST">
            <label>E-mail</label>
            <input type="email" name="email" placeholder="Digite seu e-mail" required>

            <label>Palavra-Chave</label>
            <input type="text" name="palavra_chave" placeholder="Sua palavra-chave" required>

            <button type="submit">Verificar</button>
        </form>
        <div class="links">
            <a href="../index.html">Voltar para o Login</a>
        </div>
    </div>
</body>
</html>