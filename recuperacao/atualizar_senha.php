<?php
    session_start();
    require_once '../conexao/conexao.php';

    if (!isset($_SESSION['id_usuario_redefinir'])) {
        header("Location: ../index.html");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $senha = trim($_POST['senha']);
        $confirmar_senha = trim($_POST['confirmar_senha']);
        $id_usuario = $_SESSION['id_usuario_redefinir'];
        
        if ($senha != $confirmar_senha) {
            die("As senhas não conferem. <a href='redefinir_senha.php'>Tente novamente</a>.");
        }

        try {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql_att = "UPDATE usuarios SET usu_senha = :senha WHERE usu_id = :id";
            $stmt_att = $pdo->prepare($sql_att);
            $stmt_att->bindParam(':senha', $senha_hash, PDO::PARAM_STR);
            $stmt_att->bindParam(':id', $id_usuario, PDO::PARAM_INT);

            if ($stmt_att->execute()) {
                unset($_SESSION['id_usuario_redefinir']);
                echo "Senha atualizada com sucesso! <a href='../index.html'>Fazer Login</a>";
            } else {
                echo "Erro ao atualizar a senha.";
            }
        } catch (PDOException $e) {
            die("erro no banco de dados: " . $e->getMessage());
        }
    }
?>