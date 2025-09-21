<?php 
    session_start();
    require_once "../conexao/conexao.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);
        $confirmar = trim($_POST['confirmar_senha']);
        $palavra_chave = trim($_POST['palavra_chave']);
        $nivel = 'usuario';
        // $nivel = $_POST['nivel'];
        
        if ($senha != $confirmar) {
            die("As senhas não conferem! <a href='cadastro.html'>Tente novamente</a>.");
        }

        if (strtolower($nome) === 'admin') {
            die("Erro: Este nome de usuário é reservado. <a href='cadastro.html'>Tente outro</a>.");
        }

        try {
            
            $verificar_usuario_existe = "SELECT usu_id FROM usuarios WHERE usu_nome = :nome";
            $stmt_verificar_nome = $pdo->prepare($verificar_usuario_existe);
            $stmt_verificar_nome->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt_verificar_nome->execute();

            if ($stmt_verificar_nome->rowCount() > 0) {
                die("Este nome de usuário já está cadastrado! <a href='cadastro.html'>Tente outro</a>.");
            }

            $verificar_email_existe = "SELECT usu_id FROM usuarios WHERE usu_email = :email";
            $stmt_verificar_email = $pdo->prepare($verificar_email_existe);
            $stmt_verificar_email->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_verificar_email->execute(); 

            if ($stmt_verificar_email->rowCount() > 0) {
                die("E-mail já está cadastrado! <a href='cadastro.html'>Tente outro</a>.");
            }

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $palavra_chave_hash = password_hash($palavra_chave, PASSWORD_DEFAULT);

            $inserir_dados = "INSERT INTO usuarios (usu_nome, usu_email, usu_senha, palavra_chave, usu_nivel_acesso) VALUES (:nome, :email, :senha, :palavra_chave, :nivel)";
                    
            $stmt_inserir_dados = $pdo->prepare($inserir_dados);
            $stmt_inserir_dados->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt_inserir_dados->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_inserir_dados->bindParam(':senha', $senha_hash, PDO::PARAM_STR);
            $stmt_inserir_dados->bindParam(':palavra_chave', $palavra_chave_hash, PDO::PARAM_STR);
            $stmt_inserir_dados->bindParam(':nivel', $nivel, PDO::PARAM_STR);
            
            if ($stmt_inserir_dados->execute()) {
                
                $novo_id_usuario = $pdo->lastInsertId();
                
                $_SESSION['usuario_id'] = $novo_id_usuario;
                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['usuario_email'] = $email;
                $_SESSION['usuario_nivel'] = $nivel;

                header("Location: ../site.php");
                exit;
            } else {
                die("Ocorreu um erro ao realizar o cadastro. <a href='cadastro.html'>Tente novamente</a>.");
            }
        } catch (PDOException $e) {
            echo "Erro no banco de dados: ".$e->getMessage();
        }
    } else {
        header("Location: cadastro.html");
        exit;
    }
?>