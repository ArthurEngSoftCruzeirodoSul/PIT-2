<?php
// Inclui os arquivos de configuração e rotas para o funcionamento do sistema.
require_once __DIR__ . '/../config.php'; // Carrega as configurações do banco de dados, entre outros.
require_once __DIR__ . '/../routes.php'; // Carrega as rotas definidas no sistema.
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <!-- Definição do charset e da meta tag para responsividade -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Título da página que será exibido na aba do navegador -->
  <title>Cadastro</title>

  <!-- Inclusão do Tailwind CSS via CDN para estilizar os elementos da página -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-red-600 flex items-center justify-center min-h-screen">
  <!-- Container principal da página, com fundo branco, padding e bordas arredondadas -->
  <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">

    <!-- Cabeçalho da página com título centralizado -->
    <h1 class="text-center text-3xl font-bold text-red-600 mb-6">Cadastro</h1>

    <!-- Verifica se há alguma mensagem armazenada na sessão e exibe com a cor adequada -->
    <?php if (isset($_SESSION['message'])): ?>
      <!-- Se a mensagem de sucesso contém a palavra "sucesso", aplica a classe de texto azul, caso contrário, vermelha -->
      <div class="<?= (strpos($_SESSION['message'], 'sucesso') !== false) ? 'text-blue-600' : 'text-red-600' ?>">
        <!-- Exibe a mensagem com segurança contra XSS -->
        <?= htmlspecialchars($_SESSION['message']); ?>
      </div>
      <!-- Após exibir a mensagem, limpa a variável de sessão para não exibir novamente -->
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Formulário de cadastro, com método POST, enviando os dados para 'index.php?action=register' -->
    <form method="POST" action="/../teste/index.php?action=register" class="space-y-6">

      <!-- Campo para o nome do usuário -->
      <div>
        <label for="register-name" class="block text-left text-gray-700 mb-1">Nome:</label>
        <input type="text" id="register-name" name="name" placeholder="Nome"
          class="w-full px-4 py-2 border border-gray-300 rounded-md" required />
      </div>

      <!-- Campo para o e-mail do usuário -->
      <div>
        <label for="register-email" class="block text-left text-gray-700 mb-1">E-mail:</label>
        <input type="email" id="register-email" name="email" placeholder="E-mail"
          class="w-full px-4 py-2 border border-gray-300 rounded-md" required />
      </div>

      <!-- Campo para o endereço do usuário (opcional) -->
      <div>
        <label for="register-address" class="block text-left text-gray-700 mb-1">Endereço:</label>
        <input type="text" id="register-address" name="address" placeholder="Endereço"
          class="w-full px-4 py-2 border border-gray-300 rounded-md" />
      </div>

      <!-- Campo para a senha do usuário -->
      <div>
        <label for="register-password" class="block text-left text-gray-700 mb-1">Senha:</label>
        <input type="password" id="register-password" name="password" placeholder="Senha"
          class="w-full px-4 py-2 border border-gray-300 rounded-md" required />
      </div>

      <!-- Campo para confirmar a senha do usuário -->
      <div>
        <label for="confirm-password" class="block text-left text-gray-700 mb-1">Confirmar Senha:</label>
        <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirmar Senha"
          class="w-full px-4 py-2 border border-gray-300 rounded-md" required />
      </div>

      <!-- Área para mensagens de erro (como senhas não coincidentes), caso necessário -->
      <p id="error-message" class="text-red-500 text-sm mt-2"></p>

      <!-- Botões de navegação e de envio do formulário -->
      <div class="flex justify-between items-center mt-4">
        <!-- Link para quem já tem conta e deseja fazer login -->
        <a href="<?= BASE_URL ?>login.php" class="text-red-600 text-lg">Já tenho uma conta</a>

        <!-- Botão para enviar o formulário de cadastro -->
        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700">Cadastrar</button>
      </div>
    </form>

  </div>
</body>

</html>