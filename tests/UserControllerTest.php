<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserControllerTestesUnitarioTest extends TestCase
{
    private $db;
    private $userController;
    private $userModel;

    /**
     * Configuração inicial antes de cada test.
     * Cria um banco de dados em memória e a tabela 'usuarios' necessária para os tests.
     */
    protected function setUp(): void
    {
        // Configuração do banco de dados em memória para tests (SQLite)
        $this->db = new PDO('sqlite::memory:');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Criando a tabela de usuários necessária para os tests
        $this->db->exec("CREATE TABLE usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT,
            email TEXT,
            senha_hash TEXT,
            endereco TEXT,
            criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Instanciando o UserModel
        $this->userModel = new UserModel($this->db);

        // Instanciando o UserController com o banco de dados em memória e o UserModel
        $this->userController = new UserController($this->db, $this->userModel);
    }

    /**
     * Testa o sucesso no cadastro de um usuário.
     * Espera-se que o cadastro seja realizado corretamente e retorne uma mensagem de sucesso.
     */
    public function testCadastroSucesso()
    {
        $name = "João";
        $email = "joao@teste.com";
        $address = "Rua X, 123";
        $password = "12345678";
        $confirmPassword = "12345678";

        // Realiza o cadastro do usuário com os dados fornecidos
        $result = $this->userController->register([
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'password' => $password,
            'confirm_password' => $confirmPassword
        ]);

        // Verifica se a resposta contém a chave 'success' e a mensagem de sucesso
        $this->assertArrayHasKey('success', $result);
        $this->assertEquals('Cadastro realizado com sucesso.', $result['message']);
    }

    /**
     * Testa o cadastro com senhas que não coincidem.
     * Espera-se que o método retorne um erro indicando que as senhas não coincidem.
     */
    public function testCadastroSenhaNaoCoincide()
    {
        $name = "João";
        $email = "joao@test.com";
        $address = "Rua X, 123";
        $password = "12345678";
        $confirmPassword = "87654321";

        // Realiza o cadastro com senhas diferentes
        $result = $this->userController->register([
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'password' => $password,
            'confirm_password' => $confirmPassword
        ]);

        // Verifica se a resposta contém um erro indicando que as senhas não coincidem
        $this->assertArrayHasKey('message', $result);
        $this->assertEquals('As senhas não coincidem.', $result['message']);
    }

    /**
     * Testa o cadastro com um e-mail já registrado.
     * Espera-se que o método retorne um erro indicando que o e-mail já está registrado.
     */
    public function testCadastroEmailJaRegistrado()
    {
        $name = "João";
        $email = "joao@test.com";
        $address = "Rua X, 123";
        $password = "12345678";
        $confirmPassword = "12345678";

        // Insere um usuário no banco com o e-mail fornecido
        $this->userController->register([
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'password' => $password,
            'confirm_password' => $confirmPassword
        ]);

        // Tenta registrar outro usuário com o mesmo e-mail
        $newName = "Maria";
        $newEmail = "joao@test.com"; // E-mail já existente
        $newAddress = "Rua Y, 456";
        $newPassword = "87654321";
        $newConfirmPassword = "87654321";

        $result = $this->userController->register([
            'name' => $newName,
            'email' => $newEmail,
            'address' => $newAddress,
            'password' => $newPassword,
            'confirm_password' => $newConfirmPassword
        ]);

        // Verifica se a resposta contém um erro indicando que o e-mail já está registrado
        $this->assertArrayHasKey('message', $result);
        $this->assertEquals('Já existe um usuário com este e-mail.', $result['message']);
    }

    /**
     * Testa o sucesso no login com dados corretos.
     * Espera-se que o login seja realizado com sucesso após o cadastro do usuário.
     */
    public function testLoginSucesso()
    {
        $name = "João";
        $email = "joao@test.com";
        $address = "Rua X, 123";
        $password = "12345678";
        $confirmPassword = "12345678";

        // Realiza o cadastro do usuário antes de tentar o login
        $this->userController->register([
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'password' => $password,
            'confirm_password' => $confirmPassword
        ]);

        // Tenta fazer login com as credenciais corretas
        $loginData = ['email' => $email, 'password' => $password];

        // Passa 'true' para não realizar o redirecionamento durante o teste
        $result = $this->userController->login($loginData, true);

        // Verifica se a resposta contém a chave 'success' e a mensagem de sucesso
        $this->assertArrayHasKey('success', $result);
        $this->assertEquals('Login realizado com sucesso!', $result['success']);
    }


    /**
     * Testa o login com senha incorreta.
     * Espera-se que o login falhe com uma mensagem de erro informando que a senha está incorreta.
     */
    public function testLoginSenhaIncorreta()
    {
        $name = "João";
        $email = "joao@test.com";
        $address = "Rua X, 123";
        $password = "12345678";
        $confirmPassword = "12345678";

        // Realiza o cadastro do usuário antes de tentar o login
        $this->userController->register([
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'password' => $password,
            'confirm_password' => $confirmPassword
        ]);

        // Tenta fazer login com a senha errada
        $loginData = ['email' => $email, 'password' => 'senha_errada']; // Senha errada
        $result = $this->userController->login($loginData);

        // Verifica se a resposta contém a chave 'error' com a mensagem de erro esperada
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('Senha incorreta.', $result['error']);
    }

    /**
     * Testa o login quando o usuário não existe.
     * Espera-se que o login falhe com uma mensagem de erro informando que o usuário não foi encontrado.
     */
    public function testLoginUsuarioNaoEncontrado()
    {
        // Tenta fazer login com um e-mail que não existe no banco de dados
        $loginData = ['email' => 'usuario_inexistente@test.com', 'password' => '123456'];
        $result = $this->userController->login($loginData);

        // Verifica se a resposta contém a chave 'error' com a mensagem de erro esperada
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('Usuário não encontrado.', $result['error']);
    }

    /**
     * Testa a atualização do perfil de um usuário com sucesso.
     */
    public function testEditarPerfilSucesso()
    {
        // Simula o registro de um usuário
        $userData = [
            'name' => 'João',
            'email' => 'joao@test.com',
            'address' => 'Rua X, 123',
            'password' => '12345678',
            'confirm_password' => '12345678'
        ];

        // Primeiro, registramos o usuário (assumindo que o método register existe e funciona corretamente)
        $this->userController->register($userData);

        // Dados de atualização
        $updateData = [
            'name' => 'João Silva',
            'email' => 'joao@test.com',  // O e-mail não vai mudar
            'address' => 'Rua Y, 456'
        ];

        // Chama o método updateProfile
        $result = $this->userController->updateProfile($updateData);

        // Verifica se o resultado é de sucesso
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
        $this->assertEquals('Perfil atualizado com sucesso.', $result['message']);
    }

    /**
     * Testa a atualização de perfil com dados inválidos.
     */
    public function testEditarPerfilValidacaoDadosInvalidos()
    {
        // Simula o registro de um usuário
        $userData = [
            'name' => 'João',
            'email' => 'joao@test.com',
            'address' => 'Rua X, 123',
            'password' => '12345678',
            'confirm_password' => '12345678'
        ];

        // Primeiro, registramos o usuário
        $this->userController->register($userData);

        // Dados de atualização com nome vazio
        $updateData = [
            'name' => '',
            'email' => 'joao@test.com',  // E-mail correto
            'address' => 'Rua Y, 456'
        ];

        // Chama o método updateProfile
        $result = $this->userController->updateProfile($updateData);

        // Verifica se o resultado contém erro devido ao nome vazio
        $this->assertArrayHasKey('success', $result);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('O nome é obrigatório.', $result['message']);
    }
    /**
     * Testa a atualização de perfil quando o usuário não existe.
     */
    public function testEditarPerfilValidacaoUsuarioNaoEncontrado()
    {
        // Dados de atualização para um usuário que não existe
        $updateData = [
            'name' => 'Maria',
            'email' => 'maria@test.com',  // E-mail que não está no banco de dados
            'address' => 'Rua Z, 789'
        ];

        // Chama o método updateProfile
        $result = $this->userController->updateProfile($updateData);

        // Verifica se o resultado contém erro indicando que o usuário não foi encontrado
        $this->assertArrayHasKey('success', $result);
        $this->assertFalse($result['success']);
        $this->assertEquals('Usuário não encontrado.', $result['message']);
    }
     /**
     * Teste para e-mail encontrado ao tentar recuperar a senha.
     *
     * Este teste simula a situação onde o e-mail informado para recuperação de senha
     * é encontrado no banco de dados. O sistema deve retornar uma mensagem indicando
     * que o e-mail foi encontrado e que as instruções de recuperação serão enviadas.
     */
    public function testRecuperarSenhaEmailEncontrado()
    {
        // Insere um usuário no banco de dados com um e-mail válido
        $this->db->exec("INSERT INTO usuarios (nome, email, senha_hash, endereco) 
                         VALUES ('João', 'usuario@dominio.com', 'hash_senha', 'Rua X, 123')");

        // Agora chamamos o método recoverPassword com o e-mail existente no banco
        $dados = ['email' => 'usuario@dominio.com'];
        $resposta = $this->userController->recoverPassword($dados);

        // Verifica se o resultado está correto
        $this->assertTrue($resposta['success']);
        $this->assertEquals('E-mail encontrado. Instruções de recuperação serão enviadas (simulação).', $resposta['message']);
    }
/**
     * Teste para e-mail não encontrado ao tentar recuperar a senha.
     *
     * Este teste simula a situação onde o e-mail informado para recuperação de senha
     * não está registrado no banco de dados. O sistema deve retornar uma mensagem
     * indicando que o usuário não foi encontrado.
     */
    public function testRecuperarSenhaUsuarioNaoEncontrado()
    {
        // Tenta recuperar a senha de um e-mail que não existe no banco de dados
        $dados = ['email' => 'usuario@naoexistente.com'];
        $resposta = $this->userController->recoverPassword($dados);

        // Verifica se o resultado está correto
        $this->assertFalse($resposta['success']);
        $this->assertEquals('Usuário não encontrado.', $resposta['message']);
    }
}
?>