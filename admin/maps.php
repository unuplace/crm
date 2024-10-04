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
    $properties = get_properties_by_project($pdo, $project_id); // Fetch properties related to the project
    $map = get_map_by_project($pdo, $project_id); // Fetch map related to the project
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
    <title>خريطة تفاعلية للمشروع</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #map-container {
            position: relative;
            width: 100%;
            height: 500px;
            border: 1px solid #ccc;
            overflow: hidden;
        }
        .property-area {
            position: absolute;
            border: 2px solid transparent;
            cursor: pointer;
        }
        .property-area.available {
            background-color: rgba(40, 167, 69, 0.5); /* Green for available */
        }
        .property-area.reserved {
            background-color: rgba(255, 193, 7, 0.5); /* Yellow for reserved */
        }
        .property-area.sold {
            background-color: rgba(220, 53, 69, 0.5); /* Red for sold */
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/topnav.php'; ?>

    <div class="container mt-4">
        <h2>خريطة تفاعلية للمشروع: <?php echo htmlspecialchars($project['name']); ?></h2>
        
        <form id="uploadMapForm" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="mapImage" class="form-label">تحميل صورة الخريطة</label>
        <input type="file" class="form-control" id="mapImage" name="image" required>
    </div>
    <button type="submit" class="btn btn-primary">رفع الصورة</button>
</form>

        <div id="map-container">
            <?php if ($map): ?>
                <img id="mapImage" src="../uploads/maps/<?php echo htmlspecialchars($map['map_image']); ?>" alt="Map" style="width: 100%; height: auto;">
            <?php else: ?>
                <p>لا توجد خريطة متاحة لهذا المشروع.</p>

            <?php endif; ?>
            <!-- سيتم إضافة مناطق العقارات هنا -->


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
                    </tr>
                    <script>
                        // إضافة منطقة قابلة للنقر لكل عقار
                        addClickableArea(<?php echo $property['x']; ?>, <?php echo $property['y']; ?>, 50, 50, '<?php echo $property['serial_number']; ?>', '<?php echo $property['status']; ?>');
                    </script>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <button class="btn btn-success" id="createPropertiesBtn" onclick="createProperties(<?php echo $project_id; ?>)">إنشاء عقارات</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let propertiesCreated = false; // Flag to ensure properties are created only once

        function addClickableArea(x, y, width, height, serialNumber, status) {
            const area = $('<div>')
                .addClass('property-area ' + status)
                .css({
                    left: x + 'px',
                    top: y + 'px',
                    width: width + 'px',
                    height: height + 'px'
                })
                .attr('data-serial', serialNumber)
                .appendTo('#map-container');

            area.click(function() {
                const propertySerial = $(this).data('serial');
                alert('تم النقر على العقار: ' + propertySerial);
                // يمكنك إضافة المزيد من الوظائف هنا، مثل عرض تفاصيل العقار
            });
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
        });

        function upload_map_image($pdo, $project_id, $image) {
    $target_dir = "../uploads/maps/";
    $target_file = $target_dir . basename($image["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($image["tmp_name"]);
    if($check === false) {
        return false; // Not an image
    }

    // Check file size
    if ($image["size"] > 500000) {
        return false; // File is too large
    }

    // Allow certain file formats
    if(!in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
        return false; // Invalid file format
    }

    // Try to upload file
    if (move_uploaded_file($image["tmp_name"], $target_file)) {
        // Save the image path in the database
        $stmt = $pdo->prepare("INSERT INTO maps (project_id, map_image) VALUES (?, ?)");
        return $stmt->execute([$project_id, basename($image["name"])]);
    } else {
        return false; // Error uploading file
    }
}


// تحميل الصورة
$(document).ready(function() {
    $('#uploadMapForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('project_id', <?php echo $project_id; ?>); // إضافة معرف المشروع

        $.ajax({
            url: '../api/upload_map.php', // تأكد من أن لديك ملف API لتحميل الصورة
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    alert('تم رفع الصورة بنجاح');
                    location.reload(); // إعادة تحميل الصفحة لعرض الصورة الجديدة
                } else {
                    alert('حدث خطأ أثناء رفع الصورة: ' + response.error);
                }
            },
            error: function() {
                alert('حدث خطأ أثناء رفع الصورة');
            }
        });
    });
});

    </script>
</body>
</html>