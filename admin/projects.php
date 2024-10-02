<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /crm/auth/login.php');
    exit();
}

// تعريف $employees في بداية الصفحة
$projects = get_all_projects($pdo);

// معالجة النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                add_project($pdo, $_POST);
                break;
            case 'edit':
                edit_project($pdo, $_POST);
                break;
            case 'delete':
                delete_project($pdo, $_POST['id']);
                break;
        }
    }
    // إعادة تحميل بيانات الموظفين بعد التعديل
    $projects = get_all_projects($pdo);
}

// تأكد من أن $employees ليس null
if ($projects === null) {
    $projects = [];
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المشاريع</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/topnav.php'; ?>

    <div class="container mt-4">
        <h2>إدارة الفريق</h2>
        
        <!-- نموذج إضافة موظف جديد -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProjectModal">إضافة موظف جديد</button>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>اسم المشروع</th>
                        <th>المدينة</th>
                        <th>عدد الوحدات</th>
                        <th>الوحدات المباعة</th>
                        <th>الوحدات المتبقية</th>
                        <th>عدد التصاميم</th>
                        <th>تاريخ البداية</th>
                        <th>تاريخ النهاية</th>
                        <th>الحالة</th>
                        <th>الوصف</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($project['name']); ?></td>
                        <td><?php echo htmlspecialchars($project['city']); ?></td>
                        <td><?php echo htmlspecialchars($project['total_units']); ?></td>
                        <td><?php echo htmlspecialchars($project['sold_units']); ?></td>
                        <td><?php echo htmlspecialchars($project['remaining_units']); ?></td>
                        <td><?php echo htmlspecialchars($project['design_count']); ?></td>
                        <td><?php echo htmlspecialchars($project['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($project['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($project['status']); ?></td>
                        <td><?php echo htmlspecialchars($project['description']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-project" onclick="editProject(<?php echo $project['id']; ?>)" >تعديل</button>
                            <button class="btn btn-sm btn-danger delete-project" data-id="<?php echo $project['id']; ?>">حذف</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

     <!-- Add Project Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectModalLabel">إضافة مشروع جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProjectForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="projectName" class="form-label">اسم المشروع</label>
                            <input type="text" class="form-control" id="projectName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectCity" class="form-label">المدينة</label>
                            <input type="text" class="form-control" id="projectCity" name="city" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectUnits" class="form-label">عدد الوحدات</label>
                            <input type="number" class="form-control" id="projectUnits" name="total_units" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectDesigns" class="form-label">عدد التصاميم</label>
                            <input type="number" class="form-control" id="projectDesigns" name="design_count" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectLogo" class="form-label">شعار المشروع</label>
                            <input type="file" class="form-control" id="projectLogo" name="logo">
                        </div>
                        <div class="mb-3">
                            <label for="projectDescription" class="form-label">وصف المشروع</label>
                            <textarea class="form-control" id="projectDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="projectStartDate" class="form-label">تاريخ البداية</label>
                            <input type="date" class="form-control" id="projectStartDate" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectEndDate" class="form-label">تاريخ النهاية</label>
                            <input type="date" class="form-control" id="projectEndDate" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectUnitsSold" class="form-label">عدد الوحدات المباعة</label>
                            <input type="number" class="form-control" id="projectUnitsSold" name="sold_units" value="0" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="projectStatus" class="form-label">الحالة</label>
                            <select class="form-select" id="projectStatus" name="status">
                                <option value="active">نشط</option>
                                <option value="completed">مكتمل</option>
                                <option value="on_hold">معلق</option>
                                <option value="cancelled">ملغي</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" form="addProjectForm" class="btn btn-primary">إضافة المشروع</button>
                </div>
            </div>
        </div>
    </div>

<!-- Modal تعديل موظف -->
<div class="modal fade" id="editProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل بيانات الموظف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="editProjectForm">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                            <label for="projectName" class="form-label">اسم المشروع</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectCity" class="form-label">المدينة</label>
                            <input type="text" class="form-control" id="edit_city" name="city" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="projectUnits" class="form-label">عدد الوحدات</label>
                            <input type="number" class="form-control" id="edit_total_units" name="total_units" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectUnits" class="form-label">عدد الوحدات</label>
                            <input type="number" class="form-control" id="projectUnits" name="total_units" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectDesigns" class="form-label">عدد التصاميم</label>
                            <input type="number" class="form-control" id="projectDesigns" name="design_count" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectLogo" class="form-label">شعار المشروع</label>
                            <input type="file" class="form-control" id="projectLogo" name="logo">
                        </div>
                        <div class="mb-3">
                            <label for="projectDescription" class="form-label">وصف المشروع</label>
                            <textarea class="form-control" id="projectDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="projectStartDate" class="form-label">تاريخ البداية</label>
                            <input type="date" class="form-control" id="projectStartDate" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectEndDate" class="form-label">تاريخ النهاية</label>
                            <input type="date" class="form-control" id="projectEndDate" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectUnitsSold" class="form-label">عدد الوحدات المباعة</label>
                            <input type="number" class="form-control" id="projectUnitsSold" name="sold_units" value="0" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="projectStatus" class="form-label">الحالة</label>
                            <select class="form-select" id="projectStatus" name="status">
                                <option value="active">نشط</option>
                                <option value="completed">مكتمل</option>
                                <option value="on_hold">معلق</option>
                                <option value="cancelled">ملغي</option>
                            </select>
                        </div>
                    </form>
                </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" id="saveProjectChanges">حفظ التغييرات</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/projects.js"></script>
    <?php include '../includes/footer.php'; ?>

    <script>
    $(document).ready(function() {
    // Edit project
    $('.edit-project').click(function() {
        var projectId = $(this).data('id');
        $.ajax({
            url: '/crm/api/get_project.php',
            type: 'GET',
            data: { id: projectId },
            success: function(response) {
                var project = JSON.parse(response);
                $('#edit_id').val(project.id);
                $('#edit_name').val(project.name);
                $('#edit_city').val(project.city);
                $('#edit_total_units').val(project.total_units);
                $('#edit_sold_units').val(project.sold_units);
                $('#edit_design_count').val(project.design_count);
                $('#edit_description').val(project.description);
                $('#edit_start_date').val(project.start_date);
                $('#edit_end_date').val(project.end_date);
                $('#edit_status').val(project.status);
                $('#editProjectModal').modal('show');
            }
        });
    });

    // Save project changes
    $('#saveProjectChanges').click(function() {
        var formData = $('#editProjectForm').serialize();
        $.ajax({
            url: '/crm/api/update_project.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response === 'success') {
                    alert('تم تحديث المشروع بنجاح');
                    $('#editProjectModal').modal('hide');
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء تحديث المشروع');
                }
            }
        });
    });

    // Delete project
    $('.delete-project').click(function() {
        if (confirm('هل أنت متأكد من حذف هذا المشروع؟')) {
            var projectId = $(this).data('id');
            $.ajax({
                url: '/crm/api/delete_project.php',
                type: 'POST',
                data: { id: projectId },
                success: function(response) {
                    if (response === 'success') {
                        alert('تم حذف المشروع بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المشروع');
                    }
                }
            });
        }
    });
});
</script>

</body>
</html>