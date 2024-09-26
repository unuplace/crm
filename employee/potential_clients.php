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

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>العملاء المحتملين</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/x-editable@1.5.1/dist/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>
    <?php include '../includes/employee_topnav.php'; ?>
    
    <div class="container mt-4">
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