<?php

// Definir a URL base, se ainda não estiver definida
if (!defined('BASE_URL')) {
    define('BASE_URL', '/views/');
}

// Definir as configurações do banco de dados
define('DB_HOST', ''); // Inserir Endereço do servidor MySQL
define('DB_NAME', 'database'); // Nome do banco de dados
define('DB_USER', 'root'); // Usuário do banco de dados
define('DB_PASS', ''); // Inserir Senha do banco de dados
define('DB_CHARSET', 'utf8mb4'); // Defina o charset

// Definir o DSN para a conexão PDO
define('DB_DSN', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET);
