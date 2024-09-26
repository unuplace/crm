<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
  <div class="position-sticky pt-3">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/admin/dashboard.php') ? 'active' : ''; ?>" href="/telad/admin/dashboard.php">
          <i class="fas fa-home"></i> لوحة التحكم
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/admin/projects.php') ? 'active' : ''; ?>" href="/telad/admin/projects.php">
          <i class="fas fa-project-diagram"></i> المشاريع
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/admin/team.php') ? 'active' : ''; ?>" href="/telad/admin/team.php">
          <i class="fas fa-users"></i> الفريق
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/admin/potential_clients.php') ? 'active' : ''; ?>" href="/telad/admin/potential_clients.php">
          <i class="fas fa-user-tie"></i> العملاء المحتملين
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/telad/admin/reports.php') ? 'active' : ''; ?>" href="/telad/admin/reports.php">
          <i class="fas fa-chart-bar"></i> التقارير
        </a>
      </li>
    </ul>
  </div>
</nav>