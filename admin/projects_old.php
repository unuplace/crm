<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /crm/auth/login.php');
    exit();
}

// جديد

check_login();
if (!is_admin()) {
    header("Location: ../employee/dashboard.php");
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
                delete_project($pdo, $_POST['id']);
                break;
        }
    }
}


$projects = get_all_projects($pdo);

?>



<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المشاريع</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/x-editable@1.5.1/dist/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/topnav.php'; ?>

    <div class="container mt-4">
        <!-- <h2>إدارة المشاريع</h2> -->
        
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
                        <td><?php echo htmlspecialchars($project['name']); ?></td>
                        <td><?php echo htmlspecialchars($project['city']); ?></td>
                        <td><a href="#" class="editable" data-type="number" data-pk="<?php echo $project['id']; ?>" data-name="total_units"><?php echo 
                        
                        ($project['total_units']); ?></a></td>
                        <td><a href="#" class="editable" data-type="number" data-pk="<?php echo $project['id']; ?>" data-name="sold_units"><?php echo htmlspecialchars($project['sold_units']); ?></a></td>
                        <td><?php echo htmlspecialchars($project['remaining_units']); ?></td>
                        <td><a href="#" class="editable" data-type="number" data-pk="<?php echo $project['id']; ?>" data-name="design_count"><?php echo htmlspecialchars($project['design_count']); ?></a></td>
                        <td><a href="#" class="editable" data-type="date" data-pk="<?php echo $project['id']; ?>" data-name="start_date"><?php echo htmlspecialchars($project['start_date']); ?></a></td>
                        <td><a href="#" class="editable" data-type="date" data-pk="<?php echo $project['id']; ?>" data-name="end_date"><?php echo htmlspecialchars($project['end_date']); ?></a></td>
                        <td><a href="#" class="editable" data-type="select" data-pk="<?php echo $project['id']; ?>" data-name="status" data-source='{"active":"نشط","completed":"مكتمل","on_hold":"معلق","cancelled":"ملغي"}'><?php echo htmlspecialchars($project['status']); ?></a></td>
                        <td><a href="#" class="editable" data-type="textarea" data-pk="<?php echo $project['id']; ?>" data-name="description"><?php echo htmlspecialchars($project['description']); ?></a></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editLogo(<?php echo $project['id']; ?>)">تعديل الشعار</button>
                            <form method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟');">
                                <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                                <button type="submit" name="delete_project" class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Logo Update -->
    <div class="modal fade" id="logoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تحديث شعار المشروع</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="logoForm" enctype="multipart/form-data">
                        <input type="hidden" id="project_id" name="project_id">
                        <div class="mb-3">
                            <label for="logo" class="form-label">الشعار الجديد</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">تحديث الشعار</button>
                    </form>
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
    <script src="https://cdn.jsdelivr.net/npm/x-editable@1.5.1/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script>
    $(document).ready(function() {
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.ajaxOptions = {method: "POST"};
        
        $('.editable').editable({
            url: '/crm/api/update_project_field.php',
            params: function(params) {
                params.admin_id = <?php echo $_SESSION['user_id']; ?>;
                return params;
            }
        });

        $('#logoForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '/crm/api/update_project_logo.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert('تم تحديث الشعار بنجاح');
                    $('#logoModal').modal('hide');
                },
                error: function() {
                    alert('حدث خطأ أثناء تحديث الشعار');
                }
            });
        });
    });

    function editLogo(projectId) {
        $('#project_id').val(projectId);
        $('#logoModal').modal('show');
    }
    </script>
        <?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/projects.js"></script>
</body>
</html>





