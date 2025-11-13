<?php

class CategoryController
{
public function index()
{
    $categoryId = $_GET['id'] ?? null;
    $categoryModel = new Category();
    $categories = $categoryModel->getList();

    if ($categoryId) {
        // Lấy thông tin danh mục đã click
        $currentCategory = $categoryModel->getOne($categoryId);

        // Click vào danh mục con → show tour
        $tourModel = new Tour();
        $allTours = $tourModel->getAll();

        // Lọc tour theo category_id
        $listData = array_filter($allTours, fn($t) => $t['category_id'] == $categoryId);

        // Gán title là tên danh mục
        $title = "Danh sách tour: " . ($currentCategory['name'] ?? 'Không xác định');
        $showTours = true;
    } else {
        // Chưa click danh mục → show danh mục
        $listData = $categories;
        $title = "Danh sách danh mục";
        $showTours = false;
    }

    $view = "admin/list-category";  // chỉ định view
    require_once PATH_VIEW . 'main.php'; // giữ layout
}
  public function create()
  {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $category = new Category();
      $category->insert($_POST['name']);
      header("Location:" . BASE_URL . "?action=admin-list-categories");
    } else {
      $title = "Trang thêm mới tour";
      $view = "admin/create-category";
      require_once PATH_VIEW . 'main.php';
    }
  }

  public function update()
  {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $category = new Category();
      $category->update($_GET['id'], $_POST['name']);
      $_SESSION['success'][] = "Chỉnh sửa thành công";
      header("Location:" . BASE_URL . "?action=admin-list-categories");
    } else {
      $category = new Category();
      $data = $category->getOne($_GET['id']);

      $title = "Trang chỉnh sửa danh mục";
      $view = "admin/update-category";
      require_once PATH_VIEW . 'main.php';
    }
  }

  public function delete()
  {
    $category = new Category();
    $category->delete($_GET['id']);
    $_SESSION['success'][] = "Xóa thành công";
    header("Location:" . BASE_URL . "?action=admin-list-categories");
  }
}
