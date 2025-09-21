<?php
    session_start();
    require_once '../conexao/conexao.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $palavra_chave = trim($_POST['palavra_chave']);

        try {
            $sql = "SELECT usu_id, palavra_chave FROM usuarios WHERE usu_email = :email LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($palavra_chave, $usuario['palavra_chave'])) {
                $_SESSION['id_usuario_redefinir'] = $usuario['usu_id'];

                header("Location: redefinir_senha.php");
                exit;
            } else {
                die("Erro: E-mail ou palavra-chave incorrestos. <a href='esqueci_senha.php'>Tente novamente</a>.");
            }
        } catch (PDOException $e) {
            die("Erro no banco de dados: " . $e->getMessage());
        }
    }
?>