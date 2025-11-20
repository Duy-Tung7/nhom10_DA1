<h2>Chi tiết Tour</h2>

<h4>Lịch trình</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Ngày</th>
            <th>Hoạt động</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($itinerary as $item): ?>
        <tr>
            <td><?= $item['day_number'] ?></td>
            <td><?= htmlspecialchars($item['activities']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h4>Nhật ký / Ghi chú</h4>
<ul>
    <?php foreach($logs as $log): ?>
        <li>[<?= $log['created_at'] ?>] <?= htmlspecialchars($log['content']) ?></li>
    <?php endforeach; ?>
</ul>

<h4>Thêm ghi chú</h4>
<form method="post" action="index.php?action=guide-add-log">
    <input type="hidden" name="tour_id" value="<?= $_GET['tour_id'] ?>">
    <textarea name="content" class="form-control" required></textarea>
    <button type="submit" class="btn btn-primary mt-2">Thêm ghi chú</button>
</form>
