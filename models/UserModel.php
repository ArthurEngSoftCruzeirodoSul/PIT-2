<?php
class UserModel
{
    private $db;

    /**
     * Construtor da classe.
     * Inicializa a classe com a conexão ao banco de dados.
     * 
     * @param PDO $db Instância de conexão com o banco de dados.
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Função para encontrar um usuário por e-mail.
     * 
     * @param string $email O e-mail do usuário.
     * 
     * @return array|null O usuário encontrado ou null se não encontrado.
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Função para criar um novo usuário.
     * 
     * @param string $name O nome do usuário.
     * @param string $email O e-mail do usuário.
     * @param string $address O endereço do usuário.
     * @param string $passwordHash O hash da senha do usuário.
     * 
     * @return bool Retorna true em caso de sucesso ou false em caso de erro.
     */
    public function createUser($name, $email, $address, $passwordHash)
    {
        // Verifica o tipo de banco de dados (SQLite ou MySQL)
        $dbType = $this->db->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($dbType == 'sqlite') {
            // Se for SQLite, usa datetime('now')
            $stmt = $this->db->prepare(
                "INSERT INTO usuarios (nome, email, senha_hash, endereco, criado_em)
                 VALUES (:name, :email, :password_hash, :address, datetime('now'))"
            );
        } else {
            // Para MySQL ou MariaDB, usa NOW()
            $stmt = $this->db->prepare(
                "INSERT INTO usuarios (nome, email, senha_hash, endereco, criado_em)
                 VALUES (:name, :email, :password_hash, :address, NOW())"
            );
        }
        // Vincula as variáveis $name, $email, $passwordHash e $address aos respectivos marcadores de posição na consulta preparada, garantindo segurança e integridade dos dados.
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $passwordHash);
        $stmt->bindParam(':address', $address);

        // Executa a declaração preparada e retorna true ou false dependendo do sucesso da execução.
        return $stmt->execute();
    }
    /**
     * Atualiza os dados de um usuário no banco de dados.
     * 
     * Este método realiza a atualização dos dados de um usuário no banco de dados, incluindo o nome
     * e o endereço, com base no e-mail fornecido. A atualização é feita apenas se o e-mail do usuário
     * já existir no banco.
     * 
     * @param array $data Um array associativo contendo os dados a serem atualizados. O array deve conter:
     *                     - 'name': o nome do usuário.
     *                     - 'address': o endereço do usuário.
     *                     - 'email': o e-mail do usuário (utilizado para localizar o registro no banco).
     * 
     * @return bool Retorna `true` em caso de sucesso na atualização, ou `false` em caso de erro.
     */
    public function updateUser($data)
    {
        // Prepara a consulta SQL para atualizar os dados do usuário
        $stmt = $this->db->prepare("UPDATE usuarios SET nome = :name, endereco = :address WHERE email = :email");

        // Vincula os parâmetros da consulta com os valores fornecidos no array $data
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':email', $data['email']);

        // Executa a consulta e retorna o resultado (true ou false)
        return $stmt->execute();
    }

}
