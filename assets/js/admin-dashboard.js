document.addEventListener('DOMContentLoaded', function() {
    // تحميل بيانات لوحة التحكم عبر AJAX
    loadDashboardData();

    // تهيئة الرسوم البيانية
    initializeCharts();
});

function loadDashboardData() {
    // قم بتنفيذ استدعاء AJAX لجلب بيانات لوحة التحكم
    fetch('/api/dashboard-data.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-sales').textContent = data.totalSales;
            document.getElementById('total-reservations').textContent = data.totalReservations;
            document.getElementById('total-potential-clients').textContent = data.totalPotentialClients;
        })
        .catch(error => console.error('Error:', error));
}

function initializeCharts() {
    // تهيئة الرسوم البيانية باستخدام Chart.js
    const projectsCtx = document.getElementById('projects-performance-chart').getContext('2d');
    new Chart(projectsCtx, {
        type: 'bar',
        data: {
            labels: ['المشروع 1', 'المشروع 2', 'المشروع 3'],
            datasets: [{
                label: 'المبيعات',
                data: [12, 19, 3],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
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

    // يمكنك إضافة المزيد من الرسوم البيانية هنا
}