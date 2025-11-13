<?php

class Category
{
    protected $pdo;

    public function __construct()
    {
        $database = new BaseModel();
        $this->pdo = $database->getConnection();
    }
    public function getList()
    {
        $sql = "SELECT * FROM `categories`";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($name)
    {
        $sql = "INSERT INTO `categories`( `name`) VALUES (:name)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' =>$name
        ]);
    }
    public function delete($id){
        $sql="DELETE FROM `categories` WHERE id= :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' =>$id
        ]);
    }

    public function getOne($id){
        $sql= "SELECT * FROM `categories` WHERE id= :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' =>$id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name){
        $sql = "UPDATE `categories` SET `name`= :name WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' =>$id,
            'name'=>$name
        ]);
    }
}
