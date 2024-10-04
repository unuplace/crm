<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $project_id = $_GET['id'];
    $project = get_project_by_id($pdo, $project_id);
    $property_types = get_property_types_by_project($pdo, $project_id); // Fetch property types related to the project
    $properties = get_properties_by_project($pdo, $project_id); // Fetch properties related to the project
} else {
    header('Location: projects.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل المشروع</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/topnav.php'; ?>

    <div class="container mt-4">
        <h2>تفاصيل المشروع: <?php echo htmlspecialchars($project['name']); ?></h2>
        <div class="mb-3">
            <label for="projectName" class="form-label">اسم المشروع</label>
            <input type="text" class="form-control" id="projectName" value="<?php echo htmlspecialchars($project['name']); ?>" onchange="updateProjectField('name', this.value)">
        </div>
        <div class="mb-3">
            <label for="projectCity" class="form-label">المدينة</label>
            <input type="text" class="form-control" id="projectCity" value="<?php echo htmlspecialchars($project['city']); ?>" onchange="updateProjectField('city', this.value)">
        </div>
        <div class="mb-3">
            <label for="projectDescription" class="form-label">وصف المشروع</label>
            <textarea class="form-control" id="projectDescription" onchange="updateProjectField('description', this.value)"><?php echo htmlspecialchars($project['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="projectStartDate" class="form-label">تاريخ البداية</label>
            <input type="date" class="form-control" id="projectStartDate" value="<?php echo htmlspecialchars($project['start_date']); ?>" onchange="updateProjectField('start_date', this.value)">
        </div>
        <div class="mb-3">
            <label for="projectEndDate" class="form-label">تاريخ النهاية</label>
            <input type="date" class="form-control" id="projectEndDate" value="<?php echo htmlspecialchars($project['end_date']); ?>" onchange="updateProjectField('end_date', this.value)">
        </div>
        <div class="mb-3">
            <label for="projectStatus" class="form-label">الحالة</label>
            <select class="form-select" id="projectStatus" onchange="updateProjectField('status', this.value)">
                <option value="planned" <?php echo $project['status'] == 'planned' ? 'selected' : ''; ?>>مخطط</option>
                <option value="in_progress" <?php echo $project['status'] == 'in_progress' ? 'selected' : ''; ?>>قيد التنفيذ</option>
                <option value="completed" <?php echo $project['status'] == 'completed' ? 'selected' : ''; ?>>مكتمل</option>
                <option value="on_hold" <?php echo $project['status'] == 'on_hold' ? 'selected' : ''; ?>>معلق</option>
            </select>
        </div>

        <h3>النماذج المرتبطة</h3>
        <div class="row">
            <?php foreach ($property_types as $type): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($type['name']); ?></h5>
                            <p class="card-text">عدد: <?php echo htmlspecialchars($type['quantity']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h3>العقارات المرتبطة</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الرقم المتسلسل</th>
                        <th>النموذج</th>
                        <th>السعر</th>
                        <th>الحالة</th>
                        <th>الجاهزية</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($properties as $property): ?>
                    <tr>
                        <td class="editable" data-field="serial_number" data-id="<?php echo $property['id']; ?>"><?php echo htmlspecialchars($property['serial_number']); ?></td>
                        <td><?php echo htmlspecialchars(get_property_name($pdo, $property['property_type_id'])); ?></td>
                        <td class="editable" data-field="price" data-id="<?php echo $property['id']; ?>"><?php echo htmlspecialchars($property['price']); ?></td>
                        <td class="editable" data-field="status" data-id="<?php echo $property['id']; ?>"><?php echo htmlspecialchars($property['status']); ?></td>
                        <td class="editable" data-field="readiness" data-id="<?php echo $property['id']; ?>"><?php echo htmlspecialchars($property['readiness']); ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editProperty(<?php echo $property['id']; ?>)">تعديل</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteProperty(<?php echo $property['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <button class="btn btn-success" id="createPropertiesBtn" onclick="createProperties(<?php echo $project_id; ?>)">إنشاء عقارات</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let propertiesCreated = false; // Flag to ensure properties are created only once

        function updateProjectField(field, value) {
            $.post('../api/update_project_field.php', { id: <?php echo $project_id; ?>, field: field, value: value }, function(response) {
                if (response.success) {
                    alert('تم تحديث الحقل بنجاح');
                } else {
                    alert('حدث خطأ أثناء تحديث الحقل');
                }
            }, 'json');
        }

        function createProperties(projectId) {
            if (propertiesCreated) {
                alert('تم إنشاء العقارات مسبقًا.');
                return; // Prevent creating properties again
            }

            $.post('../api/create_properties.php', { project_id: projectId }, function(response) {
                if (response.success) {
                    alert('تم إنشاء العقارات بنجاح');
                    propertiesCreated = true; // Set flag to true after creation
                    loadProperties();
                } else {
                    alert('حدث خطأ أثناء إنشاء العقارات: ' + response.error);
                }
            }, 'json');
        }

        function loadProperties() {
            $.get('../api/get_properties.php', { project_id: <?php echo $project_id; ?> }, function(data) {
                $('#propertiesTable').html(data);
            });
        }

        $(document).ready(function() {
            loadProperties();

            $('.editable').click(function() {
                const field = $(this).data('field');
                const id = $(this).data('id');
                const currentValue = $(this).text();
                const input = $('<input type="text" class="form-control">').val(currentValue);
                $(this).html(input);
                input.focus();

                input.blur(function() {
                    const newValue = $(this).val();
                    $.post('../api/update_property.php', { id: id, field: field, value: newValue }, function(response) {
                        if (response.success) {
                            $(this).parent().text(newValue);
                        } else {
                            alert('حدث خطأ أثناء تحديث الحقل');
                            $(this).parent().text(currentValue);
                        }
                    }.bind(this));
                });
            });
        });
    </script>
</body>
</html>