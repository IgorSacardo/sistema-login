<?php
    session_start();
    require_once 'conexao/conexao.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);

        if (empty($email) || empty($senha)) {
            die("Erro: Preencha todos os campos! <a href='index.html'>Tentar novamente</a>");
        }


        try {
            $sql = "SELECT usu_id, usu_nome, usu_email, usu_senha, usu_nivel_acesso FROM usuarios WHERE usu_email = :email LIMIT 1";

            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($senha, $usuario['usu_senha'])) {
                $_SESSION['usuario_id'] = $usuario['usu_id'];
                $_SESSION['usuario_nome'] = $usuario['usu_nome'];
                $_SESSION['usuario_email'] = $usuario['usu_email'];
                $_SESSION['usuario_nivel'] = $usuario['usu_nivel_acesso'];

                header("Location: site.php");
                exit;
            } else {
                echo "E-mail ou senha inválidos! <a href='index.html'>Tentar novamente</a>";
            }
        } catch (PDOException $e) {
            die("Erro no banco de dados: " . $e->getMessage());
        }
    } else {
        header("Location: index.html");
        exit;
    }
?>