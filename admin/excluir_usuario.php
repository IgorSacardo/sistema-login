<?php
session_start();
require_once '../conexao/conexao.php';

header('Content-Type: application/json');

if ($_SESSION['usuario_nivel'] != 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido.']);
    exit;
}

$dados = json_decode(file_get_contents('php://input'), true);
$id_para_excluir = $dados['id'] ?? null;

if (empty($id_para_excluir)) {
    echo json_encode(['status' => 'error', 'message' => 'ID não fornecido.']);
    exit;
}

if ($id_para_excluir == $_SESSION['usuario_id']) {
    echo json_encode(['status' => 'error', 'message' => 'Você não pode excluir seu próprio usuário.']);
    exit;
}

try {
    $sql = "DELETE FROM usuarios WHERE usu_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_para_excluir, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Usuário excluído com sucesso.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Falha ao excluir o usuário.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>