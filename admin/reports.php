<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

check_login();
if (!is_admin()) {
    header("Location: ../employee/dashboard.php");
    exit();
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;

$filters = [
    'employee_id' => isset($_GET['employee_id']) ? $_GET['employee_id'] : null,
    'project_id' => isset($_GET['project_id']) ? $_GET['project_id'] : null,
    'start_date' => isset($_GET['start_date']) ? $_GET['start_date'] : null,
    'end_date' => isset($_GET['end_date']) ? $_GET['end_date'] : null,
];

$visits = get_recent_visits($pdo, $page, $per_page, $filters);
$total_visits = get_total_visits_count($pdo, $filters);
$total_pages = ceil($total_visits / $per_page);

$employees = get_all_employees($pdo);
$projects = get_all_projects($pdo);


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
$employees = get_all_employees($pdo);


// الحصول على الأنشطة مع تطبيق التصفية والترقيم
$visits = get_recent_visits($pdo, $page, $per_page, $filters);
$total_visits = get_total_visits_count($pdo, $filters);


// الحصول على عدد العملاء المحتملين للموظف المحدد
$total_clients = get_total_potential_clients_count($pdo, $filters['employee_id']);


// الحصول على عدد العملاء المحتملين
$total_clients = get_total_potential_clients_count($pdo);

// تحديد الفترة الزمنية للتقرير (افتراضيًا الشهر الحالي)
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');


// الحصول على جميع العملاء
// $customers = get_all_customers($pdo);
$customers = get_recent_customers($pdo, $page, $per_page, $filters);
// $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
// $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// $employee_reports = get_employee_reports($pdo, $start_date, $end_date);
// $project_reports = get_project_reports($pdo, $start_date, $end_date);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير - شركة التلاد للتطوير العقاري</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/topnav.php'; ?>
    <!-- <?php include '../includes/sidebar.php'; ?> -->
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <!-- Sidebar content (same as in dashboard.php) -->
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">التقارير</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                    

                    </div>

                </div>


                <form class="mb-4" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <select name="employee_id" class="form-select">
                        <option value="">جميع الموظفين</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?php echo $employee['id']; ?>" <?php echo $filters['employee_id'] == $employee['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($employee['full_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="project_id" class="form-select">
                        <option value="">جميع المشاريع</option>
                        <?php foreach ($projects as $project): ?>
                            <option value="<?php echo $project['id']; ?>" <?php echo $filters['project_id'] == $project['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($project['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="start_date" class="form-control" value="<?php echo $filters['start_date']; ?>" placeholder="تاريخ البداية">
                </div>
                <div class="col-md-2">
                    <input type="date" name="end_date" class="form-control" value="<?php echo $filters['end_date']; ?>" placeholder="تاريخ النهاية">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">تصفية</button>
                </div>
            </div>
        </form>

                <h3>أداء الموظفين</h3>

                    <div class="container mt-4">
        
                    <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>الاتصالات (الهدف/الإنجاز)</th>
                        <th>المبيعات (الهدف/الإنجاز)</th>
                        <th>الزيارات (الهدف/الإنجاز)</th>
                        <th>نسبة الإنجاز (الاتصالات)</th>
                        <th>نسبة الإنجاز (المبيعات)</th>
                        <th>نسبة الإنجاز (الزيارات)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): 
                        $progress = get_employee_progress($pdo, $employee['id'], $start_date, $end_date);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($employee['full_name']); ?></td>
                        <td><?php echo $progress['call_target'] . ' / ' . $progress['total_calls']; ?></td>
                        <td><?php echo $progress['sales_target'] . ' / ' . $progress['total_sales']; ?></td>
                        <td><?php echo $progress['visit_target'] . ' / ' . $progress['total_visits']; ?></td>
                        <td><?php echo number_format($progress['call_progress'], 2) . '%'; ?></td>
                        <td><?php echo number_format($progress['sales_progress'], 2) . '%'; ?></td>
                        <td><?php echo number_format($progress['visit_progress'], 2) . '%'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


                </div>

                <h3 class="mt-5">تقارير المشاريع</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>اسم المشروع</th>
                                <th>المبيعات</th>
                                <th>الحجوزات</th>
                                <th>نسبة الإنجاز</th>
                                <th>المتبقي للهدف</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($project_reports as $report): ?>
                            <tr>
                                <td><?php echo $report['name']; ?></td>
                                <td><?php echo $report['sales']; ?></td>
                                <td><?php echo $report['reservations']; ?></td>
                                <td><?php echo $report['progress']; ?>%</td>
                                <td><?php echo $report['remaining_target']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>



                    <div class="container mt-4">
        <h2>تقارير الزيارات</h2>
        
        
        <!-- العداد -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>عدد العملاء المحتملين الكلي: <span class="badge bg-primary"><?php echo $total_clients; ?></span></h5>
            </div>
            <div class="col-md-6">
                <h5>عدد الزيارات: <span class="badge bg-primary"><?php echo $total_visits; ?></span></h5>
            </div>
            <div class="col-md-6">
                <h5>عدد العملاء المحتملين للموظف: <span class="badge bg-primary"><?php echo $total_activities; ?></span></h5>
            </div>
        </div>
<!-- نهاية العداد -->

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


    <div class="container mt-4">
        <h2>سجل النشاط</h2>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>العميل المحتمل</th>
                        <th>تاريخ التواصل</th>
                        <th>الحالة الجديدة</th>
                        <th>الملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(get_employee_name($pdo, $activity['employee_id'])); ?></td>
                        <td><?php echo htmlspecialchars(get_client_name($pdo, $activity['potential_client_id'])); ?></td>
                        <td><?php echo htmlspecialchars($activity['communication_date']); ?></td>
                        <td><?php echo htmlspecialchars($activity['new_status']); ?></td>
                        <td><?php echo htmlspecialchars($activity['notes']); ?></td>
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

<!-- العملاء -->

<div class="container mt-4">
        <h2>تقارير العملاء</h2>
        
        <div class="table-responsive mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الالتزام الشهري</th>
                        <th>البنك</th>
                        <th>القطاع</th>
                        <th>الموظف المعين</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                        <td><?php echo htmlspecialchars($customer['monthly_commitment']); ?></td>
                        <td><?php echo htmlspecialchars($customer['bank']); ?></td>
                        <td><?php echo htmlspecialchars($customer['sector']); ?></td>
                        <td><?php echo htmlspecialchars(get_employee_name($pdo, $customer['assigned_to'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
    <script src="../assets/js/reports.js"></script>
</body>
</html>