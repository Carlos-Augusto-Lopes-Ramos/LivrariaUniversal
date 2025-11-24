<?php

$username = 'root';
$password = '12345';

try {
    $con = new PDO('mysql:host=localhost:3306;dbname=bookstore', $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
}

?>