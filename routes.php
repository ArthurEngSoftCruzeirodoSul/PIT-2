<?php

return [
    'home' => BASE_URL . '/vitrineCupcakes.php',
    'register' => BASE_URL . '/cadastro.php',
];
// Roteamento para recuperação de senha
if (isset($_GET['action']) && $_GET['action'] === 'recoverPassword') {
    $userController->recoverPassword($_POST);
    exit;
}

?>