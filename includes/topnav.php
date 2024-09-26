<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">شركة التلاد للتطوير العقاري</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/admin/dashboard.php') ? 'active' : ''; ?>" href="/telad/admin/dashboard.php">لوحة التحكم</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/admin/projects.php') ? 'active' : ''; ?>" href="/telad/admin/projects.php">المشاريع</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/admin/team.php') ? 'active' : ''; ?>" href="/telad/admin/team.php">الفريق</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/admin/potential_clients.php') ? 'active' : ''; ?>" href="/telad/admin/potential_clients.php">العملاء المحتملين</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/admin/reports.php') ? 'active' : ''; ?>" href="/telad/admin/reports.php">التقارير</a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="/telad/auth/logout.php">تسجيل الخروج</a>
        </li>
      </ul>
    </div>
  </div>
</nav>