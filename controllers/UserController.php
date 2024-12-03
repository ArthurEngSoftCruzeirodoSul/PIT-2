<?php
class UserController
{
    private $userModel;
    private $db;

    /**
     * Construtor da classe.
     * Inicializa a classe com a conexão ao banco de dados e o modelo de usuário.
     * 
     * @param PDO $db A instância de conexão ao banco de dados.
     * @param UserModel $userModel Instância do modelo de usuário.
     */
    public function __construct($db, $userModel)
    {
        $this->db = $db;
        $this->userModel = $userModel;
    }

    /**
     * Método para registrar um novo usuário.
     * 
     * Este método valida os dados recebidos, verifica se o e-mail já está registrado
     * e, em seguida, insere o novo usuário no banco de dados, gerando o hash da senha.
     * 
     * @param array $data Os dados do usuário, incluindo nome, e-mail, senha e confirmação de senha.
     * 
     * @return array Um array com a chave 'success' indicando se o registro foi bem-sucedido e a chave 'message' com a mensagem de retorno.
     */
    public function register($data)
    {
        // Array para armazenar os erros de validação
        $errors = [];

        // Validação de dados
        if (empty($data['name'])) {
            $errors[] = 'O nome é obrigatório.';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mail inválido.';
        }
        if (empty($data['password'])) {
            $errors[] = 'A senha é obrigatória.';
        } elseif (strlen($data['password']) < 8) {
            // Se a senha for menor que 8 caracteres, retorna imediatamente o erro
            $errors[] = 'A senha deve ter no mínimo 8 caracteres.';
        } elseif ($data['password'] !== $data['confirm_password']) {
            // Se as senhas não coincidirem
            $errors[] = 'As senhas não coincidem.';
        }

        // Se houver erros de validação, retorna imediatamente
        if (!empty($errors)) {
            return ['success' => false, 'message' => implode('<br>', $errors)];
        }

        // Verifica se o e-mail já existe no banco de dados
        $existingUser = $this->userModel->findByEmail($data['email']);
        if ($existingUser) {
            return ['success' => false, 'message' => 'Já existe um usuário com este e-mail.'];
        }

        // Se não houver erros, cria o novo usuário
        try {
            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
            $this->userModel->createUser($data['name'], $data['email'], $data['address'], $passwordHash);

            return ['success' => true, 'message' => 'Cadastro realizado com sucesso.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erro ao registrar o usuário: ' . $e->getMessage()];
        }
    }

    /**
     * Método para autenticar um usuário.
     * 
     * @param array $data Dados de login, incluindo e-mail e senha.
     * 
     * @return array Um array com 'success' indicando se o login foi bem-sucedido ou 'error' caso contrário.
     */
    public function login($data, $noRedirect = false)
    {
        // // Inicia a sessão se ainda não foi iniciada
        // if (session_status() == PHP_SESSION_NONE) {
        //     session_start();
        // }


        // Validações básicas
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'E-mail inválido.'];
        }
        if (empty($data['password'])) {
            return ['error' => 'A senha é obrigatória.'];
        }

        // Busca o usuário no banco de dados
        $user = $this->userModel->findByEmail($data['email']);
        if (!$user) {
            return ['error' => 'Usuário não encontrado.'];
        }

        // Verifica a senha
        if (!password_verify($data['password'], $user['senha_hash'])) {
            return ['error' => 'Senha incorreta.'];
        }
<<<<<<< HEAD

        // Armazena os dados do usuário na sessão
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['logged_in'] = true;

        // Evita o redirecionamento durante os testes
        if ($_SESSION['logged_in'] && !$noRedirect) {
            header('Location: ' . BASE_URL . 'views/vitrineCupcakes.php'); 
            exit();
        }

        return ['success' => true, 'message' => 'Login realizado com sucesso!'];
