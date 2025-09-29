<?php
session_start();
require_once '../conexao/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.html");
    exit;
}

if ($_SESSION['usuario_nivel'] != 'admin') {
    die("Acesso negado.");
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gerenciar_usuarios.php");
    exit;
}

$id_para_excluir = $_GET['id'];

if ($id_para_excluir == $_SESSION['usuario_id']) {
    die("Erro ao excluir usuário. <a href='gerenciar_usuarios.php'>Voltar</a>");
}

try {
    $sql = "DELETE FROM usuarios WHERE usu_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_para_excluir, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: gerenciar_usuarios.php");
        exit;
    } else {
        die("ERRo ao excluir usuário. <a href'gerenciar_usuarios.php'>Voltar</a>");
    }
} catch (PDOException $e) {
    die("Erro no banco de dados: " . $e->getMessage());
}

?>