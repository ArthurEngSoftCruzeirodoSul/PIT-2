<?php 
// Inclui os arquivos de configuração e rotas para o funcionamento do sistema.
require_once __DIR__ . '/../config.php'; // Carrega as configurações do banco de dados, entre outros.
require_once __DIR__ . '/../routes.php'; // Carrega as rotas definidas no sistema.

session_start(); // Inicializando a sessão para verificar se o usuário está logado.

// Verifica se o usuário está logado antes de acessar a página de perfil
if (!isset($_SESSION['user_id'])) {
    // Redireciona para o login caso o usuário não esteja logado
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-red-600 flex items-center justify-center min-h-screen">
    <div class="bg-white text-gray-900 w-full max-w-lg p-8 rounded-lg shadow-xl max-w-sm w-full">
        <h1 class="text-4xl font-bold mb-8 text-red-600 text-center">Perfil</h1>
        
        <form action="userController.php?action=updateProfile" method="POST">
            <input type="text" id="profile-name" name="name" placeholder="Nome" class="mb-4 p-2 rounded-md border border-gray-300 text-black focus:outline-none focus:ring-2 focus:ring-red-400 w-full" value="<?= $_SESSION['user_name'] ?>" />
            <input type="email" id="profile-email" name="email" placeholder="E-mail" class="mb-4 p-2 rounded-md border border-gray-300 text-black focus:outline-none focus:ring-2 focus:ring-red-400 w-full" value="<?= $_SESSION['user_email'] ?>" />
            <input type="text" id="profile-address" name="address" placeholder="Endereço" class="mb-4 p-2 rounded-md border border-gray-300 text-black focus:outline-none focus:ring-2 focus:ring-red-400 w-full" />
            
            <a href="<?= BASE_URL ?>views/formaPagamento.php" class="block text-red-600 mb-6 text-center">Editar Forma de pagamento</a>

            <div class="flex flex-col items-center justify-center w-full space-y-4">
                <div class="w-full flex justify-between items-center">
                    <a href="<?= BASE_URL ?>views/vitrineCupcakes.php" class="text-red-600">Voltar à vitrine</a>
                    <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md shadow-md focus:outline-none">Salvar</button>
                </div>
            </div>
        </form>

        <a href="<?= BASE_URL ?>/views/login.php" class="mt-8 text-red-600 text-center">Logout</a>
    </div>
</body>
</html>
