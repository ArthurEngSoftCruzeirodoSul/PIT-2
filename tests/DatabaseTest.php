<?php

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testBancoDeDadosConectado()
    {
        $dsn = 'mysql:host=127.0.0.1;dbname=database;charset=utf8mb4'; // Substitua pelo seu banco de dados
        $dbUser = 'root';
        $dbPassword = '';

        try {
            $db = new PDO($dsn, $dbUser, $dbPassword);
            $this->assertTrue(true); // Teste passa se a conexão for bem-sucedida
        } catch (PDOException $e) {
            $this->fail("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }
}
// Cria a conexão com o banco de dados
$db = new PDO('mysql:host=127.0.0.1;dbname=database;charset=utf8mb4', 'root', '');

// Testa a conexão
try {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Define o modo de erro
    echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    echo "Falha na conexão: " . $e->getMessage();
}
