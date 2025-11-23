<?php


class User
{
    protected $pdo;

    public function __construct()
    {
        $database = new BaseModel();
        $this->pdo = $database->getConnection();
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email =:email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":email" => $email
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function checkLogin($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }
        if ($user['password'] !== md5($password)) {
            return false;
        }
        return $user;
    }

    public function update($id,$name, $email, $password,  $role)
    {
        $sql = "
        UPDATE `users` SET `name`=:name,`email`=:email,`password`=:password,`role`=:role WHERE id=:id
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":id"=>$id,
            ":name" => $name,
            ":email" => $email,
            ":password" => md5($password),
            ":role" => intval($role),
        ]);
    }
    
}
