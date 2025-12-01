<h2>Thêm mới Hướng Dẫn Viên</h2>
<form action="index.php?url=store-guide" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label>Họ tên:</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>Ngày sinh:</label>
        <input type="date" name="dob" class="form-control">
    </div>

    <div class="form-group">
        <label>Ảnh đại diện:</label>
        <input type="file" name="image" class="form-control">
    </div>

    <div class="form-group">
        <label>Điện thoại:</label>
        <input type="text" name="phone" class="form-control">
    </div>

    <div class="form-group">
        <label>Loại HDV:</label>
        <select name="type" class="form-control">
            <option value="0">Nội địa</option>
            <option value="1">Quốc tế</option>
        </select>
    </div>

    <div class="form-group">
        <label>Ngôn ngữ (VD: Anh, Nhật):</label>
        <input type="text" name="languages" class="form-control">
    </div>

    <div class="form-group">
        <label>Chứng chỉ chuyên môn:</label>
        <textarea name="certificates" class="form-control"></textarea>
    </div>

    <div class="form-group">
        <label>Kinh nghiệm / Lịch sử dẫn tour:</label>
        <textarea name="bio" class="form-control"></textarea>
    </div>

    <div class="form-group">
        <label>Tình trạng sức khỏe:</label>
        <input type="text" name="health_status" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Lưu hồ sơ</button>
</form>