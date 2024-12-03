<?php
class CartaoModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Método para criar um cartão no banco de dados.
     * 
     * @param int $usuario_id ID do usuário.
     * @param string $numero_cartao Número do cartão.
     * @param string $nome_cartao Nome no cartão.
     * @param string $validade Data de validade do cartão.
     * @param string $cvv Código de segurança do cartão.
     * 
     * @return bool Retorna true em caso de sucesso ou false em caso de falha.
     */
    public function createCartao($usuario_id, $numero_cartao, $nome_cartao, $validade, $cvv)
    {
        $stmt = $this->db->prepare("INSERT INTO cartoes (usuario_id, numero_cartao, nome_cartao, validade, cvv) 
                                    VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$usuario_id, $numero_cartao, $nome_cartao, $validade, $cvv]);
    }

    /**
     * Método para buscar o cartão de um usuário.
     * 
     * @param int $usuario_id ID do usuário.
     * 
     * @return array Retorna os dados do cartão ou false se não encontrado.
     */
    public function getCartaoByUserId($usuario_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM cartoes WHERE usuario_id = ? LIMIT 1");
        $stmt->execute([$usuario_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Método para apagar um cartão.
     * 
     * @param int $cartao_id ID do cartão a ser apagado.
     * 
     * @return bool Retorna true em caso de sucesso ou false em caso de falha.
     */
    public function deleteCartao($cartao_id)
    {
        $stmt = $this->db->prepare("DELETE FROM cartoes WHERE id = ?");
        return $stmt->execute([$cartao_id]);
    }
}
?>
