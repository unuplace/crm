<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /telad/auth/login.php');
    exit();
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
        <h2>إدارة المشاريع</h2>
        
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
                        <td><a href="#" class="editable" data-type="number" data-pk="<?php echo $project['id']; ?>" data-name="total_units"><?php echo htmlspecialchars($project['total_units']); ?></a></td>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/x-editable@1.5.1/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script>
    $(document).ready(function() {
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.ajaxOptions = {method: "POST"};
        
        $('.editable').editable({
            url: '/telad/api/update_project_field.php',
            params: function(params) {
                params.admin_id = <?php echo $_SESSION['user_id']; ?>;
                return params;
            }
        });

        $('#logoForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '/telad/api/update_project_logo.php',
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





