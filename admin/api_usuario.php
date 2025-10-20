<?php
session_start();
header('Content-Type: application/json');
require_once '../conexao/conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] != 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(!isset($_GET['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'ID do usuário não fornecido']);
        exit;
    }

    $id = $_GET['id'];

    try {
        $sql = "SELECT usu_id, usu_nome, usu_email, usu_nivel_acesso FROM usuarios WHERE usu_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            echo json_encode(['status' => 'success', 'data' => $usuario]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Usuário não encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);

    if (empty($dados['id']) || empty($dados['nome']) || empty($dados['email']) || empty($dados['nivel'])) {
        echo json_encode(['status' => 'error', 'message' => 'Todos os campos são obrigatórios.']);
        exit;
    }

    try {
        $sql = "UPDATE usuarios SET usu_nome = :nome, usu_email = :email, usu_nivel_acesso = :nivel WHERE usu_id = :id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $dados['email'], PDO::PARAM_STR);
        $stmt->bindParam(':nivel', $dados['nivel'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $dados['id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
             echo json_encode(['status' => 'success', 'message' => 'Usuário atualizado com sucesso!']);
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar o usuário.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
    }
}
?>