<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header('Location: /crm/auth/login.php');
    exit();
}

$employee_id = $_SESSION['user_id'];
$clients = get_employee_potential_clients($pdo, $employee_id);




// إضافة هذا الجزء في صفحة potential_clients.php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_as_lost'])) {
    $client_id = $_POST['client_id'];
    $clients = get_all_potential_clients($pdo); // استرجاع بيانات العميل المحتمل
    $client = '';
    if ($client) {
        // إضافة العميل إلى جدول الفرص الضائعة
        $stmt = $pdo->prepare("INSERT INTO lost_opportunities (name, phone, email, salary, monthly_commitment, bank, sector, notes, contact_date, assigned_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $client['name'],
            $client['phone'],
            $client['email'],
            $client['salary'],
            $client['monthly_commitment'],
            $client['bank'],
            $client['sector'],
            $client['notes'],
            $client['contact_date'],
            $client['assigned_to']
        ]);

        // حذف العميل من جدول العملاء المحتملين
        $stmt = $pdo->prepare("DELETE FROM potential_clients WHERE id = ?");
        $stmt->execute([$client_id]);

        $success_message = "تم نقل العميل إلى الفرص الضائعة بنجاح.";
    } else {
        $error_message = "لم يتم العثور على العميل.";
    }
}

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
                        <td><a href="client_details.php?id=<?php echo $client['id']; ?>"><?php echo htmlspecialchars($client['name']); ?></a></td>
                        <td><?php echo htmlspecialchars($client['phone']); ?></td>
                        <td><a href="#" class="editable" data-type="text" data-pk="<?php echo $client['id']; ?>" data-name="email"><?php echo htmlspecialchars($client['email']); ?></a></td>
                        <td><a href="#" class="editable" data-type="number" data-pk="<?php echo $client['id']; ?>" data-name="salary"><?php echo htmlspecialchars($client['salary']); ?></a></td>
                        <td><a href="#" class="editable" data-type="number" data-pk="<?php echo $client['id']; ?>" data-name="monthly_commitment"><?php echo htmlspecialchars($client['monthly_commitment']); ?></a></td>
                        <td><a href="#" class="editable" data-type="text" data-pk="<?php echo $client['id']; ?>" data-name="bank"><?php echo htmlspecialchars($client['bank']); ?></a></td>
                        <td><a href="#" class="editable" data-type="text" data-pk="<?php echo $client['id']; ?>" data-name="sector"><?php echo htmlspecialchars($client['sector']); ?></a></td>
                        <td><a href="#" class="editable" data-type="text" data-pk="<?php echo $client['id']; ?>" data-name="notes"><?php echo htmlspecialchars($client['notes']); ?></a></td>
                        <td><a href="#" class="editable" data-type="select" data-pk="<?php echo $client['id']; ?>" data-name="status" data-source='{"جديد":"جديد","متابعة":"متابعة","مهتم":"مهتم","غير مهتم":"غير مهتم","تم الحجز":"تم الحجز","تم البيع":"تم البيع"}'><?php echo htmlspecialchars($client['status']); ?></a></td>
                        <td><a href="#" class="editable" data-type="date" data-pk="<?php echo $client['id']; ?>" data-name="contact_date"><?php echo htmlspecialchars($client['contact_date']); ?></a></td>

                        <td>
    <form method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من نقل هذا العميل إلى الفرص الضائعة؟');">
        <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
        <button type="submit" name="mark_as_lost" class="btn btn-warning">نقل إلى الفرص الضائعة</button>
    </form>
    <form method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل المحتمل؟');">
        <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
        <button type="submit" name="delete_client" class="btn btn-danger">حذف</button>
    </form>
</td>
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
            url: '/crm/api/update_client_field.php',
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
        url: '/crm/api/update_client_field.php',
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