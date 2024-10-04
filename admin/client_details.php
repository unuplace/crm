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
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
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

    <!-- زر لتعديل بيانات العميل -->
    <button type="button" class="btn btn-warning mt-3" data-bs-toggle="modal" data-bs-target="#editClientModal">تعديل البيانات</button>

    <!-- زر لإضافة مهمة -->
    <button type="button" class="btn btn-info mt-3" data-bs-toggle="modal" data-bs-target="#addTaskModal">إضافة مهمة</button>

    <!-- زر لإدراج مرفق -->
    <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#addAttachmentModal">إدراج مرفق</button>

    <!-- نموذج تعديل بيانات العميل -->
    <div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClientModalLabel">تعديل بيانات العميل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editClientForm">
                        <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">الاسم</label>
                            <input type="text" class="form-control" id="edit_name" name="name" value="<?php echo htmlspecialchars($client['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">رقم الهاتف</label>
                            <input type="tel" class="form-control" id="edit_phone" name="phone" value="<?php echo htmlspecialchars($client['phone']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="edit_email" name="email" value="<?php echo htmlspecialchars($client['email']); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">تحديث البيانات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- نموذج إضافة مهمة -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">إضافة مهمة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTaskForm">
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
                </div>
            </div>
        </div>
    </div>

    <!-- نموذج إدراج مرفقات -->
    <div class="modal fade" id="addAttachmentModal" tabindex="-1" aria-labelledby="addAttachmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAttachmentModalLabel">إدراج مرفق</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addAttachmentForm" enctype="multipart/form-data">
                        <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                        <div class="mb-3">
                            <label for="attachment_type" class="form-label">نوع المرفق</label>
                            <select class="form-select" id="attachment_type" name="attachment_type" required>
                                <option value="مبيعات">مبيعات</option>
                                <option value="عميل">عميل</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="attachment_name" class="form-label">اسم المرفق</label>
                            <input type="text" class="form-control" id="attachment_name" name="attachment_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="attachment_file" class="form-label">اختر ملف المرفق</label>
                            <input type="file" class="form-control" id="attachment_file" name="attachment_file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">إضافة مرفق</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-4">إضافة ملاحظات</h3>
    <form method="POST" action="/crm/api/add_note.php">
        <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
        <div class="mb-3">
            <label for="note" class="form-label">ملاحظة</label>
            <textarea class="form-control" id="note" name="note" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">إضافة ملاحظة</button>
    </form>

    <h3 class="mt-4">المرفقات</h3>
    <h5>مرفقات المبيعات</h5>
    <ul>
        <?php
        $attachments = get_attachments($pdo, $client['id'], 'مبيعات');
        foreach ($attachments as $attachment) {
            echo '<li>' . htmlspecialchars($attachment['name']) . ' <a href="' . htmlspecialchars($attachment['path']) . '" target="_blank">عرض</a></li>';
        }
        ?>
    </ul>

    <h5>مرفقات العميل</h5>
    <ul>
        <?php
        $attachments = get_attachments($pdo, $client['id'], 'عميل');
        foreach ($attachments as $attachment) {
            echo '<li>' . htmlspecialchars($attachment['name']) . ' <a href="' . htmlspecialchars($attachment['path']) . '" target="_blank">عرض</a></li>';
        }
        ?>
    </ul>

    <h3 class="mt-4">المهام</h3>
    <ul>
        <?php
        $tasks = get_client_tasks($pdo, $client['id']);
        foreach ($tasks as $task) {
            echo '<li>' . htmlspecialchars($task['task_type']) . ' - ' . htmlspecialchars($task['task_date']) . ' ' . htmlspecialchars($task['task_time']) . ' - ' . htmlspecialchars($task['description']) . '</li>';
        }
        ?>
    </ul>

    <a href="clients_list.php" class="btn btn-secondary mt-3">العودة إلى قائمة العملاء</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // نموذج تعديل بيانات العميل
    document.getElementById('editClientForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../api/update_client.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // إعادة تحميل الصفحة لتحديث البيانات
            } else {
                alert(data.error);
            }
        });
    });

    // نموذج إضافة مهمة
    document.getElementById('addTaskForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../api/add_task.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // إعادة تحميل الصفحة لتحديث البيانات
            } else {
                alert(data.error);
            }
        });
    });

    // نموذج إدراج مرفقات
    document.getElementById('addAttachmentForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../api/upload_attachment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // إعادة تحميل الصفحة لتحديث البيانات
            } else {
                alert(data.error);
            }
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>