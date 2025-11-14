<div class="container">
    <div class="row">
    <div class="col-3">
        <?php include "views/admin/sidebar.php"; ?>
    </div>
    <div class="col-9">
        <!-- Nội dùng chính-->
         <form action="<?= BASE_URL?>?action=admin-update-categories&id=<?= $data['id']?>" method="post">
            <div class="mb-4">
                <label for="">Tên danh mục</label>
                <input type="text" class="form-control" name="name" value="<?= $data['name']?>" >
            </div>
            <button class="btn btn-warning">Chỉnh suwa</button>
         </form>
    </div>
    </div>
</div>