=======
        // Se estiver em modo de teste, evita o redirecionamento real
        if ($testMode) {
            return ['success' => 'Login realizado com sucesso!'];
        }

        // Em vez de redirecionar com header(), incluímos o conteúdo de vitrineCupcakes.php
        require __DIR__ . '/vitrineCupcakes.php';
        exit;

>>>>>>> 41ba1ca (PIT 2)
    }
    /**
     * Método para atualizar as informações do perfil de um usuário.
     * 
     * Este método recebe os dados de perfil atualizados (nome, endereço e e-mail),
     * valida os dados recebidos e chama o método `updateUser` do modelo de usuário
     * para realizar a atualização no banco de dados. Ele retorna uma resposta indicando
     * se a atualização foi bem-sucedida ou se ocorreu algum erro.
     * 
     * @param array $data Os dados do perfil a serem atualizados. Deve conter:
     *                     - 'name': o nome atualizado do usuário.
     *                     - 'address': o endereço atualizado do usuário.
     *                     - 'email': o e-mail do usuário (usado para identificar o usuário).
     * 
     * @return array Um array com a chave 'success' indicando se a atualização foi bem-sucedida
     *               ou 'error' caso contrário, e a chave 'message' com a mensagem de retorno.
     */
    public function updateProfile($data)
    {
        // Array para armazenar os erros de validação
        $errors = [];

        // Validação de dados
        if (empty($data['name'])) {
            $errors[] = 'O nome é obrigatório.';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mail inválido.';
        }
        if (empty($data['address'])) {
            $errors[] = 'O endereço é obrigatório.';
        }

        // Se houver erros de validação, retorna com os erros
        if (!empty($errors)) {
            return ['success' => false, 'message' => implode('<br>', $errors)];
        }

        // Verifica se o usuário existe no banco de dados
        $existingUser = $this->userModel->findByEmail($data['email']);
        if (!$existingUser) {
            return ['success' => false, 'message' => 'Usuário não encontrado.'];
        }

        // Chama o método do modelo para atualizar os dados do usuário
        try {
            $updateSuccess = $this->userModel->updateUser($data);

            // Retorna sucesso ou erro dependendo do resultado da atualização
            if ($updateSuccess) {
                return ['success' => true, 'message' => 'Perfil atualizado com sucesso.'];
            } else {
                return ['success' => false, 'message' => 'Erro ao atualizar o perfil.'];
            }
        } catch (Exception $e) {
            // Se ocorrer algum erro durante o processo de atualização
            return ['success' => false, 'message' => 'Erro ao atualizar o perfil: ' . $e->getMessage()];
        }
    }
    /**
     * Método para processar a recuperação de senha.
     * 
     * Este método valida o e-mail fornecido pelo usuário, verifica se ele existe no banco de dados
     * e retorna uma resposta indicando se o e-mail é válido e se existe um usuário associado a esse e-mail.
     * Caso o e-mail seja válido e o usuário exista, retorna uma mensagem indicando que as instruções de recuperação
     * seriam enviadas (simulação). Caso contrário, retorna um erro com a mensagem apropriada.
     * 
     * @param array $data Dados do formulário, contendo:
     *                     - 'email': o e-mail do usuário para validação.
     * 
     * @return array Um array com a chave 'success' indicando se a recuperação foi bem-sucedida e a chave 'message'
     *               com a mensagem de resposta. Exemplo:
     *               ['success' => true, 'message' => 'E-mail encontrado. Instruções de recuperação serão enviadas.']
     *               Ou:
     *               ['success' => false, 'message' => 'E-mail inválido.']
     */
    public function recoverPassword($data)
    {
        // Valida o e-mail fornecido
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'E-mail inválido.'];
        }

        // Verifica se o e-mail existe no banco de dados
        $user = $this->userModel->findByEmail($data['email']);
        if (!$user) {
            return ['success' => false, 'message' => 'Usuário não encontrado.'];
        }

        // Se o e-mail existir no banco, retorna sucesso
        return ['success' => true, 'message' => 'E-mail encontrado. Instruções de recuperação serão enviadas (simulação).'];
    }

}
