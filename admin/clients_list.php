<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /crm/auth/login.php');
    exit();
}

// استرجاع جميع العملاء
$clients = get_all_clients($pdo);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة العملاء</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>
<?php include '../includes/topnav.php'; ?>

<div class="container mt-4">
    <h2>قائمة العملاء</h2>
    
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
                    <th>الملاحظات</th>
                    <th>تاريخ الاتصال</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clients)): ?>
                    <tr>
                        <td colspan="11" class="text-center">لا توجد عملاء.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                    <td><a href="client_details.php?id=<?php echo $client['id']; ?>"><?php echo htmlspecialchars($client['name']); ?></a></td>
                        <!-- <td><?php echo htmlspecialchars($client['name']); ?></td> -->
                        <td><?php echo htmlspecialchars($client['phone']); ?></td>
                        <td><?php echo htmlspecialchars($client['email']); ?></td>
                        <td><?php echo htmlspecialchars($client['salary']); ?></td>
                        <td><?php echo htmlspecialchars($client['monthly_commitment']); ?></td>
                        <td><?php echo htmlspecialchars($client['bank']); ?></td>
                        <td><?php echo htmlspecialchars($client['sector']); ?></td>
                        <td><?php echo htmlspecialchars($client['status']); ?></td>
                        <td><?php echo htmlspecialchars($client['notes']); ?></td>
                        <td><?php echo htmlspecialchars($client['contact_date']); ?></td>
                        <td>
                            <a href="client_details.php?id=<?php echo $client['id']; ?>" class="btn btn-info btn-sm">عرض التفاصيل</a>
                            <form method="POST" action="../api/delete_client.php" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل؟');">
                                <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>