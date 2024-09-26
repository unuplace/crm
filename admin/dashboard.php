<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
$totalSales = get_total_sales($pdo);
$totalClients = get_total_clients($pdo);
$totalProjects = get_total_projects($pdo);
$monthlySales = get_monthly_sales($pdo);
$topEmployees = get_top_employees($pdo);

// Add these functions to functions.php
function get_total_sales($pdo) {
    $stmt = $pdo->query("SELECT SUM(amount) as total FROM sales");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

function get_total_clients($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM potential_clients");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

function get_total_projects($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM projects");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

function get_monthly_sales($pdo) {
    $stmt = $pdo->query("SELECT DATE_FORMAT(sale_date, '%Y-%m') as month, SUM(amount) as total 
                         FROM sales 
                         GROUP BY DATE_FORMAT(sale_date, '%Y-%m') 
                         ORDER BY month DESC 
                         LIMIT 12");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_top_employees($pdo) {
    $stmt = $pdo->query("SELECT u.full_name, SUM(s.amount) as total_sales 
                         FROM users u 
                         JOIN sales s ON u.id = s.employee_id 
                         GROUP BY u.id 
                         ORDER BY total_sales DESC 
                         LIMIT 5");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
check_login();
if (!is_admin()) {
    header("Location: ../employee/dashboard.php");
    exit();
}

$user_data = get_user_data($pdo, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الإدارة - شركة التلاد للتطوير العقاري</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/topnav.php'; ?>
    <?php include '../includes/sidebar.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                لوحة التحكم
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="projects.php">
                                إدارة المشاريع
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="team.php">
                                إدارة الفريق
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reports.php">
                                التقارير
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="potential_clients.php">
                                العملاء المحتملين
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">لوحة تحكم الإدارة</h1>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">إجمالي المبيعات</h5>
                                <p class="card-text" id="total-sales">جاري التحميل...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">إجمالي الحجوزات</h5>
                                <p class="card-text" id="total-reservations">جاري التحميل...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">إجمالي العملاء المحتملين</h5>
                                <p class="card-text" id="total-potential-clients">جاري التحميل...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">أداء المشاريع</h5>
                                <canvas id="projects-performance-chart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">أداء الموظفين</h5>
                                <canvas id="employees-performance-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/admin-dashboard.js"></script>
</body>
</html>