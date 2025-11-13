<div class="container">
    <h2>Sửa Tour: <?= $data['name'] ?></h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Tên Tour</label>
            <input type="text" name="name" class="form-control" value="<?= $data['name'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Danh mục</label>
            <select name="category_id" class="form-control" required>
                <?php foreach($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id']==$data['category_id'] ? 'selected' : '' ?>>
                    <?= $c['name'] ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Giá cơ bản</label>
            <input type="number" name="base_price" class="form-control" value="<?= $data['base_price'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Thời lượng (ngày)</label>
            <input type="number" name="duration" class="form-control" value="<?= $data['duration'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control"><?= $data['description'] ?></textarea>
        </div>

        <div class="mb-3">
            <label>Ngày bắt đầu</label>
            <input type="date" name="start_date" class="form-control" value="<?= $data['start_date'] ?>">
        </div>

        <div class="mb-3">
            <label>Ngày kết thúc</label>
            <input type="date" name="end_date" class="form-control" value="<?= $data['end_date'] ?>">
        </div>

        <!-- Lịch trình -->
        <h5>Lịch trình</h5>
        <?php for($i=1;$i<=5;$i++): 
            $activity = $data['itineraries'][$i-1]['activity'] ?? '';
        ?>
        <div class="mb-2">
            <label>Ngày <?= $i ?></label>
            <input type="text" name="itinerary[<?= $i ?>]" class="form-control" value="<?= $activity ?>">
        </div>
        <?php endfor; ?>

        <!-- Nhà cung cấp -->
        <h5>Nhà cung cấp</h5>
        <?php foreach($data['suppliers'] as $s): ?>
        <div class="row mb-2">
            <div class="col">
                <input type="text" name="supplier_name[]" class="form-control" value="<?= $s['name'] ?>">
            </div>
            <div class="col">
                <input type="text" name="supplier_type[]" class="form-control" value="<?= $s['type'] ?>">
            </div>
            <div class="col">
                <input type="text" name="supplier_contact[]" class="form-control" value="<?= $s['contact'] ?>">
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Chính sách -->
        <h5>Chính sách</h5>
        <?php foreach($data['policies'] as $p): ?>
        <div class="row mb-2">
            <div class="col">
                <input type="text" name="policy_type[]" class="form-control" value="<?= $p['type'] ?>">
            </div>
            <div class="col">
                <input type="text" name="policy_desc[]" class="form-control" value="<?= $p['description'] ?>">
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Hình ảnh -->
        <div class="mb-3">
            <label>Hình ảnh hiện có</label>
            <div class="row">
                <?php foreach($data['images'] as $img): ?>
                <div class="col-2 mb-2">
                    <img src="<?= $img['filepath'] ?>" class="img-fluid">
                </div>
                <?php endforeach; ?>
            </div>
            <label>Thêm hình ảnh mới</label>
            <input type="file" name="images[]" multiple class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật Tour</button>
    </form>
</div>
