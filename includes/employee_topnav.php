<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">لوحة تحكم الموظف</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/employee/dashboard.php') ? 'active' : ''; ?>" href="/telad/employee/dashboard.php">الرئيسية</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/employee/visits.php') ? 'active' : ''; ?>" href="/telad/employee/visits.php">الزيارات</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/employee/potential_clients.php') ? 'active' : ''; ?>" href="/telad/employee/potential_clients.php">العملاء المحتملين</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/employee/statistics.php') ? 'active' : ''; ?>" href="/telad/employee/statistics.php">الإحصائيات</a>
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