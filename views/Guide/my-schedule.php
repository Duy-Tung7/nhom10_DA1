<div class="container mt-4">
    <h3 class="text-center text-primary">Lịch dẫn tour của bạn</h3>
    
    <?php if(empty($tours)): ?>
        <div class="alert alert-warning">Bạn chưa có lịch dẫn nào.</div>
    <?php else: ?>
        <div class="list-group mt-3">
            <?php foreach($tours as $t): ?>
                <div class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= $t['name'] ?></h5>
                        <small>
                            <?= date('d/m', strtotime($t['start_date'])) ?> 
                            - 
                            <?= date('d/m', strtotime($t['end_date'])) ?>
                        </small>
                    </div>
                    <p class="mb-1">Thời lượng: <?= $t['duration'] ?> ngày</p>
                    
                    <a href="index.php?act=hdv-xem-khach&tour_id=<?= $t['id'] ?>" class="btn btn-sm btn-info mt-2">
                        📋 Danh sách khách
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>