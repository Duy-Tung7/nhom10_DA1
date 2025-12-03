<div class="row">
    <div class="col-3">
        <?php include PATH_VIEW . "admin/sidebar.php"; ?>
    </div>

    <div class="col-9">
        <h3><?= $title ?></h3>
        <a href="<?= BASE_URL ?>?action=admin-list-tour" class="btn btn-secondary btn-sm mb-3">Quay lại danh sách</a>

        <h4><?= htmlspecialchars($data['name']) ?></h4>
        <p><strong>Giá:</strong> <?= number_format($data['base_price']) ?> VND</p>
        <p><strong>Thời lượng:</strong> <?= $data['duration'] ?> ngày</p>
        <p><strong>Mô tả:</strong> <?= nl2br(htmlspecialchars($data['description'])) ?></p>
        <hr>
        <h5>Lịch trình</h5>
        <?php if (!empty($data['itineraries'])): ?>
            <ul>
                <?php foreach ($data['itineraries'] as $itinerary): ?>
                    <li><strong>Ngày <?= $itinerary['day_number'] ?>:</strong> <?= htmlspecialchars($itinerary['activities']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Chưa có lịch trình.</p>
        <?php endif; ?>

        <hr>
        <h5>Nhà cung cấp</h5>
        <?php if (!empty($data['suppliers'])): ?>
            <ul>
                <?php foreach ($data['suppliers'] as $supplier): ?>
                    <li><?= htmlspecialchars($supplier['name']) ?> (<?= $supplier['supplier_type'] ?>) - <?= $supplier['contact_info'] ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Chưa có nhà cung cấp.</p>
        <?php endif; ?>

        <hr>
        <h5>Chính sách</h5>
        <?php if (!empty($data['policies'])): ?>
            <ul>
                <?php foreach ($data['policies'] as $policy): ?>
                    <li><strong><?= $policy['policy_type'] ?>:</strong> <?= htmlspecialchars($policy['description']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Chưa có chính sách.</p>
        <?php endif; ?>

        <hr>
        <h5>Hình ảnh</h5>
        <?php if (!empty($data['images'])): ?>
            <div class="row">
                <?php foreach ($data['images'] as $img): ?>
                    <div class="col-3 mb-3">
                        <img src="<?= BASE_URL . '/' . $img['filepath'] ?>" class="img-fluid img-thumbnail">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Chưa có hình ảnh.</p>
        <?php endif; ?>
    </div>
</div>