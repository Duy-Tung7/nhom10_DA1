<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1470&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            min-height: 100vh;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.6); /* Nền trong suốt cho chữ nổi */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 30px;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 30px;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 10px 30px;
            font-size: 1rem;
        }

        .btn-danger:hover {
            background-color: #a71d2a;
        }
    </style>
</head>
<body>

<div class="container mt-5 text-center">
    <h1 class="mb-4">Chào mừng đến với Website Du Lịch!</h1>

    <?php if (isset($_SESSION['userLogin'])): ?>
        <?= htmlspecialchars($_SESSION['userLogin']['name']) ?>
        <a href="index.php?action=login" class="btn btn-danger">Đăng xuất</a>
    <?php else: ?>
        <a href="index.php?action=login" class="btn btn-primary">Đăng nhập</a>
    <?php endif; ?>
</div>

</body>
</html>
