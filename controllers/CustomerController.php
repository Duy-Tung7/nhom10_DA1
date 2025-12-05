<?php
class CustomerController
{
    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new Customer();
    }

    // Danh sách khách hàng
    public function index()
    {
        $customers = $this->customerModel->getAll();
        $title = "Trang danh sách khách hàng";
        $view = "admin/list";
        require_once PATH_VIEW . 'main.php';
    }

    // Chi tiết khách hàng
    public function detail($id)
    {
        $customer = $this->customerModel->getById($id);
        $title = "Trang chi tiết khách hàng";
        $view = "admin/detail";
        require_once PATH_VIEW . 'main.php';
    }

    // Thêm/Sửa/Xóa sẽ tương tự, dựa vào form gửi POST
}
