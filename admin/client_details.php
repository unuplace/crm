<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /crm/auth/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $client_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM potential_clients WHERE id = ?");
    $stmt->execute([$client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$client) {
        http_response_code(404);
        echo json_encode(['error' => 'Client not found']);
        exit();
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Missing client ID']);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل العميل</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>
<?php include '../includes/topnav.php'; ?>

<div class="container mt-4">
    <h2>تفاصيل العميل</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($client['name']); ?></h5>
            <p class="card-text"><strong>رقم الهاتف:</strong> <?php echo htmlspecialchars($client['phone']); ?></p>
            <p class="card-text"><strong>البريد الإلكتروني:</strong> <?php echo htmlspecialchars($client['email']); ?></p>
            <p class="card-text"><strong>الراتب:</strong> <?php echo htmlspecialchars($client['salary']); ?></p>
            <p class="card-text"><strong>الالتزام الشهري:</strong> <?php echo htmlspecialchars($client['monthly_commitment']); ?></p>
            <p class="card-text"><strong>البنك:</strong> <?php echo htmlspecialchars($client['bank']); ?></p>
            <p class="card-text"><strong>القطاع:</strong> <?php echo htmlspecialchars($client['sector']); ?></p>
            <p class="card-text"><strong>الحالة:</strong> <?php echo htmlspecialchars($client['status']); ?></p>
            <p class="card-text"><strong>الملاحظات:</strong> <?php echo htmlspecialchars($client['notes']); ?></p>
            <p class="card-text"><strong>تاريخ الاتصال:</strong> <?php echo htmlspecialchars($client['contact_date']); ?></p>
        </div>
    </div>

    <!-- نموذج تعديل بيانات العميل -->
    <h3 class="mt-4">تعديل بيانات العميل</h3>
    <form method="POST" action="../api/update_client.php">
        <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
        <div class="mb-3">
            <label for="name" class="form-label">الاسم</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($client['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">رقم الهاتف</label>
            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($client['phone']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($client['email']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">تحديث البيانات</button>
    </form>

    <!-- نموذج إدراج مرفقات -->
    <h3 class="mt-4">إدراج مرفقات</h3>
    <form method="POST" action="upload_attachment.php" enctype="multipart/form-data">
        <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
        <div class="mb-3">
            <label for="attachment" class="form-label">اختر ملف المرفق</label>
            <input type="file" class="form-control" id="attachment" name="attachment" required>
        </div>
        <div class="mb-3">
            <label for="attachment_name" class="form-label">اسم المرفق</label>
            <input type="text" class="form-control" id="attachment_name" name="attachment_name" required>
        </div>
        <button type="submit" class="btn btn-primary">إضافة مرفق</button>
    </form>

    <!-- نموذج إضافة ملاحظات -->
    <h3 class="mt-4">إضافة ملاحظات</h3>
    <form method="POST" action="add_note.php">
        <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
        <div class="mb-3">
            <label for="note" class="form-label">ملاحظة</label>
            <textarea class="form-control" id="note" name="note" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">إضافة ملاحظة</button>
    </form>

    <!-- نموذج إضافة مهمة -->
    <h3 class="mt-4">إضافة مهمة</h3>
    <form method="POST" action="add_task.php">
        <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
        <div class="mb-3">
            <label for="task_type" class="form-label">نوع المهمة</label>
            <select class="form-select" id="task_type" name="task_type" required>
                <option value="اتصال">اتصال</option>
                <option value="اجتماع">اجتماع</option>
                <option value="زيارة">زيارة</option>
                <option value="استكمال الطلب">استكمال الطلب</option>
                <option value="أخرى">أخرى</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="task_date" class="form-label">تاريخ المهمة</label>
            <input type="date" class="form-control" id="task_date" name="task_date" required>
        </div>
        <div class="mb-3">
            <label for="task_time" class="form-label">وقت المهمة</label>
            <input type="time" class="form-control" id="task_time" name="task_time" required>
        </div>
        <div class="mb-3">
            <label for="task_description" class="form-label">وصف المهمة</label>
            <textarea class="form-control" id="task_description" name="task_description" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">إضافة مهمة</button>
    </form>

    <a href="potential_clients.php" class="btn btn-secondary mt-3">العودة إلى قائمة العملاء المحتملين</a>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>