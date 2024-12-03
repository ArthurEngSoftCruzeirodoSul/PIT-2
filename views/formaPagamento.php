<?php
// Inclui os arquivos de configuração e rotas para o funcionamento do sistema.
require_once __DIR__ . '/../config.php'; // Carrega as configurações do banco de dados, entre outros.
require_once __DIR__ . '/../routes.php'; // Carrega as rotas definidas no sistema.
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pagamento</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
</head>

<body class="bg-red-600 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
    <h1 class="text-center text-3xl font-bold text-red-600 mb-6">
      Pagamento
    </h1>

    <!-- Formulário de pagamento -->
    <form onsubmit="validateAndSaveCardData(event)">
      <div class="mb-4">
        <label for="cardNumber" class="text-gray-700 block text-left mb-1">Número do Cartão:</label>
        <input type="text" id="cardNumber" placeholder="Número do Cartão"
          class="w-full p-3 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400"
          maxlength="19" />
        <p id="cardNumberError" class="text-red-500 text-sm mt-1"></p>
      </div>
      <div class="mb-4">
        <label for="cardName" class="text-gray-700 block text-left mb-1">Nome no Cartão:</label>
        <input type="text" id="cardName" placeholder="Nome no Cartão"
          class="w-full p-3 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400" />
        <p id="cardNameError" class="text-red-500 text-sm mt-1"></p>
      </div>
      <div class="flex items-center justify-center mb-4">
        <label for="expiryDate" class="text-gray-700 mr-2">Validade:</label>
        <input type="text" id="expiryDate" placeholder="MM/AA"
          class="w-32 p-3 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400 mr-3"
          maxlength="5" oninput="formatExpiryDate(event)" />
        <!-- <i class="fas fa-calendar-alt text-gray-700 ml-2"></i> -->

        <label for="cvv" class="text-gray-700 block text-left mr-2">CVV:</label>
        <input type="text" id="cvv" placeholder="CVV"
          class="w-16 p-3 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400"
          maxlength="4" />
        <p id="cvvError" class="text-red-500 text-sm mt-1"></p>
      </div>

      <!-- Mensagem de erro geral -->
      <p id="generalError" class="text-red-500 text-sm mb-4"></p>

      <div class="flex text-sm items-center justify-between mt-6">
        <button class="text-red-600" type="button" onclick="editProfile()">
          Editar Perfil
        </button>
        <!-- Botão de salvar -->
        <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">
          Salvar
        </button>
      </div>
    </form>

    <!-- Link para apagar dados do cartão -->
    <div class="mt-4">
      <a href="javascript:void(0);" onclick="clearCardData()" class="text-red-600 text-sm hover:underline">
        Apagar dados do cartão
      </a>
    </div>

    <!-- Pop-up de mensagem -->
    <div id="messagePopup"
      class="fixed top-0 left-0 right-0 bg-gray-800 bg-opacity-75 text-white text-center p-4 hidden">
      <span id="messageContent"></span>
      <!-- <button onclick="closePopup()" class="text-white ml-4">Fechar</button> -->
    </div>
  </div>
  <script>
    function editProfile() {
        // Redireciona para o arquivo editarPerfil.php dentro da pasta views
        window.location.href = '/teste/views/editarPerfil.php';
    }
</script>
</body>
</html>