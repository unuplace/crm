<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                add_project($pdo, $_POST);
                break;
            case 'edit':
                edit_project($pdo, $_POST);
                break;
            case 'delete':
                delete_project($pdo, $_POST['project_id']);
                break;
            case 'add_property_types':
                add_property_types_to_project($pdo, $_POST);
                break;
        }
    }
}

$projects = get_all_projects($pdo);
$property_types = get_all_property_types($pdo);
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
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">إدارة المشاريع</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">إضافة مشروع جديد</button>
        </div>

        <div class="table-responsive mt-4">
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
                        <td><a href="project_details.php?id=<?php echo $project['id']; ?>"><?php echo htmlspecialchars($project['name']); ?></a></td>
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
                            <button class="btn btn-sm btn-primary" onclick="showEditProjectModal(<?php echo $project['id']; ?>)">تعديل</button>
                            <form method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟');">
                                <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                                <button type="submit" name="delete_project" class="btn btn-sm btn-danger">حذف</button>
                            </form>
                            <button class="btn btn-sm btn-primary" onclick="showPropertyTypesModal(<?php echo $project['id']; ?>)">إضافة نماذج</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for adding property types to project -->
    <div class="modal fade" id="addPropertyTypesModal" tabindex="-1" aria-labelledby="addPropertyTypesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPropertyTypesModalLabel">إضافة نماذج عقارية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPropertyTypesForm">
                        <input type="hidden" name="project_id" id="modalProjectId">
                        <?php foreach ($property_types as $property_type): ?>
                        <div class="mb-3">
                            <label for="property_type_<?php echo $property_type['id']; ?>" class="form-label"><?php echo htmlspecialchars($property_type['name']); ?></label>
                            <input type="number" class="form-control" id="property_type_<?php echo $property_type['id']; ?>" name="property_type_<?php echo $property_type['id']; ?>" min="0" value="0">
                        </div>
                        <?php endforeach; ?>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing project -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectModalLabel">تعديل المشروع</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProjectForm">
                        <input type="hidden" name="project_id" id="editProjectId">
                        <div class="mb-3">
                            <label for="editProjectName" class="form-label">اسم المشروع</label>
                            <input type="text" class="form-control" id="editProjectName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProjectCity" class="form-label">المدينة</label>
                            <input type="text" class="form-control" id="editProjectCity" name="city" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProjectTotalUnits" class="form-label">عدد الوحدات</label>
                            <input type="number" class="form-control" id="editProjectTotalUnits" name="total_units" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProjectDesignCount" class="form-label">عدد التصاميم</label>
                            <input type="number" class="form-control" id="editProjectDesignCount" name="design_count" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProjectDescription" class="form-label">وصف المشروع</label>
                            <textarea class="form-control" id="editProjectDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editProjectStartDate" class="form-label">تاريخ البداية</label>
                            <input type="date" class="form-control" id="editProjectStartDate" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProjectEndDate" class="form-label">تاريخ النهاية</label>
                            <input type="date" class="form-control" id="editProjectEndDate" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProjectStatus" class="form-label">الحالة</label>
                            <select class="form-select" id="editProjectStatus" name="status">
                                <option value="planned">مخطط</option>
                                <option value="in_progress">قيد التنفيذ</option>
                                <option value="completed">مكتمل</option>
                                <option value="on_hold">معلق</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" form="editProjectForm" class="btn btn-primary">تحديث المشروع</button>
                </div>
            </div>
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
                    <form id="addProjectForm" method="POST">
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
                            <label for="projectUnitsSold" class="form-label">عدد الوحدات المباعة</label>
                            <input type="number" class="form-control" id="projectUnitsSold" name="sold_units" value="0" min="0">
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
                            <label for="projectStatus" class="form-label">الحالة</label>
                            <select class="form-select" id="projectStatus" name="status">
                                <option value="planned">مخطط</option>
                                <option value="in_progress">قيد التنفيذ</option>
                                <option value="completed">مكتمل</option>
                                <option value="on_hold">معلق</option>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showEditProjectModal(projectId) {
            $.ajax({
                url: '../api/get_project.php',
                type: 'GET',
                data: { id: projectId },
                success: function(data) {
                    const project = JSON.parse(data);
                    $('#editProjectId').val(project.id);
                    $('#editProjectName').val(project.name);
                    $('#editProjectCity').val(project.city);
                    $('#editProjectTotalUnits').val(project.total_units);
                    $('#editProjectDesignCount').val(project.design_count);
                    $('#editProjectDescription').val(project.description);
                    $('#editProjectStartDate').val(project.start_date);
                    $('#editProjectEndDate').val(project.end_date);
                    $('#editProjectStatus').val(project.status);
                    $('#editProjectModal').modal('show');
                }
            });
        }

        function showPropertyTypesModal(projectId) {
            $('#modalProjectId').val(projectId);
            $('#addPropertyTypesModal').modal('show');
        }

        document.getElementById('addPropertyTypesForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('../api/add_property_types_to_project.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم إضافة النماذج بنجاح');
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء إضافة النماذج: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء إضافة النماذج');
            });
        });
    </script>
    <?php include '../includes/footer.php'; ?>
</body>
</html>