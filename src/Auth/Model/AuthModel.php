<?php

namespace src\Auth\Model;

use \PDO;

class AuthModel
{
    public function login(array $post)
    {
        $verificaLogin = $this->verificaLogin($post);

        if($verificaLogin) {
            session_start();

            $_SESSION['email'] = $post['email'];

            header("Location: /dashboard");
        } else {
            echo '<pre>';
            var_dump($verificaLogin);
            die();
        }
    }
    
    public function verificaLogin($post)
    {
    	//Usuário
	    $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : null;
	    $senha = isset($_POST['senha']) && !empty($_POST['senha']) ? $_POST['senha'] : null;
    	
	    // Conexão com banco de dados
    	$servername = "localhost";
	    $username   = "root";
    	$password   = "";
    	$db_name    = "ecoponto";
    
    	try {
            $pdo = new PDO("mysql:dbname=" . $db_name . ";host=" . $servername, $username, $password);
    	} catch (\PDOException $e) {
            $msgErro = $e->getMessage();
    	}
	
        // OBTER DADOS DO USUÁRIO
        $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email = :e");
	    $sql->bindValue(":e", $email);
    	$sql->execute();

    	if($sql->rowCount() > 0) { return true; }

        return false;
    }

    public function cadastrar($post)
    {
        // Conexão com banco de dados
        $servername = "localhost";
        $username   = "root";
        $password   = "";
        $db_name    = "ecoponto";
        
        try {
            $pdo = new PDO("mysql:dbname=" . $db_name . ";host=" . $servername, $username, $password);
        } catch (\PDOException $e) {
            $msgErro = $e->getMessage();
        }

        //Usuário
        $nome     = $_POST['nome_completo'];
        $telefone = isset($_POST['telefone']) && !empty($_POST['telefone']) ? $_POST['telefone'] : NULL;
        $cpf      = isset($_POST['cpf'])      && !empty($_POST['cpf'])      ? $_POST['cpf']      : NULL;
        $email    = $_POST['email'];
        $senha    = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        //Endereço
        $cep         = isset($_POST['cep'])         && !empty($_POST['cep'])         ? $_POST['cep']         : NULL;
        $est_cid     = isset($_POST['est_cid'])     && !empty($_POST['est_cid'])     ? $_POST['est_cid']     : NULL;
        $bairro      = isset($_POST['bairro'])      && !empty($_POST['bairro'])      ? $_POST['bairro']      : NULL;
        $rua         = isset($_POST['rua'])         && !empty($_POST['rua'])         ? $_POST['rua']         : NULL;
        $complemento = isset($_POST['complemento']) && !empty($_POST['complemento']) ? $_POST['complemento'] : NULL;

        // VERIFICAR SE O USUÁRIO JÁ EXISTE
        $sql = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :e");
        $sql->bindValue(":e", $email);
        $sql->execute();

        if($sql->rowCount() <= 0)
        {
            // CASO O USUÁRIO NÃO EXISTA, CADASTRE-O
            $sql = $pdo->prepare("INSERT INTO usuarios (nome, telefone, cpf, email, senha) VALUES (:n, :t, :c, :e, :s)");
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":t", $telefone);
            $sql->bindValue(":c", $cpf);
            $sql->bindValue(":e", $email);
            $sql->bindValue(":s", $senha);
            $sql->execute();

            $sql = $pdo->prepare("INSERT INTO endereco (id_usuario, cep, estado_cidade, bairro, rua, complemento) VALUES (:i, :c, :e, :b, :r, :l)");
            $sql->bindValue(":i", $pdo->lastInsertId());
            $sql->bindValue(":c", $cep);
            $sql->bindValue(":e", $est_cid);
            $sql->bindValue(":b", $bairro);
            $sql->bindValue(":r", $rua);
            $sql->bindValue(":l", $complemento);
            $sql->execute();

            header('Location: dashboard?msg=true');
        }
        else
        {
            header('Location: cadastro?msg=true');
        }
    }
}
