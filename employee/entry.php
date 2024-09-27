<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header('Location: /telad/auth/login.php');
    exit();
}

$employee_id = $_SESSION['user_id'];
$clients = get_employee_potential_clients($pdo, $employee_id);
?>

<!-- الكود الجديد -->
<?php

// إضافة عميل محتمل جديد
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_client'])) {
    $result = add_potential_client($pdo, $_POST);
    if ($result) {
        $success_message = "تمت إضافة العميل المحتمل بنجاح";
    } else {
        $error_message = "حدث خطأ أثناء إضافة العميل المحتمل";
    }
}

// تحديث بيانات العميل المحتمل
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_client'])) {
    $result = update_potential_client($pdo, $_POST);
    if ($result) {
        $success_message = "تم تحديث بيانات العميل المحتمل بنجاح";
    } else {
        $error_message = "حدث خطأ أثناء تحديث بيانات العميل المحتمل";
    }
}

// حذف العميل المحتمل
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_client'])) {
    $result = delete_potential_client($pdo, $_POST['client_id']);
    if ($result) {
        $success_message = "تم حذف العميل المحتمل بنجاح";
    } else {
        $error_message = "حدث خطأ أثناء حذف العميل المحتمل";
    }
}

$potential_clients = get_all_potential_clients($pdo);
$employees = get_all_employees($pdo);
?>



<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استقبال العملاء</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/x-editable@1.5.1/dist/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>
    <?php include '../includes/employee_topnav.php'; ?>
    




    <div class="container mt-4">

            <!-- نموذج إضافة عميل محتمل جديد -->
            <h3>إضافة عميل محتمل جديد</h3>
        <form method="POST">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="phone" class="form-label">رقم الهاتف</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="salary" class="form-label">الراتب</label>
                    <input type="number" class="form-control" id="salary" name="salary">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="monthly_commitment" class="form-label">الالتزام الشهري</label>
                    <input type="number" class="form-control" id="monthly_commitment" name="monthly_commitment">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="bank" class="form-label">البنك</label>
                    <input type="text" class="form-control" id="bank" name="bank">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="sector" class="form-label">القطاع</label>
                    <input type="text" class="form-control" id="sector" name="sector">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">الحالة</label>
                    <select class="form-select" id="status" name="status">
                        <option value="جديد">جديد</option>
                        <option value="متابعة">متابعة</option>
                        <option value="مهتم">مهتم</option>
                        <option value="غير مهتم">غير مهتم</option>
                        <option value="تم الحجز">تم الحجز</option>
                        <option value="تم البيع">تم البيع</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="assigned_to" class="form-label">تعيين إلى</label>
                    <select class="form-select" id="assigned_to" name="assigned_to">
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?php echo $employee['id']; ?>"><?php echo htmlspecialchars($employee['full_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                    <label for="source" class="form-label">المصدر</label>
                    <select class="form-select" id="source" name="source">
                        <option value="وسائل تواصل">وسائل تواصل</option>
                        <option value="الهاتف">الهاتف</option>
                        <option value="مركز المبيعات">مركز المبيعات</option>
                        <option value="الموقع الالكتروني">الموقع الالكتروني</option>
                        <option value="الحملات">الحملات</option>
                        <option value="أخرى">أخرى</option>
                    </select>
                </div>

            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="contact_date" class="form-label">تاريخ الاتصال</label>
                <input type="date" class="form-control" id="contact_date" name="contact_date">
            </div>
            <button type="submit" name="add_client" class="btn btn-primary">إضافة العميل المحتمل</button>
        </form>



        <h2>العملاء المحتملين</h2>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>رقم الهاتف</th>
                        <th>البريد الإلكتروني</th>
                        <th>الراتب</th>
                        <th>الالتزام الشهري</th>
                        <th>البنك</th>
                        <th>القطاع</th>
                        <th>الحالة</th>
                        <th>ملاحظات</th>
                        <th>تاريخ الاتصال</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($client['name']); ?></td>
                        <td><?php echo htmlspecialchars($client['phone']); ?></td>
                        <td><a href="#" class="editable" data-type="text" data-pk="<?php echo $client['id']; ?>" data-name="email"><?php echo htmlspecialchars($client['email']); ?></a></td>
                        <td><a href="#" class="editable" data-type="number" data-pk="<?php echo $client['id']; ?>" data-name="salary"><?php echo htmlspecialchars($client['salary']); ?></a></td>
                        <td><a href="#" class="editable" data-type="number" data-pk="<?php echo $client['id']; ?>" data-name="monthly_commitment"><?php echo htmlspecialchars($client['monthly_commitment']); ?></a></td>
                        <td><a href="#" class="editable" data-type="text" data-pk="<?php echo $client['id']; ?>" data-name="bank"><?php echo htmlspecialchars($client['bank']); ?></a></td>
                        <td><a href="#" class="editable" data-type="text" data-pk="<?php echo $client['id']; ?>" data-name="sector"><?php echo htmlspecialchars($client['sector']); ?></a></td>
                        <td><a href="#" class="editable" data-type="text" data-pk="<?php echo $client['id']; ?>" data-name="notes"><?php echo htmlspecialchars($client['notes']); ?></a></td>
                        <td><a href="#" class="editable" data-type="select" data-pk="<?php echo $client['id']; ?>" data-name="status" data-source='{"جديد":"جديد","متابعة":"متابعة","مهتم":"مهتم","غير مهتم":"غير مهتم","تم الحجز":"تم الحجز","تم البيع":"تم البيع"}'><?php echo htmlspecialchars($client['status']); ?></a></td>
                        <td><a href="#" class="editable" data-type="date" data-pk="<?php echo $client['id']; ?>" data-name="contact_date"><?php echo htmlspecialchars($client['contact_date']); ?></a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/x-editable@1.5.1/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script>
    $(document).ready(function() {
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.ajaxOptions = {method: "POST"};
        
        $('.editable').editable({
            url: '/telad/api/update_client_field.php',
            params: function(params) {
                params.employee_id = <?php echo $employee_id; ?>;
                return params;
            }
        });
    });
    </script>
<script>
$(document).ready(function() {
    $.fn.editable.defaults.mode = 'inline';
    $.fn.editable.defaults.ajaxOptions = {method: "POST"};
    
    $('.editable').editable({
        url: '/telad/api/update_client_field.php',
        params: function(params) {
            params.employee_id = <?php echo $_SESSION['user_id']; ?>;
            if (params.name === 'status') {
                params.notes = prompt("أدخل ملاحظات حول تغيير الحالة:");
            }
            return params;
        }
    });
});
</script>
        <?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/potential_clients.js"></script>
</body>
</html>