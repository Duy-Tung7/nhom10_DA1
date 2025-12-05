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
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $data['category_id'] ? 'selected' : '' ?>>
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
            <input type="text" name="duration" class="form-control" value="<?= $data['duration'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control"><?= $data['description'] ?></textarea>
        </div>

        <!-- Lịch trình -->
        <?php
        // Tạo map theo số ngày
        $itineraryMap = [];
        if (!empty($data['itineraries'])) {
            foreach ($data['itineraries'] as $item) {
                // day_number = 1,2,3,4,5
                $itineraryMap[$item['day_number']] = $item['activities'];
            }
        }
        ?>
        <h5>Lịch trình</h5>

        <?php
        // Tạo map theo số ngày
        $itineraryMap = [];
        if (!empty($data['itineraries'])) {
            foreach ($data['itineraries'] as $item) {
                $itineraryMap[$item['day_number']] = $item['activities'];
            }
        }

        // Lấy ngày cuối cùng
        $maxDay = !empty($data['itineraries']) ? max(array_column($data['itineraries'], 'day_number')) : 1;
        ?>

        <div id="itineraryWrapper">
            <?php for ($i = 1; $i <= $maxDay; $i++): ?>
                <div class="mb-2">
                    <label>Ngày <?= $i ?></label>
                    <input type="text" name="itinerary[<?= $i ?>]" class="form-control"
                        value="<?= $itineraryMap[$i] ?? '' ?>"
                        placeholder="Hoạt động ngày <?= $i ?>">
                </div>
            <?php endfor; ?>
        </div>

        <button type="button" class="btn btn-success btn-sm" id="addDayBtn">+ Thêm ngày</button>

        <script>
            let day = <?= $maxDay ?>;

            document.getElementById("addDayBtn").addEventListener("click", function() {
                day++;
                const div = document.createElement("div");
                div.classList.add("mb-2");
                div.innerHTML = `
        <label>Ngày ${day}</label>
        <input type="text" name="itinerary[${day}]" class="form-control"
               placeholder="Hoạt động ngày ${day}">
    `;
                document.getElementById("itineraryWrapper").appendChild(div);
            });
        </script>
        <!-- Chính sách -->
        <h5>Chính sách</h5>
        <?php foreach (($data['policies'] ?? []) as $p): ?>
            <?php
            $pType = $p['policy_type'] ?? ($p['type'] ?? '');
            $pDesc = $p['policy_description'] ?? ($p['description'] ?? '');
            ?>
            <div class="row mb-2">
                <div class="col">
                    <input type="text" name="policy_type[]" class="form-control" value="<?= $pType ?>">
                </div>
                <div class="col">
                    <input type="text" name="policy_desc[]" class="form-control" value="<?= $pDesc ?>">
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Hình ảnh -->
        <div class="mb-3">
            <label>Hình ảnh hiện có</label>
            <div class="row">
                <?php foreach (($data['images'] ?? []) as $img): ?>
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