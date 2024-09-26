<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = authenticate_user($pdo, $username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        if ($user['role'] == 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../employee/dashboard.php");
        }
        exit();
    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - شركة التلاد للتطوير العقاري</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="text-center">
    <main class="form-signin">
        <form method="POST">
            <img class="mb-4" src="../assets/img/logo.png" alt="شعار شركة التلاد" width="72" height="72">
            <h1 class="h3 mb-3 fw-normal">الرجاء تسجيل الدخول</h1>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="اسم المستخدم" required>
                <label for="username">اسم المستخدم</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="كلمة المرور" required>
                <label for="password">كلمة المرور</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">تسجيل الدخول</button>
            <p class="mt-5 mb-3 text-muted">&copy; 2023 شركة التلاد للتطوير العقاري</p>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>