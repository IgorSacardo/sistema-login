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
    <header class="nav-bar">
        <nav class="info-usu">
            <div class="det-usu">
                <span class="nome-usu">Olá, <?php echo $nome_usuario; ?>!</span>
                <span class="nivel-usu">Nível: <span style="color: #E7297E"><?php echo $nivel_usuario; ?></span></span>
            </div>
            <a href="logout.php" class="btn-sair">Sair</a>
        </nav>
    </header>
    <main class="cont-principal">
        <section class="apresentacao">
            <div class="txt-apresentacao">
                <h1>Bem-vindo ao MySystem</h1>
                <p>Este é o seu portal de acesso seguro. Desenvolvido como parte do projeto de
                    Programação e Web, este sistema demonstra a implementação de um fluxo completo
                    de autenticação de usuários, incluindo cadastro, login seguro com criptografia de
                    senhas e gerenciamento de sessões para controle de acesso.
                </p>
                <a href="#" class="btn-principal">Saiba Mais</a>
            </div>
            <div class="img-apresentacao">
                <img src="" alt="Ilustração de boas-vindas ao sistema">
            </div>
        </section>

        <section class="cards-informativos">
            <h2>Explore os Recursos</h2>
            <div class="card-info">
                <div class="cards-container">
                    <div class="card-info">
                        <h3>Segurança em Primeiro Lugar</h3>
                        <p>Utilizamos as práticas mais recentes de segurança, como `password_hash`, para garantir que suas informações estejam sempre protegidas.</p>
                    </div>
                    <div class="card-info">
                        <h3>Design Responsivo</h3>
                        <p>Acesse de qualquer dispositivo. Nossa interface se adapta perfeitamente a desktops, tablets e celulares para sua conveniência.</p>
                    </div>
                    <div class="card-info">
                        <h3>Gerenciamento de Acesso</h3>
                        <p>O sistema suporta diferentes níveis de permissão, permitindo funcionalidades exclusivas para administradores e usuários padrão.</p>
                    </div>

                    <?php if ($nivel_usuario === 'admin'): ?>
                        <div class="card-info admin-card">
                            <h3>Painel Administrativo</h3>
                            <p>Gerir utilizadores, ver logs e configurar o sistema</p>
                            <a href="admin/admin_pag.php" class="admin-link">Aceder ao Painel</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    </main>
</body>
</html>