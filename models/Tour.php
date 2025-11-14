<?php
class Tour
{
    protected $pdo;

    public function __construct()
    {
        $database = new BaseModel();
        $this->pdo = $database->getConnection();
    }

    // -------------------- Tour cơ bản --------------------
    public function getAll()
    {
        $sql = "SELECT * FROM tours";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM tours WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO tours (category_id, name, base_price, duration, description, start_date, end_date, created_at, updated_at)
                VALUES (:category_id, :name, :base_price, :duration, :description, :start_date, :end_date, NOW(), NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':category_id' => $data['category_id'],
            ':name' => $data['name'],
            ':base_price' => $data['base_price'],
            ':duration' => $data['duration'],
            ':description' => $data['description'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date']
        ]);
        return $this->pdo->lastInsertId(); // trả về ID tour mới
    }

    public function update($id, $data)
    {
        $sql = "UPDATE tours SET category_id=:category_id, name=:name, base_price=:base_price, duration=:duration,
                description=:description, start_date=:start_date, end_date=:end_date, updated_at=NOW() WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':category_id' => $data['category_id'],
            ':name' => $data['name'],
            ':base_price' => $data['base_price'],
            ':duration' => $data['duration'],
            ':description' => $data['description'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM tours WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    // -------------------- Lịch trình --------------------
    public function getItineraries($tour_id)
    {
        $sql = "SELECT * FROM tour_itineraries WHERE tour_id=:tour_id ORDER BY day_number";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addItinerary($tour_id, $day_number, $activities)
    {
        $sql = "INSERT INTO tour_itineraries (tour_id, day_number, activities) VALUES (:tour_id, :day_number, :activities)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':tour_id' => $tour_id,
            ':day_number' => $day_number,
            ':activities' => $activities
        ]);
    }

    public function deleteItinerary($id)
    {
        $sql = "DELETE FROM tour_itineraries WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    // -------------------- Nhà cung cấp --------------------
    public function getSuppliers($tour_id)
    {
        $sql = "SELECT * FROM tour_suppliers WHERE tour_id=:tour_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addSupplier($tour_id, $name, $supplier_id)
    {
        $sql = "INSERT INTO tour_suppliers (tour_id, name, supplier_id) 
            VALUES (:tour_id, :name, :supplier_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':tour_id' => $tour_id,
            ':name' => $name,
            ':supplier_id' => $supplier_id
        ]);
    }
    public function deleteSupplier($id)
    {
        $sql = "DELETE FROM tour_suppliers WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    // -------------------- Chính sách --------------------
    public function getPolicies($tour_id)
    {
        $sql = "SELECT * FROM tour_policies WHERE tour_id=:tour_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPolicy($tour_id, $type, $description)
    {
        $sql = "INSERT INTO tour_policies (tour_id, type, description) VALUES (:tour_id, :type, :description)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':tour_id' => $tour_id,
            ':type' => $type,
            ':description' => $description
        ]);
    }

    public function deletePolicy($id)
    {
        $sql = "DELETE FROM tour_policies WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    // -------------------- Hình ảnh --------------------
    public function getImages($tour_id)
    {
        $sql = "SELECT * FROM tour_images WHERE tour_id=:tour_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addImage($tour_id, $filepath)
    {
        $sql = "INSERT INTO tour_images (tour_id, filepath) VALUES (:tour_id, :filepath)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':tour_id' => $tour_id,
            ':filepath' => $filepath
        ]);
    }

    public function deleteImage($id)
    {
        $sql = "DELETE FROM tour_images WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
