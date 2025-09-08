<?php
$servername="localhost";
$port="3306";
$username="root";
$password="";
$dbname="fafram";
try {
    $pdo = new PDO(
        "mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " .$e -> getMessage(). "<br>";
}
?>
