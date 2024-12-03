<?php 
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../models/CartaoModel.php';
require_once __DIR__ . '/../controllers/CartaoController.php';

class CartaoControllerTestesIntegracaoTest extends TestCase
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
     * Teste de Integração: Salvar e recuperar o cartão
     * Verifica se o cartão é salvo e recuperado corretamente.
     */
    public function testSalvarERecuperarCartao()
    {
        $data = [
            'numero_cartao' => '1234567890123456',
            'nome_cartao' => 'Maria Silva',
            'validade' => '12/26',
            'cvv' => '123'
        ];
        $usuario_id = 1;  // Simulando um ID de usuário válido

        // Salva o cartão com os dados fornecidos
        $resultSave = $this->cartaoController->saveCartao($data, $usuario_id);

        // Verifica se o cartão foi salvo corretamente
        $this->assertArrayHasKey('success', $resultSave);
        $this->assertTrue($resultSave['success']);

        // Agora, tenta recuperar o cartão para o mesmo usuário
        $resultGet = $this->cartaoController->getCartao($usuario_id);

        // Verifica se o cartão foi recuperado corretamente
        $this->assertArrayHasKey('success', $resultGet);
        $this->assertTrue($resultGet['success']);
        $this->assertArrayHasKey('cartao', $resultGet);
        $this->assertEquals($data['numero_cartao'], $resultGet['cartao']['numero_cartao']);
        $this->assertEquals($data['nome_cartao'], $resultGet['cartao']['nome_cartao']);
        $this->assertEquals($data['validade'], $resultGet['cartao']['validade']);
        $this->assertEquals($data['cvv'], $resultGet['cartao']['cvv']);
    }

    /**
     * Teste de Integração: Deletar um cartão e tentar recuperar
     * Verifica se o cartão é excluído corretamente e não pode ser recuperado.
     */
    public function testExcluirERecuperarCartao()
    {
        $usuario_id = 1;
        $cartao_id = $this->cartaoModel->createCartao($usuario_id, '1234567890123456', 'João Silva', '12/23', '123');

        // Excluir o cartão
        $resultDelete = $this->cartaoController->deleteCartao($cartao_id);

        // Verifica se a exclusão foi bem-sucedida
        $this->assertArrayHasKey('success', $resultDelete);
        $this->assertTrue($resultDelete['success']);
        
        // Tenta recuperar o cartão após a exclusão
        $resultGet = $this->cartaoController->getCartao($usuario_id);

        // Verifica se a resposta de recuperação é uma falha, pois o cartão foi excluído
        $this->assertArrayHasKey('success', $resultGet);
        $this->assertFalse($resultGet['success']);
        $this->assertEquals('Nenhum cartão encontrado para este usuário.', $resultGet['message']);
    }
}
?>
