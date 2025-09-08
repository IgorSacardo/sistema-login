<?php 
    session_start();
    require_once "../conexao/conexao.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);
        $confirmar = trim($_POST['confirmar_senha']);
        $nivel = 'usuario';
        // $nivel = $_POST['nivel'];
        
        if ($senha != $confirmar) {
            die("As senhas não conferem! <a href='cadastro.html'>Tente novamente</a>.");
        }

        try {
            
            $verificar_email_existe = "SELECT usu_id FROM usuarios WHERE usu_email = :email";
            $stmt_verificar_email = $pdo->prepare($verificar_email_existe);
            $stmt_verificar_email->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_verificar_email->execute(); 
            
            if ($stmt_verificar_email->rowCount() > 0) {
                die("E-mail já está cadastrado! <a href='cadastro.html'>Tente outro</a>.");
            }

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $inserir_dados = "INSERT INTO usuarios (usu_nome, usu_email, usu_senha, usu_nivel_acesso) VALUES (:nome, :email, :senha, :nivel)";
                    
            $stmt_inserir_dados = $pdo->prepare($inserir_dados);
            $stmt_inserir_dados->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt_inserir_dados->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_inserir_dados->bindParam(':senha', $senha_hash, PDO::PARAM_STR);
            $stmt_inserir_dados->bindParam(':nivel', $nivel, PDO::PARAM_STR);
            
            if ($stmt_inserir_dados->execute()) {
                echo "Cadastro realizado com sucesso! <a href='../index.html'>Fazer login</a>";
            } else {
                echo "Erro ao cadastrar usuário.";
            }
        } catch (PDOException $e) {
            echo "Erro no banco de dados: ".$e->getMessage();
        }
    } else {
        header("Location: cadastro.html");
        exit;
    }
?>