<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">شركة التلاد للتطوير العقاري</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/crm/admin/dashboard.php') ? 'active' : ''; ?>" href="/crm/admin/dashboard.php">لوحة التحكم</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/crm/admin/projects.php') ? 'active' : ''; ?>" href="/crm/admin/projects.php">المشاريع</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/crm/admin/team.php') ? 'active' : ''; ?>" href="/crm/admin/team.php">الفريق</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/crm/admin/potential_clients.php') ? 'active' : ''; ?>" href="/crm/admin/potential_clients.php">العملاء المحتملين</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/crm/admin/reports.php') ? 'active' : ''; ?>" href="/crm/admin/reports.php">التقارير</a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="/crm/auth/logout.php">تسجيل الخروج</a>
        </li>
      </ul>
    </div>
  </div>
</nav>