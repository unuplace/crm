document.addEventListener('DOMContentLoaded', function() {
    // تحميل بيانات لوحة التحكم الخاصة بالموظف
    loadEmployeeDashboardData();

    // تهيئة الرسوم البيانية
    initializeCharts();

    // إضافة مستمع الحدث لنموذج تحديث الإحصائيات اليومية
    document.getElementById('dailyStatsForm').addEventListener('submit', updateDailyStats);

    // إضافة مستمع الحدث لزر تغيير كلمة المرور
    document.getElementById('changePasswordBtn').addEventListener('click', changePassword);
});

function loadEmployeeDashboardData() {
    fetch('/api/employee-dashboard-data.php')
    .then(response => response.json())
    .then(data => {
        // تحديث معلومات الموظف وإحصائياته
        document.getElementById('employeeName').textContent = data.fullName;
        document.getElementById('employeeRole').textContent = data.role;
        document.getElementById('employeeProject').textContent = data.projectName;
        document.getElementById('dailySales').textContent = data.dailySales;
        document.getElementById('dailyCalls').textContent = data.dailyCalls;
        document.getElementById('monthlyTarget').textContent = data.monthlyTarget;
        document.getElementById('monthlyAchievement').textContent = data.monthlyAchievement;
    })
    .catch(error => console.error('Error:', error));
}

function initializeCharts() {
    // تهيئة الرسم البياني لأداء المبيعات الشهري
    const salesChartCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesChartCtx, {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'المبيعات',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        }
    });

    // تهيئة الرسم البياني لأداء الاتصالات الشهري
    const callsChartCtx = document.getElementById('callsChart').getContext('2d');
    new Chart(callsChartCtx, {
        type: 'bar',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'الاتصالات',
                data: [65, 59, 80, 81, 56, 55],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function updateDailyStats(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch('/api/update-daily-stats.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم تحديث الإحصائيات اليومية بنجاح');
            loadEmployeeDashboardData(); // إعادة تحميل البيانات لتحديث العرض
        } else {
            alert('حدث خطأ أثناء تحديث الإحصائيات اليومية');
        }
    })
    .catch(error => console.error('Error:', error));
}

function changePassword() {
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (newPassword !== confirmPassword) {
        alert('كلمة المرور الجديدة وتأكيدها غير متطابقين');
        return;
    }

    fetch('/api/change-password.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            currentPassword: currentPassword,
            newPassword: newPassword
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم تغيير كلمة المرور بنجاح');
            $('#changePasswordModal').modal('hide');
        } else {
            alert(data.message || 'حدث خطأ أثناء تغيير كلمة المرور');
        }
    })
    .catch(error => console.error('Error:', error));
}