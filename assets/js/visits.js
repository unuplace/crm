document.addEventListener('DOMContentLoaded', function() {
    // إضافة مستمع الحدث لزر إضافة زيارة جديدة
    document.getElementById('addVisitBtn').addEventListener('click', addVisit);

    // إضافة مستمعات الأحداث لأزرار عرض وحذف الزيارات
    document.querySelectorAll('.view-visit-btn').forEach(btn => {
        btn.addEventListener('click', () => viewVisit(btn.dataset.id));
    });
    document.querySelectorAll('.delete-visit-btn').forEach(btn => {
        btn.addEventListener('click', () => deleteVisit(btn.dataset.id));
    });
});

function addVisit(event) {
    event.preventDefault();
    const form = document.getElementById('addVisitForm');
    const formData = new FormData(form);

    fetch('/api/add-visit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تمت إضافة الزيارة بنجاح');
            location.reload();
        } else {
            alert('حدث خطأ أثناء إضافة الزيارة');
        }
    })
    .catch(error => console.error('Error:', error));
}

function viewVisit(id) {
    fetch(`/api/get-visit.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // عرض تفاصيل الزيارة في نافذة منبثقة أو قسم مخصص في الصفحة
            showVisitDetails(data.visit);
        } else {
            alert('حدث خطأ أثناء جلب تفاصيل الزيارة');
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteVisit(id) {
    if (confirm('هل أنت متأكد من رغبتك في حذف هذه الزيارة؟')) {
        fetch(`/api/delete-visit.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم حذف الزيارة بنجاح');
                location.reload();
            } else {
                alert('حدث خطأ أثناء حذف الزيارة');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function showVisitDetails(visit) {
    // قم بإنشاء وعرض نافذة منبثقة أو قسم لعرض تفاصيل الزيارة
    // يمكنك استخدام مكتبة مثل Bootstrap Modal لهذا الغرض
}