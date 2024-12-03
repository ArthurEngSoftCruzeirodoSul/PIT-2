<?php
class CartaoController
{
    private $db;
    private $cartaoModel;

    /**
     * Construtor da classe.
     * Inicializa a classe com a conexão ao banco de dados e o modelo de cartão.
     * 
     * @param PDO $db A instância de conexão ao banco de dados.
     * @param CartaoModel $cartaoModel Instância do modelo de cartão.
     */
    public function __construct($db, $cartaoModel)
    {
        $this->db = $db;
        $this->cartaoModel = $cartaoModel;
    }

    /**
     * Método para salvar os dados do cartão no banco de dados.
     * 
     * @param array $data Dados do cartão, incluindo número do cartão, nome no cartão, validade e CVV.
     * @param int $usuario_id ID do usuário associado ao cartão.
     * 
     * @return array Retorna o sucesso ou erro da operação.
     */
    public function saveCartao($data, $usuario_id)
    {
        // Validação dos dados do cartão
        $errors = [];

        if (empty($data['numero_cartao']) || !preg_match("/^[0-9]{13,19}$/", $data['numero_cartao'])) {
            $errors[] = "Número do cartão inválido.";
        }

        if (empty($data['nome_cartao'])) {
            $errors[] = "Nome no cartão é obrigatório.";
        }

        if (empty($data['validade']) || !preg_match("/^\d{2}\/\d{2}$/", $data['validade'])) {
            $errors[] = "Data de validade inválida. O formato é MM/AA.";
        }

        if (empty($data['cvv']) || !preg_match("/^\d{3,4}$/", $data['cvv'])) {
            $errors[] = "CVV inválido.";
        }

        if (!empty($errors)) {
            return ['success' => false, 'message' => implode('<br>', $errors)];
        }

        // Salvar no banco de dados
        $success = $this->cartaoModel->createCartao(
            $usuario_id,
            $data['numero_cartao'],
            $data['nome_cartao'],
            $data['validade'],
            $data['cvv']
        );

        if ($success) {
            return ['success' => true, 'message' => 'Dados do cartão salvos com sucesso!'];
        } else {
            return ['success' => false, 'message' => 'Erro ao salvar os dados do cartão.'];
        }
    }

    /**
     * Método para obter o cartão de um usuário.
     * Como cada usuário tem um único cartão, sempre retorna apenas um.
     * 
     * @param int $usuario_id ID do usuário para obter seu cartão.
     * 
     * @return array Retorna o cartão do usuário ou uma mensagem de erro.
     */
    public function getCartao($usuario_id)
    {
        $cartao = $this->cartaoModel->getCartaoByUserId($usuario_id);

        if ($cartao) {
            return ['success' => true, 'cartao' => $cartao];
        } else {
            return ['success' => false, 'message' => 'Nenhum cartão encontrado para este usuário.'];
        }
    }

    /**
     * Método para apagar os dados de um cartão.
     * 
     * @param int $cartao_id ID do cartão a ser apagado.
     * 
     * @return array Retorna sucesso ou erro da operação.
     */
    public function deleteCartao($cartao_id)
    {
        $success = $this->cartaoModel->deleteCartao($cartao_id);

        if ($success) {
            return ['success' => true, 'message' => 'Cartão apagado com sucesso!'];
        } else {
            return ['success' => false, 'message' => 'Erro ao apagar o cartão.'];
        }
    }
}
?>
