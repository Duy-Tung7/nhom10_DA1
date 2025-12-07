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
        $stmt = $this->pdo->prepare("SELECT * FROM tours");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tours WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tours (category_id, name, base_price, duration, description, start_date, end_date, created_at, updated_at)
            VALUES (:category_id, :name, :base_price, :duration, :description, :start_date, :end_date, NOW(), NOW())
        ");
        $stmt->execute([
            ':category_id' => $data['category_id'],
            ':name' => $data['name'],
            ':base_price' => $data['base_price'],
            ':duration' => $data['duration'],
            ':description' => $data['description'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE tours SET category_id=:category_id, name=:name, base_price=:base_price, duration=:duration,
            description=:description, start_date=:start_date, end_date=:end_date, updated_at=NOW()
            WHERE id=:id
        ");
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
        $stmt = $this->pdo->prepare("DELETE FROM tours WHERE id=:id");
        $stmt->execute([':id' => $id]);
    }

    // -------------------- Lịch trình --------------------
    public function getItineraries($tour_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tour_itineraries WHERE tour_id=:tour_id ORDER BY day_number");
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addItinerary($tour_id, $day_number, $activities)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tour_itineraries (tour_id, day_number, activities)
            VALUES (:tour_id, :day_number, :activities)
        ");
        $stmt->execute([
            ':tour_id' => $tour_id,
            ':day_number' => $day_number,
            ':activities' => $activities
        ]);
    }

    public function deleteItinerary($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tour_itineraries WHERE id=:id");
        $stmt->execute([':id' => $id]);
    }

    // -------------------- Nhà cung cấp --------------------
    public function getSuppliers($tour_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tour_suppliers WHERE tour_id=:tour_id");
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addSupplier($tour_id, $name, $supplier_type = null, $contact_info = null)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM suppliers WHERE name=?");
        $stmt->execute([$name]);
        $supplier_id = $stmt->fetchColumn();

        if (!$supplier_id) {
            $stmt = $this->pdo->prepare("INSERT INTO suppliers (name, contact_info) VALUES (?, ?)");
            $stmt->execute([$name, $contact_info]);
            $supplier_id = $this->pdo->lastInsertId();
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO tour_suppliers (tour_id, supplier_id, name, supplier_type, contact_info)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$tour_id, $supplier_id, $name, $supplier_type, $contact_info]);
    }

    public function deleteSupplier($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tour_suppliers WHERE id=:id");
        $stmt->execute([':id' => $id]);
    }

    // -------------------- Chính sách --------------------
    public function getPolicies($tour_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tour_policies WHERE tour_id=:tour_id");
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPolicy($tour_id, $policy_type, $description)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tour_policies (tour_id, policy_type, description)
            VALUES (:tour_id, :policy_type, :description)
        ");
        $stmt->execute([
            ':tour_id' => $tour_id,
            ':policy_type' => $policy_type,
            ':description' => $description
        ]);
    }

    public function deletePolicy($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tour_policies WHERE id=:id");
        $stmt->execute([':id' => $id]);
    }

    // -------------------- Hình ảnh --------------------
    public function getImages($tour_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tour_images WHERE tour_id=:tour_id");
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addImage($tour_id, $filepath)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tour_images (tour_id, filepath, created_at)
            VALUES (:tour_id, :filepath, NOW())
        ");
        $stmt->execute([
            ':tour_id' => $tour_id,
            ':filepath' => $filepath
        ]);
    }

    public function deleteImage($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tour_images WHERE id=:id");
        $stmt->execute([':id' => $id]);
    }
}
