<?php
// $booking = booking info
// $customers = danh sách tất cả khách
// $assigned = danh sách khách đã gán
?>

<h2>Gán khách hàng cho booking #<?= $booking['id'] ?></h2>

<form id="assignCustomersForm">
    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">

    <label>Chọn khách hàng:</label><br>
    <?php foreach ($customers as $customer): 
        $checked = in_array($customer['id'], array_column($assigned, 'id')) ? 'checked' : '';
    ?>
        <input type="checkbox" name="customer_ids[]" value="<?= $customer['id'] ?>" <?= $checked ?>>
        <?= htmlspecialchars($customer['name']) ?> (<?= htmlspecialchars($customer['phone']) ?>)<br>
    <?php endforeach; ?>

    <button type="submit">Gán khách hàng</button>
</form>

<div id="assignCustomersMessage"></div>

<script>
document.getElementById('assignCustomersForm').addEventListener('submit', function(e){
    e.preventDefault();

    let form = e.target;
    let formData = new FormData(form);

    fetch('index.php?action=assign-customers', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        let msg = document.getElementById('assignCustomersMessage');
        msg.innerText = data.message;
        msg.style.color = data.success ? 'green' : 'red';
    })
    .catch(err => {
        console.error(err);
    });
});
</script>
