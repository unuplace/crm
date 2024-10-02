<?php
require_once '../includes/employee_functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header('Location: /crm/auth/login.php');
    exit();
}

// الكود الخاص بالإحصائيات هنا
$employee_id = $_SESSION['user_id'];
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// $stats = get_employee_statistics($pdo, $employee_id, $start_date, $end_date);


// الكود الجديد


// $employees = get_all_employees($pdo);
// $projects = get_all_projects($pdo);



// تحديد الصفحة الحالية وعدد العناصر في كل صفحة
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;

// جمع معايير التصفية
$filters = [
    'employee_id' => isset($_GET['employee_id']) ? $_GET['employee_id'] : null,
    'start_date' => isset($_GET['start_date']) ? $_GET['start_date'] : null,
    'end_date' => isset($_GET['end_date']) ? $_GET['end_date'] : null,
];

// الحصول على الأنشطة مع تطبيق التصفية والترقيم
$activities = get_recent_activities($pdo, $page, $per_page, $filters);
$total_activities = get_total_activities_count($pdo, $filters);
$total_pages = ceil($total_activities / $per_page);

// الحصول على قائمة الموظفين لقائمة التصفية
// $employees = get_all_employees($pdo);


// الحصول على الأنشطة مع تطبيق التصفية والترقيم
$visits = get_recent_visits($pdo, $page, $per_page, $filters, $employee_id);
$total_visits = get_total_visits_count($pdo, $filters);


// الحصول على عدد العملاء المحتملين للموظف المحدد
$total_clients = get_total_potential_clients_count($pdo, $filters['employee_id']);


// الحصول على عدد العملاء المحتملين
$total_clients = get_total_potential_clients_count($pdo);

$total_customers = get_total_customers_count($pdo, $employee_id);


?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إحصائيات الموظف</title>
    <!-- إضافة روابط CSS الخاصة بك هنا -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>
    <?php include '../includes/employee_topnav.php'; ?>
    
    <div class="container mt-4">
        <h2>إحصائيات الموظف</h2>
        
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">تاريخ البداية</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo $start_date; ?>">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">تاريخ النهاية</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo $end_date; ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">عرض الإحصائيات</button>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">إجمالي المبيعات</h5>
                        <p class="card-text"><?php echo $total_customers; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">إجمالي الحجوزات</h5>
                        <p class="card-text"><?php echo $stats['total_reservations'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">عدد الزيارات</h5>
                        <p class="card-text"><?php echo $total_visits; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">عدد الاتصالات</h5>
                        <p class="card-text"><?php echo $stats['total_communications'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <h2>سجل النشاط</h2>

                <!-- العداد -->
                <div class="row mb-4">
            <div class="col-md-6">
                <h5>عدد العملاء المحتملين الكلي: <span class="badge bg-primary"><?php echo $total_clients; ?></span></h5>
            </div>
            <div class="col-md-6">
                <h5>عدد العملاء المحتملين لديك: <span class="badge bg-primary"><?php echo $total_activities; ?></span></h5>
            </div>
        </div>
<!-- نهاية العداد -->

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>العميل المحتمل</th>
                    <th>تاريخ التواصل</th>
                    <th>الحالة الجديدة</th>
                    <th>الملاحظات</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $activities = get_recent_activities($pdo);
                foreach ($activities as $activity):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars(get_client_name($pdo, $activity['potential_client_id'])); ?></td>
                    <td><?php echo htmlspecialchars($activity['communication_date']); ?></td>
                    <td><?php echo htmlspecialchars($activity['new_status']); ?></td>
                    <td><?php echo htmlspecialchars($activity['notes']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<!-- سجل الزيارات -->
<h2>سجل الزيارات</h2>

<div class="table-responsive mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>تاريخ الزيارة</th>
                        <th>الشركة</th>
                        <th>القسم</th>
                        <th>المسؤول</th>
                        <th>الجوال</th>
                        <th>وصف الزيارة</th>
                        <th>التوصيات</th>
                        <th>المشروع</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($visits as $visit): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($visit['employee_name']); ?></td>
                        <td><?php echo htmlspecialchars($visit['visit_date']); ?></td>
                        <td><?php echo htmlspecialchars($visit['company_name']); ?></td>
                        <td><?php echo htmlspecialchars($visit['department']); ?></td>
                        <td><?php echo htmlspecialchars($visit['contact_name']); ?></td>
                        <td><?php echo htmlspecialchars($visit['contact_phone']); ?></td>
                        <td><?php echo htmlspecialchars($visit['description']); ?></td>
                        <td><?php echo htmlspecialchars($visit['recommendations']); ?></td>
                        <td><?php echo htmlspecialchars($visit['project_name']); ?></td>
                        <td><?php echo htmlspecialchars($visit['notes']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&<?php echo http_build_query($filters); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <!-- نهاية سجل الزيارات -->

    </div>

    <!-- إضافة روابط JavaScript الخاصة بك هنا -->

    <?php include '../includes/footer.php'; ?>

</body>
</html>

