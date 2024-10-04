<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

$project_id = $_GET['project_id'] ?? null;

if ($project_id) {
    $properties = get_properties_by_project($pdo, $project_id);
    
    echo '<table class="table">';
    echo '<thead><tr><th>الرقم المتسلسل</th><th>النموذج</th><th>السعر</th><th>الحالة</th><th>الجاهزية</th></tr></thead>';
    echo '<tbody>';
    foreach ($properties as $property) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($property['serial_number']) . '</td>';
        echo '<td>' . htmlspecialchars($property['property_type_name']) . '</td>';
        echo '<td>' . htmlspecialchars($property['price']) . '</td>';
        echo '<td>' . htmlspecialchars($property['status']) . '</td>';
        echo '<td>' . htmlspecialchars($property['readiness']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
} else {
    echo 'Missing project ID';
}