<?php
// Inicia a sessão para acessar as variáveis de sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: /views/login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- Configura o conjunto de caracteres para UTF-8 -->
    <meta charset="UTF-8">
    <!-- Define a largura da viewport para a largura do dispositivo, importante para responsividade -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título da página exibido na aba do navegador -->
    <title>Vitrine de Cupcakes</title>
    <!-- Carrega a biblioteca TailwindCSS via CDN para estilização rápida -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Carrega os ícones do FontAwesome via CDN para uso dos ícones de carrinho e perfil -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="bg-gray-100">
    <!-- Cabeçalho fixo no topo, visível ao rolar a página -->
    <header class="bg-red-600 text-white p-6 flex justify-between items-center sticky top-0 z-50 ">
        <!-- Título do site ou loja -->
        <h1 class="text-xl font-bold">Vitrine de Cupcakes</h1>
        <div class="flex items-center space-x-6">
            <!-- Botão de Carrinho com contador de itens -->
            <button class="relative" onclick="goToCart()">
                <i class="fas fa-shopping-cart text-2xl"></i>
                <!-- Contador de itens no carrinho -->
                <span id="cart-count"
                    class="absolute  bottom-[0.45rem] left-0 text-red-600 rounded-full px-2 text-gg">0</span>
            </button>
            <!-- Botão para perfil do usuário -->
            <button onclick="goToProfile()" class="text-white hover:text-gray-200">
                <i class="fas fa-user-circle text-2xl"></i>
                <!-- Texto alternativo para acessibilidade -->
                <span class="sr-only">Configurações de Perfil</span>
            </button>
        </div>
    </header>

    <!-- Conteúdo principal da página -->
    <main class="p-4">
        <!-- Grade de cupcakes, adaptável para diferentes tamanhos de tela -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="cupcakes-container">
            <!-- Os cupcakes serão renderizados aqui via JavaScript -->
        </div>

    </main>
    <footer class="bg-white shadow mt-8 sticky bottom-0 z-50">
        <div class="container mx-auto px-8 py-1">
            <div class="flex justify-between items-center">
                <!-- Botão para o usuário acessar o carrinho -->
                <div class="mt-6 text-center">
                    <button onclick="goToCart()" class="bg-red-600 text-white px-4 py-2 rounded-md">Revisar
                        Pedido</button>
                </div>
                <div class="flex space-x-4">
                    <a class="text-gray-600 hover:text-gray-800">
                        <i class="fab fa-facebook">
                        </i>
                    </a>
                    <a class="text-gray-600 hover:text-gray-800">
                        <i class="fab fa-twitter">
                        </i>
                    </a>
                    <a class="text-gray-600 hover:text-gray-800">
                        <i class="fab fa-instagram">
                        </i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    <!-- Pop-up de confirmação ao adicionar item ao carrinho -->
    <div id="popup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center max-w-xs">
            <p class="text-lg font-bold text-gray-800">Item Adicionado ao Carrinho!</p>
        </div>
    </div>

    <!-- Script para renderizar cupcakes e gerenciar o carrinho -->
    <script>

        // Verifica se o usuário está logado com base na variável de sessão PHP
        const isLoggedIn = <?php echo isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true ? 'true' : 'false'; ?>;

        function loadProfile() {
            if (!isLoggedIn) {
                console.log("Usuário não encontrado. Redirecionando para o login...");
                window.location.href = '/views/login.php'; // Redireciona para a página de login
                return;
            }
        }

        loadProfile();



        // Lista de cupcakes disponíveis
        const cupcakes = [
            { name: "Chocolate", price: 10, imageUrl: "https://storage.googleapis.com/a1aa/image/9j7ZUP0k5kahPhluegR4n1olVCLfWTnvM44UdMfKxVRY4ZdnA.jpg", description: "Delicioso cupcake de chocolate com cobertura cremosa." },
            { name: "Baunilha", price: 12, imageUrl: "https://storage.googleapis.com/a1aa/image/ToVBqCqfVTTVbCm7NT3lMMQTnHOlv2tOr0JHDYQimvfK8suTA.jpg", description: "Clássico cupcake de baunilha, leve e fofinho." },
            { name: "Morango", price: 15, imageUrl: "https://storage.googleapis.com/a1aa/image/aUCGJObynfwROybPem3mymCsVOZbeI3gJe6YcviRR9tehn1dC.jpg", description: "Cupcake de morango fresco com cobertura de chantilly." },
            { name: "Limão", price: 11, imageUrl: "https://storage.googleapis.com/a1aa/image/HtaWhVQO7eWSfEyXJIxouXfsBeFltUFOG0MGSG7BHw11wz6OB.jpg", description: "Refrescante cupcake de limão com um toque azedo." },
            { name: "Coco", price: 13, imageUrl: "https://storage.googleapis.com/a1aa/image/EhfCjYxJu1QaEyEVkhCrpXFNyihBzwA4ZeQcxDtkk4qQ8suTA.jpg", description: "Delicioso cupcake de coco com cobertura de chocolate branco." },
            { name: "Amora", price: 14, imageUrl: "https://storage.googleapis.com/a1aa/image/DRjG0eRjyVUaMyDwIq29z0Y6GLeuDUU0TFgAjZAeYIET4ZdnA.jpg", description: "Cupcake de amora suculenta com um toque de creme." },
        ];

        // Seleciona o contêiner onde os cupcakes serão inseridos
        const cupcakesContainer = document.getElementById("cupcakes-container");

        // Função que formata um número como valor em reais
        function formatPrice(price) {
            return new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(price);
        }

        // Renderiza cada cupcake na tela, criando o HTML dinâmico para cada item
        cupcakes.forEach((cupcake) => {
            cupcakesContainer.innerHTML += `
            <div class="bg-white rounded-lg shadow-lg overflow-hidden group">
                <img src="${cupcake.imageUrl}" alt="Cupcake de ${cupcake.name}" class="w-full h-50 ">
                <div class="p-4">
                    <h2 class="text-lg font-bold">Cupcake de ${cupcake.name}</h2>
                    <p class="text-gray-600 mt-">${cupcake.description}</p>
                    <p class="text-gray-700">${formatPrice(cupcake.price)}</p>

                    <div class="flex items-center mt-4">
                        <label for="quantity-${cupcake.name.toLowerCase()}" class="mr-2">Quantidade:</label>
                        <input type="number" id="quantity-${cupcake.name.toLowerCase()}" class="w-16 p-2 border rounded-md" min="1" value="1">
                        <!-- Botão de adicionar ao carrinho, visível ao passar o mouse -->
                        <button onclick="addToCart('${cupcake.name.toLowerCase()}', ${cupcake.price})" class="bg-red-600 text-white px-4 py-2 rounded-md ml-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">Adicionar ao Carrinho</button>
                    </div>
                </div>
            </div>
            `;
        });

        // Atualiza o contador do carrinho com a quantidade total de itens
        function updateCartCount() {
            const currentUser = localStorage.getItem("currentUser");
            if (!currentUser) return;

            const cart = JSON.parse(localStorage.getItem(`cart_${currentUser}`)) || [];
            const cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById("cart-count").innerText = cartCount;
        }
        updateCartCount();

        // Adiciona um cupcake ao carrinho do usuário atualmente logado
        function addToCart(item, price) {
            const quantity = parseInt(document.getElementById(`quantity-${item}`).value);

            const currentUser = localStorage.getItem("currentUser");
            if (!currentUser) {
                alert("Por favor, faça login para adicionar itens ao carrinho.");
                return;
            }

            let cart = JSON.parse(localStorage.getItem(`cart_${currentUser}`)) || [];
            const existingItem = cart.find(cartItem => cartItem.item === item);

            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({ item, price, quantity });
            }

            localStorage.setItem(`cart_${currentUser}`, JSON.stringify(cart));
            updateCartCount();

            // Exibe a confirmação de item adicionado ao carrinho
            showPopup(`${quantity} cupcake(s) de ${item} adicionado(s) ao carrinho!`);
        }

        // Exibe uma mensagem pop-up de confirmação por 1 segundo
        function showPopup(message) {
            const popup = document.getElementById("popup");
            const popupText = popup.querySelector("p");
            popupText.textContent = message;

            popup.classList.remove("hidden");

            setTimeout(() => {
                popup.classList.add("hidden");
            }, 1000); //tempo em ms
        }

        // Navega para a página do carrinho, mas exige login
        function goToCart() {
            const currentUser = localStorage.getItem("currentUser");
            if (!currentUser) {
                alert("Por favor, faça login para acessar o carrinho.");
                return;
            }
            window.location.href = './carrinhoCompras.php'; // Alterado para caminho local
        }

        // Navega para a página de perfil do usuário
        function goToProfile() {
            window.location.href = './editarPerfil.php';
        }
    </script>
</body>

</html>