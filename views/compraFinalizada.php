<?php
// Inclui os arquivos de configuração e rotas para o funcionamento do sistema.
require_once __DIR__ . '/../config.php'; // Carrega as configurações do banco de dados, entre outros.
require_once __DIR__ . '/../routes.php'; // Carrega as rotas definidas no sistema.
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finalizar Compra</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm text-center">
    <h1 class="text-2xl font-bold mb-6">Compra Finalizada com Sucesso!</h1>
    <p class="mb-4">Obrigado por sua compra! Seu pedido foi processado e em breve será enviado.</p>
    <button onclick="goToVitrine()" class="bg-red-600 text-white px-4 py-2 rounded-md mt-4">Voltar à Vitrine</button>
  </div>

  <script>
    // Função para finalizar a compra, limpando o carrinho do localStorage
    function finalizePurchase() {
      // Remove o carrinho do localStorage
      localStorage.removeItem('cart');
    }

    // Função para redirecionar o usuário de volta à vitrine
    function goToVitrine() {
      window.location.href = 'http://lojacupcakes.freesite.online/views/vitrineCupcakes.php';
    }

    // Chama a função de finalização assim que a página carrega
    document.addEventListener('DOMContentLoaded', finalizePurchase);
  </script>
</body>

</html>