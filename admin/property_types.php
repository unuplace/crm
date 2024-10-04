<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /crm/auth/login.php');
    exit();
}

// استرجاع جميع نماذج العقارات
$property_types = get_all_property_types($pdo);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة نماذج العقارات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>
<?php include '../includes/topnav.php'; ?>

<div class="container mt-4">
    <h2>إدارة نماذج العقارات</h2>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPropertyTypeModal">
        إضافة نموذج جديد
    </button>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>اسم النموذج</th>
                    <th>الاستخدام</th>
                    <th>نوع الوحدة</th>
                    <th>مساحة الأرض</th>
                    <th>مساحة البناء</th>
                    <th>عدد الطوابق</th>
                    <th>غرف النوم</th>
                    <th>المجالس</th>
                    <th>الصالات</th>
                    <th>دورات المياه</th>
                    <th>المطبخ</th>
                    <th>صورة المخطط</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($property_types as $property): ?>
                <tr>
                    <td><?php echo htmlspecialchars($property['name']); ?></td>
                    <td><?php echo htmlspecialchars($property['property_usage']); ?></td>
                    <td><?php echo htmlspecialchars($property['unit_type']); ?></td>
                    <td><?php echo htmlspecialchars($property['land_area']); ?> م²</td>
                    <td><?php echo htmlspecialchars($property['building_area']); ?> م²</td>
                    <td><?php echo htmlspecialchars($property['floors']); ?></td>
                    <td><?php echo htmlspecialchars($property['bedrooms']); ?></td>
                    <td><?php echo htmlspecialchars($property['halls']); ?></td>
                    <td><?php echo isset($property['living_rooms']) ? htmlspecialchars($property['living_rooms']) : 'غير محدد'; ?></td>
                    <td><?php echo htmlspecialchars($property['bathrooms']); ?></td>
                    <td><?php echo htmlspecialchars($property['kitchen']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($property['plan_image']); ?>" alt="مخطط" style="width: 100px;"></td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" onclick="editPropertyType(<?php echo $property['id']; ?>)">تعديل</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deletePropertyType(<?php echo $property['id']; ?>)">حذف</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Adding Property Type -->
<div class="modal fade" id="addPropertyTypeModal" tabindex="-1" aria-labelledby="addPropertyTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPropertyTypeModalLabel">إضافة نموذج عقار جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPropertyTypeForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم النموذج</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="property_usage" class="form-label">الاستخدام</label>
                        <select class="form-select" id="property_usage" name="property_usage" required>
                            <option value="سكني">سكني</option>
                            <option value="تجاري">تجاري</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="unit_type" class="form-label">نوع الوحدة</label>
                        <select class="form-select" id="unit_type" name="unit_type" required>
                            <option value="فيلا">فيلا</option>
                            <option value="دوبلكس">دوبلكس</option>
                            <option value="شقة">شقة</option>
                            <option value="بنتهاوس">بنتهاوس</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="land_area" class="form-label">مساحة الأرض (م²)</label>
                        <input type="number" class="form-control" id="land_area" name="land_area" required>
                    </div>
                    <div class="mb-3">
                        <label for="building_area" class="form-label">مساحة البناء (م²)</label>
                        <input type="number" class="form-control" id="building_area" name="building_area" required>
                    </div>
                    <div class="mb-3">
                        <label for="floors" class="form-label">عدد الطوابق</label>
                        <input type="number" class="form-control" id="floors" name="floors" required>
                    </div>
                    <div class="mb-3">
                        <label for="bedrooms" class="form-label">غرف النوم</label>
                        <input type="number" class="form-control" id="bedrooms" name="bedrooms" required>
                    </div>
                    <div class="mb-3">
                        <label for="halls" class="form-label">المجالس</label>
                        <input type="number" class="form-control" id="halls" name="halls" required>
                    </div>
                    <div class="mb-3">
                        <label for="living_rooms" class="form-label">الصالات</label>
                        <input type="number" class="form-control" id="living_rooms" name="living_rooms" required>
                    </div>
                    <div class="mb-3">
                        <label for="bathrooms" class="form-label">دورات المياه</label>
                        <input type="number" class="form-control" id="bathrooms" name="bathrooms" required>
                    </div>
                    <div class="mb-3">
                        <label for="kitchen" class="form-label">المطبخ</label>
                        <input type="text" class="form-control" id="kitchen" name="kitchen" required>
                    </div>
                    <div class="mb-3">
                        <label for="plan_image" class="form-label">صورة المخطط</label>
                        <input type="file" class="form-control" id="plan_image" name="plan_image" required>
                    </div>
                    <button type="submit" class="btn btn-primary">إضافة النموذج</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Editing Property Type -->
<div class="modal fade" id="editPropertyTypeModal" tabindex="-1" aria-labelledby="editPropertyTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPropertyTypeModalLabel">تعديل نموذج العقار</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPropertyTypeForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id" name="id">
                    <!-- Add the same fields as in the add form, but with "edit_" prefix -->
                    <!-- For example: -->
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">اسم النموذج</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_property_usage<?php echo $property['id']; ?>" class="form-label">الاستخدام</label>
                        <select class="form-select" id="edit_property_usage<?php echo $property['id']; ?>" name="property_usage" required>
                            <option value="سكني" <?php echo $property['property_usage'] == 'سكني' ? 'selected' : ''; ?>>سكني</option>
                            <option value="تجاري" <?php echo $property['property_usage'] == 'تجاري' ? 'selected' : ''; ?>>تجاري</option>
                        </select>
                    </div>
                    <!-- Add all other fields here -->
                    <div class="mb-3">
                        <label for="edit_plan_image" class="form-label">صورة المخطط</label>
                        <input type="file" class="form-control" id="edit_plan_image" name="plan_image">
                    </div>
                    <button type="submit" class="btn btn-primary">تحديث النموذج</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editPropertyType(id) {
    // Fetch property type data and populate the edit form
    fetch(`../api/get_property_type.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_name').value = data.name;
            // Populate other fields
            $('#editPropertyTypeModal').modal('show');
        });
}

function deletePropertyType(id) {
    if (confirm('هل أنت متأكد من حذف هذا النموذج؟')) {
        fetch('../api/delete_property_type.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء حذف النموذج');
            }
        });
    }
}

document.getElementById('addPropertyTypeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('../api/add_property_type.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إضافة النموذج بنجاح');
            location.reload();
        } else {
            alert('حدث خطأ أثناء إضافة النموذج: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إضافة النموذج');
    });
});

document.getElementById('editPropertyTypeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('../api/update_property_type.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('حدث خطأ أثناء تحديث النموذج');
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>