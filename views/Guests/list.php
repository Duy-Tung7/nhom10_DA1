<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách khách Tour #<?= $tour_id ?></title>
</head>
<body>

    <h2>Danh sách khách của tour #<?= $tour_id ?></h2>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr style="background: #f0f0f0;">
                <th>ID</th>
                <th>Tên khách</th>
                <th>SĐT</th>
                <th>Email</th>
                <th>Trạng thái</th>
                <th>Ghi chú</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($guests)) : ?>
                <tr>
                    <td colspan="6" style="text-align:center">Không có khách nào!</td>
                </tr>
            <?php else: ?>

                <?php foreach ($guests as $g): ?>
                <tr>
                    <td><?= $g['id'] ?></td>
                    <td><?= $g['name'] ?></td>
                    <td><?= $g['phone'] ?></td>
                    <td><?= $g['email'] ?></td>
                    <td>
                        <?php if ($g['status'] == 1): ?>
                            <span style="color: green; font-weight:bold;">Đã check-in</span>
                        <?php else: ?>
                            <span style="color: red;">Chưa đến</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $g['note'] ?></td>
                </tr>
                <?php endforeach; ?>

            <?php endif; ?>
        </tbody>
    </table>
    
</body>
</html>