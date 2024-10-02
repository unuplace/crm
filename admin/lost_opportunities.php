<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /crm/auth/login.php');
    exit();
}

// استرجاع جميع الفرص الضائعة
$lost_opportunities = get_all_lost_opportunities($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_opportunity'])) {
    $opportunity_id = $_POST['opportunity_id'];
    $stmt = $pdo->prepare("DELETE FROM lost_opportunities WHERE id = ?");
    $stmt->execute([$opportunity_id]);
    $success_message = "تم حذف الفرصة الضائعة بنجاح.";
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الفرص الضائعة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>
<?php include '../includes/topnav.php'; ?>

<div class="container mt-4">
    <h2>الفرص الضائعة</h2>
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
                    <th>الملاحظات</th>
                    <th>تاريخ الاتصال</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lost_opportunities as $opportunity): ?>
                <tr>
                    <td><?php echo htmlspecialchars($opportunity['name']); ?></td>
                    <td><?php echo htmlspecialchars($opportunity['phone']); ?></td>
                    <td><?php echo htmlspecialchars($opportunity['email']); ?></td>
                    <td><?php echo htmlspecialchars($opportunity['salary']); ?></td>
                    <td><?php echo htmlspecialchars($opportunity['monthly_commitment']); ?></td>
                    <td><?php echo htmlspecialchars($opportunity['bank']); ?></td>
                    <td><?php echo htmlspecialchars($opportunity['sector']); ?></td>
                    <td><?php echo htmlspecialchars($opportunity['notes']); ?></td>
                    <td><?php echo htmlspecialchars($opportunity['contact_date']); ?></td>
                    <td>
                        <form method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفرصة الضائعة؟');">
                            <input type="hidden" name="opportunity_id" value="<?php echo $opportunity['id']; ?>">
                            <button type="submit" name="delete_opportunity" class="btn btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>