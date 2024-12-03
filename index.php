<?php
// Inicia uma sessão para armazenar informações do usuário
session_start();

// Inclui os arquivos de configuração e os controladores necessários
require_once __DIR__ . '/config.php';  // Configuração de banco de dados
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/models/UserModel.php';
require_once __DIR__ . '/controllers/CartaoController.php';
require_once __DIR__ . '/models/CartaoModel.php';
require_once __DIR__ . '/routes.php';

try {
    // Cria a conexão com o banco de dados usando PDO
    $db = new PDO(DB_DSN, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Termina a execução caso haja erro na conexão
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Instancia os modelos e controladores necessários
$userModel = new UserModel($db);
$userController = new UserController($db, $userModel);
$cartaoModel = new CartaoModel($db);
$cartaoController = new CartaoController($db, $cartaoModel);

// Determina a ação atual com base no parâmetro 'action' da URL (padrão: 'login')
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Processa requisições POST (formulários de registro, login, perfil, e cartão)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($action) {
        case 'register':
            // Processa o cadastro de um novo usuário
            $result = $userController->register($_POST);
            $_SESSION['message'] = $result['message'];
            if (headers_sent()) {
                die('Erro: Cabeçalhos já foram enviados.');
            }

            if ($result['success']) {
                header('Location: ' . BASE_URL . 'views/login.php');
                exit;
            } else {
                require __DIR__ . '/views/cadastro.php';
                exit;
            }
        case 'login':
            $result = $userController->login($_POST);
            if (isset($result['error'])) {
                $_SESSION['message'] = $result['error']; // Armazena a mensagem de erro na sessão
                header('Location: ' . BASE_URL . 'views/login.php'); // Redireciona de volta para o login
                exit;
            }
            header('Location: ' . BASE_URL . 'views/vitrineCupcakes.php'); // Redireciona para a página principal após sucesso
            exit;
        case 'updateProfile':
            // Verifica se o usuário está autenticado
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['message'] = 'Você precisa estar logado para atualizar seu perfil.';
                header('Location: ' . BASE_URL . 'views/login.php');
                exit;
            }

            // Processa a atualização do perfil
            $result = $userController->updateProfile($_POST);

            // Armazena a mensagem de sucesso ou erro
            $_SESSION['message'] = $result['message'];

            // Redireciona de volta à página de perfil ou para uma página de erro
            if ($result['success']) {
                header('Location: ' . BASE_URL . 'views/perfil.php');
                exit;
            } else {
                require __DIR__ . '/views/perfil.php';
                exit;
            }
        case 'addCartao':
            $usuario_id = $_SESSION['user_id'] ?? null;

            if (!$usuario_id) {
                $_SESSION['message'] = 'Usuário não autenticado.';
                header('Location: ' . BASE_URL . 'views/login.php');
                exit;
            }

            $result = $cartaoController->saveCartao($_POST, $usuario_id);
            $_SESSION['message'] = $result['message'];
            header('Location: ' . BASE_URL . 'views/vitrineCupcakes.php');
            exit;
        default:
            $_SESSION['message'] = 'Ação inválida.';
            header('Location: ' . BASE_URL . 'views/login.php');
            exit;
    }
}

// Processa as requisições GET e carrega as páginas correspondentes
switch ($action) {
    case 'register':
        require __DIR__ . '/views/cadastro.php';
        break;
    case 'login':
        require __DIR__ . '/views/login.php';
        break;
    case 'perfil':
        require __DIR__ . '/views/perfil.php';
        break;
    default:
        require __DIR__ . '/views/login.php';
        break;
}
?>