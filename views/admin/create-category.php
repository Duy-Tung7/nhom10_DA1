<div class="container">
    <div class="row">
    <div class="col-3">
        <?php include "views/admin/sidebar.php"; ?>
    </div>
    <div class="col-9">
        <!-- Nội dùng chính-->
         <form action="<?= BASE_URL?>?action=admin-create-categories" method="post">
            <div class="mb-4">
                <label for="">Tên danh mục</label>
                <input type="text" class="form-control" name="name">
            </div>
            <button class="btn btn-primary">Thêm mới</button>
           <a href="<?= BASE_URL?>?action=admin-list-categories" class="btn btn-success">Quay lại</a>
         </form>
    </div>
    </div>
</div>