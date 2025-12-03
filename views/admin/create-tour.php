<div class="container">

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Tên Tour</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Danh mục</label>
            <select name="category_id" class="form-control" required>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Giá cơ bản</label>
            <input type="number" name="base_price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Thời lượng (ngày)</label>
            <input type="text" name="duration" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <!-- Lịch trình -->
        <h5>Lịch trình</h5>
        <?php for ($i = 1; $i <= 2; $i++): ?>
            <div class="mb-2">
                <label>Ngày <?= $i ?></label>
                <input type="text" name="itinerary[<?= $i ?>]" class="form-control" placeholder="Hoạt động ngày <?= $i ?>">
            </div>
        <?php endfor; ?>

        <!-- Nhà cung cấp -->
        <h5>Nhà cung cấp</h5>
        <div id="suppliers">
            <div class="row mb-2">
                <div class="col">
                    <input type="text" name="supplier_name[]" class="form-control" placeholder="Tên nhà cung cấp">
                </div>
                <div class="col">
                    <input type="text" name="supplier_type[]" class="form-control" placeholder="Loại">
                </div>
                <div class="col">
                    <input type="text" name="supplier_contact[]" class="form-control" placeholder="Liên hệ">
                </div>
            </div>
        </div>

        <!-- Chính sách -->
        <!-- Chính sách -->
        <h5>Chính sách</h5>
        <div id="policies">
            <div class="row mb-2">
                <div class="col">
                    <input type="text" name="policy_type[]" class="form-control" placeholder="Loại chính sách">
                </div>
                <div class="col">
                    <input type="text" name="policy_desc[]" class="form-control" placeholder="Nội dung">
                </div>
            </div>
        </div>

        <!-- Nút xem hoặc tải file chính sách mẫu -->
        <a href="/nhom10_Da1/assets/files/chinhSach.pdf" target="_blank">Xem chính sách mẫu</a>
            


        <!-- Hình ảnh -->
        <div class="mb-3">
            <label>Hình ảnh</label>
            <input type="file" name="images[]" multiple class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Thêm Tour</button>
        <a href="<?= BASE_URL ?>?action=admin-sidebar" class="btn btn-success btn-sm">Quay lại</a>
    </form>
</div>