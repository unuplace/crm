<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header('Location: /crm/auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_visit'])) {
    $client_id = $_POST['client_id'];
    $visit_date = $_POST['visit_date'];
    $company_name = $_POST['company_name'];
    $department = $_POST['department'];
    $contact_name = $_POST['contact_name'];
    $contact_phone = $_POST['contact_phone'];
    $description = $_POST['description'];
    $recommendations = $_POST['recommendations'];
    $notes = $_POST['notes'];
    
    if (add_visit($pdo, $_SESSION['user_id'], $client_id, $visit_date, $company_name, $department, $contact_name, $contact_phone, $description, $recommendations, $notes)) {
        $success_message = "تمت إضافة الزيارة بنجاح";
    } else {
        $error_message = "حدث خطأ أثناء إضافة الزيارة";
    }
}

$clients = get_employee_potential_clients($pdo, $_SESSION['user_id']);
$visits = get_employee_visits($pdo, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الزيارات</title>
    <!-- إضافة روابط CSS الخاصة بك هنا -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/employee_topnav.php'; ?>
    
    <div class="container mt-4">
        <h2>إضافة زيارة جديدة</h2>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="client_id" class="form-label">العميل</label>
                <select name="client_id" id="client_id" class="form-select" required>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?php echo $client['id']; ?>"><?php echo htmlspecialchars($client['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="company_name" class="form-label">اسم الشركة </label>
                <input type="text" name="company_name" id="company_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">القسم </label>
                <input type="text" name="department" id="department" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact_name" class="form-label">المسؤول </label>
                <input type="text" name="contact_name" id="contact_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact_phone" class="form-label">جوال </label>
                <input type="text" name="contact_phone" id="contact_phone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">وصف الزيارة </label>
                <input type="text" name="description" id="description" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="recommendations" class="form-label">التوصيات </label>
                <input type="text" name="recommendations" id="recommendations" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="visit_date" class="form-label">تاريخ الزيارة</label>
                <input type="date" name="visit_date" id="visit_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات</label>
                <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" name="add_visit" class="btn btn-primary">إضافة الزيارة</button>
        </form>
        
        <h2 class="mt-5">الزيارات السابقة</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>العميل</th>
                    <th>تاريخ الزيارة</th>
                    <th>الشركة</th>
                    <th>القسم</th>
                    <th>المسؤول</th>
                    <th>الجوال</th>
                    <th>وصف الزيارة</th>
                    <th>التوصيات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($visits as $visit): ?>
                <tr>
                    <td><?php echo htmlspecialchars($visit['client_name']); ?></td>
                    <td><?php echo htmlspecialchars($visit['visit_date']); ?></td>
                    <td><?php echo htmlspecialchars($visit['company_name']); ?></td>
                    <td><?php echo htmlspecialchars($visit['department']); ?></td>
                    <td><?php echo htmlspecialchars($visit['contact_name']); ?></td>
                    <td><?php echo htmlspecialchars($visit['contact_phone']); ?></td>
                    <td><?php echo htmlspecialchars($visit['description']); ?></td>
                    <td><?php echo htmlspecialchars($visit['recommendations']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- إضافة روابط JavaScript الخاصة بك هنا -->
    <?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/visits.js"></script>

</body>
</html>