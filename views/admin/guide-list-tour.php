<h2>Danh sách tour được phân công</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tên tour</th>
            <th>Thời gian</th>
            <th>Chi tiết</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($tours as $tour): ?>
        <tr>
            <td><?= htmlspecialchars($tour['tour_name']) ?></td>
            <td><?= htmlspecialchars($tour['start_date']) ?> - <?= htmlspecialchars($tour['end_date']) ?></td>
            <td><a href="index.php?action=guide-tour-detail&tour_id=<?= $tour['tour_id'] ?>">Xem chi tiết</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
