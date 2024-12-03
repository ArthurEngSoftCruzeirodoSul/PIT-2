<<<<<<< HEAD
<?php
// Inclui os arquivos de configuração e rotas para o funcionamento do sistema.
require_once __DIR__ . '/../config.php'; // Carrega as configurações do banco de dados, entre outros.
require_once __DIR__ . '/../routes.php'; // Carrega as rotas definidas no sistema.
?>
=======
>>>>>>> 41ba1ca (PIT 2)
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <!-- Container centralizado com espaçamento adequado -->
    <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg max-w-sm w-full">
        <h1 class="text-2xl font-bold mb-6 text-center">Carrinho de Compras</h1>

        <!-- Lista de itens do carrinho -->
        <ul id="cart-items" class="space-y-4">
            <!-- Os itens serão adicionados dinamicamente aqui -->
        </ul>

        <!-- Total e botão de finalização -->
        <div class="mt-8 flex justify-between items-center">
            <span id="total-price" class="text-xl font-semibold">Total: R$ 0,00</span>
            <button onclick="verifyAndFinalizePurchase()" class="bg-red-600 text-white px-4 py-2 rounded-md">Finalizar Compra</button>
        </div>

        <!-- Link de voltar à vitrine alinhado à esquerda -->
        <div class="mt-6">
            <a href="#" onclick="goBackToVitrine()" class="text-red-600 hover:underline">Voltar à Vitrine</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', loadCart);

        function loadCart() {
            // Verificar se o usuário está logado
            const currentUser = localStorage.getItem('currentUser');
            if (!currentUser) {
                alert("Por favor, faça login para ver o carrinho.");
                window.location.href = 'http://lojacupcakes.freesite.online/';
                return;
            }

            // Carregar o carrinho do usuário específico
            const cart = JSON.parse(localStorage.getItem(`cart_${currentUser}`)) || [];
            const cartItemsContainer = document.getElementById('cart-items');
            const totalPriceElement = document.getElementById('total-price');
            cartItemsContainer.innerHTML = '';
            let totalPrice = 0;

            cart.forEach(item => {
                const itemElement = document.createElement('li');
                itemElement.classList.add('flex', 'justify-between', 'items-center');
                itemElement.innerHTML = `
                    <div>
                        <span class="font-medium">${item.item.charAt(0).toUpperCase() + item.item.slice(1)} (${item.quantity})</span>
                        <p class="text-gray-500 text-sm">Preço unitário: R$ ${item.price.toFixed(2)}</p>
                        <div class="flex items-center mt-2 space-x-2">
                            <button onclick="updateQuantity('${item.item}', -1)" class="bg-gray-300 text-black px-2 py-1 rounded-md">-</button>
                            <span class="mx-2">${item.quantity}</span>
                            <button onclick="updateQuantity('${item.item}', 1)" class="bg-gray-300 text-black px-2 py-1 rounded-md">+</button>
                        </div>
                    </div>
                    <span>R$ ${(item.price * item.quantity).toFixed(2)}</span>
                `;
                cartItemsContainer.appendChild(itemElement);
                totalPrice += item.price * item.quantity;
            });

            totalPriceElement.innerText = `Total: R$ ${totalPrice.toFixed(2)}`;
        }

        function updateQuantity(item, change) {
            const currentUser = localStorage.getItem('currentUser');
            const cart = JSON.parse(localStorage.getItem(`cart_${currentUser}`)) || [];
            const cartItem = cart.find(cartItem => cartItem.item === item);
            if (cartItem) {
                cartItem.quantity += change;
                if (cartItem.quantity <= 0) {
                    const index = cart.indexOf(cartItem);
                    cart.splice(index, 1);
                }
                localStorage.setItem(`cart_${currentUser}`, JSON.stringify(cart));
                loadCart();
            }
        }

        // Função que verifica se há itens no carrinho e se a forma de pagamento está preenchida
        function verifyAndFinalizePurchase() {
            const currentUser = localStorage.getItem('currentUser');
            if (!currentUser) {
                alert("Por favor, faça login para finalizar a compra.");
                window.location.href = 'http://lojacupcakes.freesite.online/';
                return;
            }

            // Verificar se o carrinho tem itens
            const cart = JSON.parse(localStorage.getItem(`cart_${currentUser}`)) || [];
            if (cart.length === 0) {
                alert("Seu carrinho está vazio! Adicione itens para continuar.");
                return;
            }

            // Verificar se os dados de pagamento foram preenchidos
            const cardData = localStorage.getItem(`cardData_${currentUser}`);
            if (!cardData) {
                alert("Por favor, preencha os dados do cartão de pagamento.");
                window.location.href = 'http://lojacupcakes.freesite.online/views/formaPagamento.php'; // Redireciona para a página de pagamento
                return;
            }

            // Confirmar dados antes de finalizar a compra
            const confirmation = confirm("Você confirma os dados do seu carrinho e forma de pagamento?");
            if (confirmation) {
                finalizePurchase();
            }
        }

        function finalizePurchase() {
            const currentUser = localStorage.getItem('currentUser');
            // Limpar o carrinho após a finalização da compra
            localStorage.removeItem(`cart_${currentUser}`);
            alert("Aguardando confirmação de pagamento...");
            window.location.href = 'http://lojacupcakes.freesite.online/views/compraFinalizada.php';
        }

        function goBackToVitrine() {
            window.location.href = 'http://lojacupcakes.freesite.online/views/vitrineCupcakes.php';
        }
    </script>
</body>

</html>