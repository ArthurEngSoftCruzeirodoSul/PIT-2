<?php 
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../models/CartaoModel.php';
require_once __DIR__ . '/../controllers/CartaoController.php';
class CartaoControllerTestesUnitarioTest extends TestCase
{
    private $db;
    private $cartaoController;
    private $cartaoModel;

    /**
     * Configuração inicial antes de cada test.
     * Cria um banco de dados em memória e a tabela 'cartoes' necessária para os tests.
     */
    protected function setUp(): void
    {
        // Configuração do banco de dados em memória para tests (SQLite)
        $this->db = new PDO('sqlite::memory:');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Criando a tabela 'cartoes' necessária para os tests
        $this->db->exec("CREATE TABLE cartoes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            usuario_id INTEGER,
            numero_cartao TEXT,
            nome_cartao TEXT,
            validade TEXT,
            cvv TEXT,
            criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Instanciando o CartaoModel
        $this->cartaoModel = new CartaoModel($this->db);

        // Instanciando o CartaoController com o banco de dados em memória e o CartaoModel
        $this->cartaoController = new CartaoController($this->db, $this->cartaoModel);
    
    }
 /**
     * Testa o sucesso ao salvar um cartão.
     * Espera-se que os dados sejam salvos com sucesso.
     */
    public function testSalvarCartaoSucesso()
    {
        $data = [
            'numero_cartao' => '1234567890123456',
            'nome_cartao' =>'Maria Silva',
            'validade' => '12/26',
            'cvv' => '123'
        ];
        $usuario_id = 1;  // Simulando um ID de usuário válido

        // Salva o cartão com os dados fornecidos
        $result = $this->cartaoController->saveCartao($data, $usuario_id);

        // Verifica se o resultado contém sucesso
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
        $this->assertEquals('Dados do cartão salvos com sucesso!', $result['message']);
    }

/**
     * Testa o erro ao tentar salvar um cartão com dados inválidos.
     * Espera-se que o método retorne um erro indicando dados inválidos.
     */
    public function testSalvarCartaoValidarErroDadosInvalidos()
    {
        $data = [
            'numero_cartao' => '123',
            'nome_cartao' => '',
            'validade' => '12/23',
            'cvv' => '12'
        ];
        $usuario_id = 1;  // Simulando um ID de usuário válido

        // Tenta salvar o cartão com dados inválidos
        $result = $this->cartaoController->saveCartao($data, $usuario_id);

        // Verifica se o resultado contém um erro
        $this->assertArrayHasKey('success', $result);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Número do cartão inválido.', $result['message']);
        $this->assertStringContainsString('Nome no cartão é obrigatório.', $result['message']);
    }

    /**
     * Testa a exclusão de um cartão.
     * Espera-se que o cartão seja excluído com sucesso.
     */
    public function testExcluirCartaoSucesso()
    {
        $usuario_id = 1;
        $cartao_id = $this->cartaoModel->createCartao($usuario_id, '1234567890123456', 'João Silva', '12/23', '123');

        // Excluir o cartão
        $result = $this->cartaoController->deleteCartao($cartao_id);

        // Verifica se a resposta contém sucesso
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
        $this->assertEquals('Cartão apagado com sucesso!', $result['message']);
    }
     /**
     * Testa a recuperação do cartão de um usuário.
     * Espera-se que o cartão seja retornado com sucesso.
     */
    public function testGetCartaoSucesso()
    {
        $usuario_id = 1;
        $this->cartaoModel->createCartao($usuario_id, '1234567890123456', 'Maria Silva', '12/26', '123');

        // Recupera o cartão do usuário
        $result = $this->cartaoController->getCartao($usuario_id);

        // Verifica se o cartão foi encontrado e retornado com sucesso
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('cartao', $result);
        $this->assertEquals('1234567890123456', $result['cartao']['numero_cartao']);
        $this->assertEquals('Maria Silva', $result['cartao']['nome_cartao']);
        $this->assertEquals('12/26', $result['cartao']['validade']);
        $this->assertEquals('123', $result['cartao']['cvv']);
    }

    /**
     * Testa o erro ao tentar recuperar um cartão de um usuário sem cartão.
     * Espera-se que o método retorne uma mensagem de erro.
     */
    public function testGetCartaoErroUsuarioSemCartao()
    {
        $usuario_id = 1;  // Usuário sem cartão

        // Tenta recuperar o cartão
        $result = $this->cartaoController->getCartao($usuario_id);

        // Verifica se a resposta contém um erro
        $this->assertArrayHasKey('success', $result);
        $this->assertFalse($result['success']);
        $this->assertEquals('Nenhum cartão encontrado para este usuário.', $result['message']);
    }
}
