<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// التحقق من تسجيل الدخول
// session_start();
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
//     header('Location: /telad/auth/login.php');
//     exit();
// }

$start_date = date('Y-m-d', strtotime('-30 days')); // آخر 30 يوم
$end_date = date('Y-m-d');
$stats = get_employee_statistics($pdo, $_SESSION['user_id'], $start_date, $end_date);


check_login();
if (is_admin()) {
    header("Location: ../admin/dashboard.php");
    exit();
}

$user_data = get_user_data($pdo, $_SESSION['user_id']);
// $employee_stats = get_employee_statistics($pdo, $_SESSION['user_id'], date('Y-m-d'));
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الموظف - شركة التلاد للتطوير العقاري</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/employee_topnav.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <!-- Sidebar content -->
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">لوحة تحكم الموظف</h1>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">معلومات الموظف</h5>
                                <p>الاسم: <?php echo $user_data['full_name']; ?></p>
                                <p>المسمى الوظيفي: <?php echo $user_data['role']; ?></p>
                                <p>رقم الجوال: <?php echo $user_data['phone']; ?></p>
                                <p>البريد الإلكتروني: <?php echo $user_data['email']; ?></p>
                                <p>المشروع: <?php echo get_project_name($pdo, $user_data['project_id']); ?></p>
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">تغيير كلمة المرور</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">إحصائيات اليوم</h5>
                                    <form id="dailyStatsForm">
                                    <div class="mb-3">
                                        <label for="unitsSold" class="form-label">عدد الوحدات المباعة</label>
                                        <input type="number" class="form-control" id="unitsSold" name="units_sold" required min="0">
                                    </div>
                                    <div class="mb-3">
                                        <label for="reservations" class="form-label">عدد الحجوزات</label>
                                        <input type="number" class="form-control" id="reservations" name="reservations" required min="0">
                                    </div>
                                    <div class="mb-3">
                                        <label for="visits" class="form-label">عدد الزيارات</label>
                                        <input type="number" class="form-control" id="visits" name="visits" required min="0">
                                    </div>
                                    <button type="submit" class="btn btn-primary">حفظ</button>
                                    </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">أداء المبيعات الشهري</h5>
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">أداء الاتصالات الشهري</h5>
                                <canvas id="callsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">تغيير كلمة المرور</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">كلمة المرور الحالية</label>
                            <input type="password" class="form-control" id="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" class="form-control" id="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" class="form-control" id="confirmPassword" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" id="changePasswordBtn">تغيير كلمة المرور</button>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <?php
$start_date = date('Y-m-d', strtotime('-30 days')); // مثال: آخر 30 يوم
$end_date = date('Y-m-d');
$stats = get_employee_statistics($pdo, $_SESSION['user_id'], $start_date, $end_date);
?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/employee-dashboard.js"></script>
</body>
</html>