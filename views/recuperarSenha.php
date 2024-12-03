<?php
// Inclui os arquivos de configuração e rotas para o funcionamento do sistema.
require_once __DIR__ . '/../config.php'; // Carrega as configurações do banco de dados, entre outros.
require_once __DIR__ . '/../routes.php'; // Carrega as rotas definidas no sistema.
?>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-red-600 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
        <h1 class="text-center text-3xl font-bold text-red-600 mb-6">Recuperar Senha</h1>
        <form class="space-y-6" id="recover-form">
            <div>
                <label for="recover-email" class="block text-left text-gray-700 mb-1">E-mail:</label>
                <input type="email" id="recover-email" placeholder="Digite seu e-mail"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-400"
                    required>
            </div>
            <div class="flex justify-between items-center mt-4">
                <a href="<?= BASE_URL ?>login.php" class="text-red-600 text-lg">Voltar ao Login</a>
                <button type="submit"
                    class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700">Recuperar</button>
            </div>
            <!-- Mensagem de confirmação oculta inicialmente -->
            <p id="success-message" class="text-red-600 text-center mt-6 hidden">Um e-mail de recuperação foi enviado
                com sucesso!</p>
            <p id="error-message" class="text-red-500 text-sm mt-2"></p>

        </form>
    </div>

    <!-- Script para exibir a mensagem de confirmação -->
    <script>
        document.getElementById('recover-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Impede o envio do formulário para simular o feedback visual

    const errorMessage = document.getElementById("error-message");
    let email = document.getElementById("recover-email").value;

    if (!email) {
        errorMessage.textContent = "O campo email está em branco.";
        return;
    }
    
    const emailPattern = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        errorMessage.textContent = "Por favor, insira um e-mail válido.";
        return;
    }

    // Envia os dados para o backend via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/controllers/UserController.php?action=recoverPassword', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Exibe a mensagem de sucesso
                document.getElementById('success-message').classList.remove('hidden');
                document.getElementById('error-message').classList.add('hidden');
            } else {
                document.getElementById('error-message').textContent = response.message;
                document.getElementById('error-message').classList.remove('hidden');
            }

            // Limpa o campo de e-mail após o feedback
            document.getElementById('recover-form').reset();
        }
    };

    // Envia os dados do formulário
    xhr.send('email=' + encodeURIComponent(email));
});

    </script>
</body>

</html>