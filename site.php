<?php
    session_start();

    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.html");
        exit;
    }

    $nome_usuario = htmlspecialchars($_SESSION['usuario_nome']);
    $nivel_usuario = htmlspecialchars($_SESSION['usuario_nivel']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header class="cabeçalho">
        <nav class="usu-info">
            <div class="usu_detalhes">
                <span class="usu-nome">Olá, <?php echo $nome_usuario; ?>!</span>
                <span class="usu-nivel">Nível: <?php echo $nivel_usuario; ?></span>
            </div>
            <a href="logout.php" class="btn-sair">Sair</a>
        </nav>
    </header>
    <main class="conteudo">
        <div class="mensagem">
            <h1>Bem-vindo ao seu Painel de Controle</h1>
            <p></p>
        </div>
        </section>
    </main>
</body>
</html>