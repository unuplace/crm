<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

check_login();
if (!is_admin()) {
    header("Location: ../employee/dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                add_employee($pdo, $_POST);
                break;
            case 'edit':
                edit_employee($pdo, $_POST);
                break;
            case 'delete':
                delete_employee($pdo, $_POST['id']);
                break;
        }
    }
}

$employees = get_all_employees($pdo);
$projects = get_all_projects($pdo);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الفريق - شركة التلاد للتطوير العقاري</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/topnav.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <!-- Sidebar content (same as in dashboard.php) -->
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">إدارة الفريق</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">إضافة موظف جديد</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>اسم الموظف</th>
                                <th>المنصب</th>
                                <th>المشروع</th>
                                <th>الهدف اليومي (الاتصالات)</th>
                                <th>الهدف الشهري (المبيعات)</th>
                                <th>الهدف الشهري (الزيارات)</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td><?php echo $employee['full_name']; ?></td>
                                <td><?php echo $employee['role']; ?></td>
                                <td><?php echo get_project_name($pdo, $employee['project_id']); ?></td>
                                <td><?php echo $employee['daily_call_target']; ?></td>
                                <td><?php echo $employee['monthly_sales_target']; ?></td>
                                <td><?php echo $employee['monthly_visit_target']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="editEmployee(<?php echo $employee['id']; ?>)">تعديل</button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteEmployee(<?php echo $employee['id']; ?>)">حذف</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">إضافة موظف جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEmployeeForm" method="POST">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="employeeName" class="form-label">اسم الموظف</label>
                            <input type="text" class="form-control" id="employeeName" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="employeeUsername" class="form-label">اسم المستخدم</label>
                            <input type="text" class="form-control" id="employeeUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="employeePassword" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control" id="employeePassword" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="employeeEmail" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="employeeEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="employeePhone" class="form-label">رقم الهاتف</label>
                            <input type="tel" class="form-control" id="employeePhone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="employeeRole" class="form-label">المنصب</label>
                            <select class="form-select" id="employeeRole" name="role" required>
                                <option value="employee">موظف</option>
                                <option value="admin">مدير</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="employeeProject" class="form-label">المشروع</label>
                            <select class="form-select" id="employeeProject" name="project_id" required>
                                <?php foreach ($projects as $project): ?>
                                <option value="<?php echo $project['id']; ?>"><?php echo $project['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="employeeDailyCallTarget" class="form-label">الهدف اليومي (الاتصالات)</label>
                            <input type="number" class="form-control" id="employeeDailyCallTarget" name="daily_call_target" required>
                        </div>
                        <div class="mb-3">
                            <label for="employeeMonthlySalesTarget" class="form-label">الهدف الشهري (المبيعات)</label>
                            <input type="number" class="form-control" id="employeeMonthlySalesTarget" name="monthly_sales_target" required>
                        </div>
                        <div class="mb-3">
                            <label for="employeeMonthlyVisitTarget" class="form-label">الهدف الشهري (الزيارات)</label>
                            <input type="number" class="form-control" id="employeeMonthlyVisitTarget" name="monthly_visit_target" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" form="addEmployeeForm" class="btn btn-primary">إضافة الموظف</button>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/team.js"></script>
</body>
</html>