<?php 
use PHPUnit\Framework\TestCase;

class UserControllerIntegracaoTest extends TestCase
{
    private $db;
    private $userController;
    private $userModel;

    /**
     * Configuração do ambiente antes de cada teste.
     * Aqui criamos a conexão com o banco de dados, instanciamos o modelo e o controlador.
     */
    protected function setUp(): void
    {
        // Criação de uma conexão simulada com o banco de dados utilizando SQLite na memória (para testes rápidos)
        $this->db = new PDO('sqlite::memory:'); // Banco de dados na memória
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Criação da tabela 'usuarios' no banco de dados para os testes
        $this->db->exec("
            CREATE TABLE usuarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome VARCHAR(100),
                email VARCHAR(100) UNIQUE,
                senha_hash VARCHAR(255),
                endereco VARCHAR(255),
                criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Instancia o modelo e o controlador com a conexão do banco de dados
        $this->userModel = new UserModel($this->db);
        $this->userController = new UserController($this->db, $this->userModel);
    }

    /**
     * Teste de integração para o processo de cadastro de um usuário.
     * Este teste verifica se um usuário pode ser registrado corretamente no sistema.
     */
    public function testCadastroUsuarioSucesso()
    {
        // Dados de entrada para o cadastro
        $data = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => '12345678',
            'confirm_password' => '12345678',
            'address' => 'Rua Exemplo, 123'
        ];

        // Chama o método de registro do controlador
        $result = $this->userController->register($data);

        // Verifica se a resposta foi de sucesso
        $this->assertTrue($result['success']);
        $this->assertEquals('Cadastro realizado com sucesso.', $result['message']);

        // Verifica se o usuário foi inserido corretamente no banco de dados
        $user = $this->userModel->findByEmail('joao@example.com');
        $this->assertNotEmpty($user);
        $this->assertEquals('João Silva', $user['nome']);
        $this->assertEquals('joao@example.com', $user['email']);
        $this->assertTrue(password_verify('12345678', $user['senha_hash'])); // Verifica se o hash da senha está correto
    }

    /**
     * Teste de integração para o caso de cadastro com e-mail já existente.
     * Este teste verifica se o sistema retorna o erro adequado quando o e-mail já está registrado.
     */
    public function testCadastroUsuarioComEmailExistente()
    {
        // Criação de um usuário no banco de dados diretamente para testar o caso de e-mail duplicado
        $this->userModel->createUser('Carlos Souza', 'carlos@example.com', 'Rua Exemplo, 456', password_hash('654321', PASSWORD_BCRYPT));

        // Dados de entrada para o cadastro com e-mail já existente
        $data = [
            'name' => 'Maria Oliveira',
            'email' => 'carlos@example.com',
            'password' => '12345678',
            'confirm_password' => '12345678',
            'address' => 'Rua Exemplo, 789'
        ];

        // Chama o método de registro do controlador
        $result = $this->userController->register($data);

        // Verifica se a resposta foi de erro e a mensagem é a esperada
        $this->assertFalse($result['success']);
        $this->assertEquals('Já existe um usuário com este e-mail.', $result['message']);
    }

    /**
     * Teste de integração para validação de senhas não coincidentes.
     * Este teste verifica se o sistema retorna erro quando as senhas não coincidem.
     */
    public function testCadastroUsuarioComSenhasNaoCoincidentes()
    {
        // Dados de entrada com senhas diferentes
        $data = [
            'name' => 'Pedro Martins',
            'email' => 'pedro@example.com',
            'password' => '12345678',
            'confirm_password' => '87654321', // Senha de confirmação diferente
            'address' => 'Rua Exemplo, 101'
        ];

        // Chama o método de registro do controlador
        $result = $this->userController->register($data);

        // Verifica se a resposta foi de erro e a mensagem de erro está correta
        $this->assertFalse($result['success']);
        $this->assertEquals('As senhas não coincidem.', $result['message']);
    }

    /**
     * Limpeza do ambiente após cada teste.
     * Aqui podemos apagar os dados e realizar qualquer limpeza necessária.
     */
    protected function tearDown(): void
    {
        // Exclui a tabela após cada teste para garantir um estado limpo para o próximo
        $this->db->exec("DROP TABLE IF EXISTS usuarios");
    }
}
?>
