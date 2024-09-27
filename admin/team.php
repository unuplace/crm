<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /telad/auth/login.php');
    exit();
}

// تعريف $employees في بداية الصفحة
$employees = get_all_employees($pdo);
$projects = get_all_projects($pdo);

// معالجة النموذج
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     if (isset($_POST['action'])) {
//         switch ($_POST['action']) {
//             case 'add':
//                 add_employee($pdo, $_POST);
//                 break;
//             case 'edit':
//                 edit_employee($pdo, $_POST);
//                 break;
//             case 'delete':
//                 delete_employee($pdo, $_POST['id']);
//                 break;
//         }
//     }
//     // إعادة توجيه لتجنب إعادة إرسال النموذج
//     header('Location: ' . $_SERVER['PHP_SELF']);
//     exit();
// }

// معالجة النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    // إعادة تحميل بيانات الموظفين بعد التعديل
    $employees = get_all_employees($pdo);
}

// تأكد من أن $employees ليس null
if ($employees === null) {
    $employees = [];
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الفريق</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>
    <?php include '../includes/topnav.php'; ?>
    
    <div class="container mt-4">
        <h2>إدارة الفريق</h2>
        
        <!-- نموذج إضافة موظف جديد -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">إضافة موظف جديد</button>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>المنصب</th>
                        <th>المشروع</th>
                        <th>الهدف اليومي (الاتصالات)</th>
                        <th>الهدف الشهري (الاتصالات)</th>
                        <th>الهدف الشهري (المبيعات)</th>
                        <th>الهدف الشهري (الزيارات)</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>

                <tbody>
    <?php foreach ($employees as $employee): ?>
                            <!-- ... (الكود الخاص بعرض بيانات الموظفين) ... -->

    <tr>
        <td><?php echo htmlspecialchars($employee['full_name'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($employee['email'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($employee['phone'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($employee['role'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars(get_project_name($pdo, $employee['project_id'] ?? null) ?? 'غير محدد'); ?></td>
        <td><?php echo htmlspecialchars($employee['daily_call_target'] ?? '0'); ?></td>
        <td><?php echo htmlspecialchars($employee['monthly_call_target'] ?? '0'); ?></td>
        <td><?php echo htmlspecialchars($employee['monthly_sales_target'] ?? '0'); ?></td>
        <td><?php echo htmlspecialchars($employee['monthly_visit_target'] ?? '0'); ?></td>
        <td>
            <button class="btn btn-sm btn-primary" onclick="editEmployee(<?php echo $employee['id']; ?>)">تعديل</button>
            <form method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟');">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">
                <button type="submit" class="btn btn-sm btn-danger">حذف</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
            </table>
        </div>
    </div>

    <!-- Modal إضافة موظف -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة موظف جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="username" class="form-label">اسم المستخدم</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="full_name" class="form-label">الاسم الكامل</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">الهاتف</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">المنصب</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="employee">موظف</option>
                                <option value="admin">مدير</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="project_id" class="form-label">المشروع</label>
                            <select class="form-select" id="project_id" name="project_id">
                                <option value="">اختر المشروع</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?php echo $project['id']; ?>"><?php echo htmlspecialchars($project['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="daily_call_target" class="form-label">الهدف اليومي (الاتصالات)</label>
                            <input type="number" class="form-control" id="daily_call_target" name="daily_call_target" required>
                        </div>
                        <div class="mb-3">
                            <label for="monthly_call_target" class="form-label">الهدف الشهري (الاتصالات)</label>
                            <input type="number" class="form-control" id="monthly_call_target" name="monthly_call_target" required>
                        </div>
                        <div class="mb-3">
                            <label for="monthly_sales_target" class="form-label">الهدف الشهري (المبيعات)</label>
                            <input type="number" class="form-control" id="monthly_sales_target" name="monthly_sales_target" required>
                        </div>
                        <div class="mb-3">
                            <label for="monthly_visit_target" class="form-label">الهدف الشهري (الزيارات)</label>
                            <input type="number" class="form-control" id="monthly_visit_target" name="monthly_visit_target" required>
                        </div>
                        <button type="submit" class="btn btn-primary">إضافة الموظف</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Modal تعديل موظف -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل بيانات الموظف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="editEmployeeForm">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_full_name" class="form-label">الاسم الكامل</label>
                        <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">الهاتف</label>
                        <input type="tel" class="form-control" id="edit_phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">المنصب</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="employee">موظف</option>
                            <option value="admin">مدير</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_project_id" class="form-label">المشروع</label>
                        <select class="form-select" id="edit_project_id" name="project_id">
                            <option value="">اختر المشروع</option>
                            <?php foreach ($projects as $project): ?>
                                <option value="<?php echo $project['id']; ?>"><?php echo htmlspecialchars($project['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_daily_call_target" class="form-label">الهدف اليومي (الاتصالات)</label>
                        <input type="number" class="form-control" id="edit_daily_call_target" name="daily_call_target" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_monthly_call_target" class="form-label">الهدف الشهري (الاتصالات)</label>
                        <input type="number" class="form-control" id="edit_monthly_call_target" name="monthly_call_target" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_monthly_sales_target" class="form-label">الهدف الشهري (المبيعات)</label>
                        <input type="number" class="form-control" id="edit_monthly_sales_target" name="monthly_sales_target" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_monthly_visit_target" class="form-label">الهدف الشهري (الزيارات)</label>
                        <input type="number" class="form-control" id="edit_monthly_visit_target" name="monthly_visit_target" required>
                    </div>
                    <button type="submit" class="btn btn-primary">تحديث الموظف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editEmployee(id) {
    $.ajax({
        url: '/telad/api/get_employee.php',
        type: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(data) {
            $('#edit_id').val(data.id);
            $('#edit_full_name').val(data.full_name);
            $('#edit_email').val(data.email);
            $('#edit_phone').val(data.phone);
            $('#edit_role').val(data.role);
            $('#edit_project_id').val(data.project_id);
            $('#edit_daily_call_target').val(data.daily_call_target);
            $('#edit_monthly_call_target').val(data.monthly_call_target);
            $('#edit_monthly_sales_target').val(data.monthly_sales_target);
            $('#edit_monthly_visit_target').val(data.monthly_visit_target);
            
            $('#editEmployeeModal').modal('show');
        },
        error: function() {
            alert('حدث خطأ أثناء جلب بيانات الموظف');
        }
    });
}
</script>

<script>
    $('#editEmployeeForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: '/telad/api/update_employee.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('تم تحديث بيانات الموظف بنجاح');
                $('#editEmployeeModal').modal('hide');
                location.reload(); // إعادة تحميل الصفحة لعرض البيانات المحدثة
            } else {
                alert('حدث خطأ أثناء تحديث بيانات الموظف');
            }
        },
        error: function() {
            alert('حدث خطأ أثناء الاتصال بالخادم');
        }
    });
});

</script>

</body>
</html>