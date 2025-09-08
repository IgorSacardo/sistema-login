<?php
    session_start();

    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.html");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="login">
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']);?>!</h1>
        <p>Seu nível de acesso é: <strong><?php echo htmlspecialchars($_SESSION['usuario_nivel']);?></strong></p>
        <br>
        <a class="links" href="logout.php">Sair</a>
    </div>
</body>
</html>