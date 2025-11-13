<?php

class TourController
{
    // Hiển thị danh sách tour
    public function index()
    {
        $tour = new Tour();
        $listData = $tour->getAll();

        // Lấy thêm các bảng phụ
        foreach ($listData as &$t) {
            $t['itineraries'] = $tour->getItineraries($t['id']);
            $t['suppliers'] = $tour->getSuppliers($t['id']);
            $t['images'] = $tour->getImages($t['id']);
            $t['policies'] = $tour->getPolicies($t['id']);
        }

        $title = "Quản lý Tour";
        $view = "admin/list-tour";
        require_once PATH_VIEW . 'main.php';
    }

    // Thêm tour mới
    public function create()
    {
        $tour = new Tour();
        $category = new Category();
        $categories = $category->getList();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Thêm tour cơ bản
            $tour_id = $tour->insert($_POST);

            // 2. Lịch trình
            if(isset($_POST['itinerary'])) {
                foreach($_POST['itinerary'] as $day => $activity) {
                    if($activity != '') {
                        $tour->addItinerary($tour_id, $day, $activity);
                    }
                }
            }

            // 3. Nhà cung cấp
            if(isset($_POST['supplier_name'])) {
                foreach($_POST['supplier_name'] as $i => $name) {
                    if($name != '') {
                        $tour->addSupplier($tour_id, $name, $_POST['supplier_type'][$i], $_POST['supplier_contact'][$i]);
                    }
                }
            }

            // 4. Chính sách
            if(isset($_POST['policy_type'])) {
                foreach($_POST['policy_type'] as $i => $type) {
                    if($_POST['policy_desc'][$i] != '') {
                        $tour->addPolicy($tour_id, $type, $_POST['policy_desc'][$i]);
                    }
                }
            }

            // 5. Hình ảnh
            if (!empty($_FILES['images']['name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $i => $tmp_name) {
                    $filename = 'uploads/' . time() . '_' . $_FILES['images']['name'][$i];
                    move_uploaded_file($tmp_name, $filename);
                    $tour->addImage($tour_id, $filename);
                }
            }

            header("Location:" . BASE_URL . "?action=admin-list-tour");
        } else {
            $title = "Thêm Tour mới";
            $view = "admin/create-tour";
            require_once PATH_VIEW . 'main.php';
        }
    }

    // Sửa tour
    public function update()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) die("Không có ID tour");

        $tour = new Tour();
        $category = new Category();
        $categories = $category->getList();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour->update($id, $_POST);

            // Xóa các bảng phụ cũ
            foreach($tour->getItineraries($id) as $item) $tour->deleteItinerary($item['id']);
            foreach($tour->getSuppliers($id) as $item) $tour->deleteSupplier($item['id']);
            foreach($tour->getPolicies($id) as $item) $tour->deletePolicy($item['id']);
            foreach($tour->getImages($id) as $item) $tour->deleteImage($item['id']);

            // Thêm lại các bảng phụ từ form
            if(isset($_POST['itinerary'])) {
                foreach($_POST['itinerary'] as $day => $activity) {
                    if($activity != '') $tour->addItinerary($id, $day, $activity);
                }
            }
            if(isset($_POST['supplier_name'])) {
                foreach($_POST['supplier_name'] as $i => $name) {
                    if($name != '') $tour->addSupplier($id, $name, $_POST['supplier_type'][$i], $_POST['supplier_contact'][$i]);
                }
            }
            if(isset($_POST['policy_type'])) {
                foreach($_POST['policy_type'] as $i => $type) {
                    if($_POST['policy_desc'][$i] != '') $tour->addPolicy($id, $type, $_POST['policy_desc'][$i]);
                }
            }
            if (!empty($_FILES['images']['name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $i => $tmp_name) {
                    $filename = 'uploads/' . time() . '_' . $_FILES['images']['name'][$i];
                    move_uploaded_file($tmp_name, $filename);
                    $tour->addImage($id, $filename);
                }
            }

            header("Location:" . BASE_URL . "?action=admin-list-tour");
        } else {
            $data = $tour->getById($id);
            $data['itineraries'] = $tour->getItineraries($id);
            $data['suppliers'] = $tour->getSuppliers($id);
            $data['images'] = $tour->getImages($id);
            $data['policies'] = $tour->getPolicies($id);

            $title = "Sửa Tour";
            $view = "admin/update-tour";
            require_once PATH_VIEW . 'main.php';
        }
    }

    // Xóa tour
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $tour = new Tour();

            // Xóa cascade các bảng phụ
            foreach($tour->getItineraries($id) as $item) $tour->deleteItinerary($item['id']);
            foreach($tour->getSuppliers($id) as $item) $tour->deleteSupplier($item['id']);
            foreach($tour->getPolicies($id) as $item) $tour->deletePolicy($item['id']);
            foreach($tour->getImages($id) as $item) $tour->deleteImage($item['id']);

            // Xóa tour cơ bản
            $tour->delete($id);
        }
        header("Location:" . BASE_URL . "?action=admin-list-tour");
    }
}
