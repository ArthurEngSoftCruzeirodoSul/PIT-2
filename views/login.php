<?php
// Inclui os arquivos de configuração e rotas, necessários para a aplicação
require_once __DIR__ . '/../config.php';  // Configuração do banco de dados e outros parâmetros
require_once __DIR__ . '/../routes.php';  // Define as rotas da aplicação

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <!-- Definição do charset e da meta tag para responsividade -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Título da página que será exibido na aba do navegador -->
  <title>Login</title>

  <!-- Inclusão do Tailwind CSS via CDN para estilizar os elementos da página -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-red-600 flex items-center justify-center min-h-screen">
  <!-- Container principal da página, com fundo branco, padding e bordas arredondadas -->
  <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
    <!-- Cabeçalho da página com título centralizado -->
    <h1 class="text-center text-3xl font-bold text-red-600 mb-6">Login</h1>

    <!-- Exibe a mensagem de erro, caso exista, vinda da sessão -->
    <?php if (isset($_SESSION['message'])): ?>
      <!-- A mensagem de erro será exibida em vermelho, com uma borda inferior -->
      <div class="text-red-500 text-sm mb-4">
        <?= htmlspecialchars($_SESSION['message']);
        unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <!-- Formulário de login -->
    <form method="POST" action="/../teste/index.php?action=login" class="space-y-6">

      <!-- Campo para o e-mail do usuário -->
      <div>
        <label for="login-email" class="block text-gray-700">E-mail</label>
        <input type="email" name="email" id="login-email" placeholder="E-mail"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-400"
          required />
      </div>

      <!-- Campo para a senha do usuário -->
      <div>
        <label for="login-password" class="block text-gray-700">Senha</label>
        <input type="password" name="password" id="login-password" placeholder="Senha"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-400"
          required />
      </div>

      <!-- Mensagem de erro específica para falhas de login -->
      <?php if (isset($errorMessage)): ?>
        <p id="error-message" class="text-red-600 text-sm mt-2"><?= htmlspecialchars($errorMessage) ?></p>
      <?php endif; ?>

      <!-- Link para recuperação de senha caso o usuário tenha esquecido -->
      <div class="text-right">
        <a href="<?= BASE_URL ?>/views/recuperarSenha.php" class="text-red-600 text-sm">Esqueci minha senha</a>
      </div>

      <!-- Links de navegação e botão de login -->
      <div class="flex justify-between items-center mt-4">
        <!-- Link para a página de cadastro, caso o usuário ainda não tenha conta -->
        <a href="<?= BASE_URL ?>/views/cadastro.php" class="text-red-600 text-lg">Criar Conta</a>

        <!-- Botão para submeter o formulário de login -->
        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700">
          Entrar
        </button>
      </div>
    </form>
  </div>
</body>

</html>