<?php

return [
    'home' => BASE_URL . '/vitrineCupcakes.php',
<<<<<<< HEAD
=======
    'login' => BASE_URL . '/index.php?action=login',
>>>>>>> 41ba1ca (PIT 2)
    'register' => BASE_URL . '/cadastro.php',
];
// Roteamento para recuperação de senha
if (isset($_GET['action']) && $_GET['action'] === 'recoverPassword') {
    $userController->recoverPassword($_POST);
    exit;
}

?>