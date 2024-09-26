// هذا الملف يحتوي على الوظائف العامة المستخدمة في جميع أنحاء الموقع

document.addEventListener('DOMContentLoaded', function() {
    // تهيئة عناصر Bootstrap
    initializeBootstrapComponents();

    // إضافة مستمع الحدث لزر تسجيل الخروج
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', logout);
    }
});

function initializeBootstrapComponents() {
    // تهيئة التلميحات
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // تهيئة النوافذ المنبثقة
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

function logout(event) {
    event.preventDefault();
    if (confirm('هل أنت متأكد من رغبتك في تسجيل الخروج؟')) {
        fetch('/auth/logout.php', {
            method: 'POST'
        })
        .then(() => {
            window.location.href = '/auth/login.php';
        })
        .catch(error => console.error('Error:', error));
    }
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('ar-SA', options);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('ar-SA', { style: 'currency', currency: 'SAR' }).format(amount);
}

// يمكنك إضافة المزيد من الوظائف العامة هنا