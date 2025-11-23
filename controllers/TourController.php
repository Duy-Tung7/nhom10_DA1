<?php

class TourController
{
    private $tour;
    private $category;

    public function __construct()
    {
        $this->tour = new Tour();
        $this->category = new Category();
    }

    // Hiển thị danh sách tour
    public function index()
    {
        $listData = $this->tour->getAll();

        foreach ($listData as &$t) {
            $t['itineraries'] = $this->tour->getItineraries($t['id']);
            $t['suppliers']   = $this->tour->getSuppliers($t['id']);
            $t['images']      = $this->tour->getImages($t['id']);
            $t['policies']    = $this->tour->getPolicies($t['id']);
        }

        $title = "Quản lý Tour";
        $view  = "admin/list-tour";
        require_once PATH_VIEW . 'main.php';
    }

    // Thêm tour mới
    public function create()
    {
        $categories = $this->category->getList();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour_id = $this->tour->insert($_POST);

            $this->handleItineraries($tour_id, $_POST);
            $this->handleSuppliers($tour_id, $_POST);
            $this->handlePolicies($tour_id, $_POST);
            $this->handleImages($tour_id, $_FILES);

            header("Location:" . BASE_URL . "?action=admin-list-tour");
        } else {
            $title = "Thêm Tour mới";
            $view  = "admin/create-tour";
            require_once PATH_VIEW . 'main.php';
        }
    }

    // Sửa tour
    public function update()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) die("Không có ID tour");

        $categories = $this->category->getList();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->tour->update($id, $_POST);

            $this->deleteSubTables($id);

            $this->handleItineraries($id, $_POST);
            $this->handleSuppliers($id, $_POST);
            $this->handlePolicies($id, $_POST);
            $this->handleImages($id, $_FILES);

            header("Location:" . BASE_URL . "?action=admin-list-tour");
        } else {
            $data = $this->tour->getById($id);
            $data['itineraries'] = $this->tour->getItineraries($id);
            $data['suppliers']   = $this->tour->getSuppliers($id);
            $data['images']      = $this->tour->getImages($id);
            $data['policies']    = $this->tour->getPolicies($id);

            $title = "Sửa Tour";
            $view  = "admin/update-tour";
            require_once PATH_VIEW . 'main.php';
        }
    }

    // Xóa tour
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->deleteSubTables($id);
            $this->tour->delete($id);
        }
        header("Location:" . BASE_URL . "?action=admin-list-tour");
    }

    // Xử lý lịch trình
    private function handleItineraries($tour_id, $data)
    {
        if (!empty($data['itinerary'])) {
            foreach ($data['itinerary'] as $day => $activity) {
                if (trim($activity) !== '') {
                    $this->tour->addItinerary($tour_id, $day, $activity);
                }
            }
        }
    }

    // Xử lý nhà cung cấp
    private function handleSuppliers($tour_id, $data)
    {
        if (!empty($data['supplier_name'])) {
            foreach ($data['supplier_name'] as $i => $name) {
                if (trim($name) !== '') {
                    $this->tour->addSupplier(
                        $tour_id,
                        $name,
                        $data['supplier_type'][$i] ?? '',
                        $data['supplier_contact'][$i] ?? ''
                    );
                }
            }
        }
    }

    // Xử lý chính sách
    private function handlePolicies($tour_id, $data)
    {
        if (!empty($data['policy_type'])) {
            foreach ($data['policy_type'] as $i => $type) {
                $desc = $data['policy_desc'][$i] ?? '';
                if (trim($desc) !== '') {
                    $this->tour->addPolicy($tour_id, $type, $desc);
                }
            }
        }
    }

    // Xử lý hình ảnh
    private function handleImages($tour_id, $files)
    {
        $uploadDir = __DIR__ . '/../assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!empty($files['images']['name'][0])) {
            foreach ($files['images']['tmp_name'] as $i => $tmp_name) {
                $originalName = basename($files['images']['name'][$i]);
                $filename     = time() . '_' . $originalName;
                $targetPath   = $uploadDir . $filename;

                if (move_uploaded_file($tmp_name, $targetPath)) {
                    $this->tour->addImage($tour_id, 'assets/uploads/' . $filename);
                } else {
                    error_log("Upload failed for file: $originalName");
                }
            }
        }
    }

    // Xóa dữ liệu phụ
    private function deleteSubTables($tour_id)
    {
        foreach ($this->tour->getItineraries($tour_id) as $item) {
            $this->tour->deleteItinerary($item['id']);
        }
        foreach ($this->tour->getSuppliers($tour_id) as $item) {
            $this->tour->deleteSupplier($item['id']);
        }
        foreach ($this->tour->getPolicies($tour_id) as $item) {
            $this->tour->deletePolicy($item['id']);
        }
        foreach ($this->tour->getImages($tour_id) as $item) {
            $this->tour->deleteImage($item['id']);
        }
    }
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) die("Không có ID tour");

        $tour = new Tour();
        $data = $tour->getById($id);

        if (!$data) die("Tour không tồn tại");

        // Lấy dữ liệu phụ
        $data['itineraries'] = $tour->getItineraries($id);
        $data['suppliers']   = $tour->getSuppliers($id);
        $data['images']      = $tour->getImages($id);
        $data['policies']    = $tour->getPolicies($id);

        $title = "Chi tiết Tour";
        $view  = "admin/detail-tour";
        require_once PATH_VIEW . 'main.php';
    }
}
