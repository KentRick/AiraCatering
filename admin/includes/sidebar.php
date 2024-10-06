
<nav id="sidebar">
  <div class="custom-menu">
    <button type="button" id="sidebarCollapse" class="btn btn-primary">
      <i class="fa fa-bars"></i>
      <span class="sr-only">Toggle Menu</span>
    </button>
  </div>
  <h1><a href="index.php" class="logo">Aira Cater</a></h1>
  <ul class="list-unstyled components mb-5">
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
      <a href="dashboard.php"><span class="fas fa-chart-line mr-3"></span> Dashboard</a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'reservation_list.php' ? 'active' : ''; ?>">
      <a href="reservation_list.php"><span class="fa fa-user mr-3"></span> Reservation List</a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'reservation_report.php' ? 'active' : ''; ?>">
      <a href="reservation_report.php"><span class="fa fa-sticky-note mr-3"></span> Reservation Report</a>
    </li>
    <br>
    <h1><a class="logo">Maintenance</a></h1>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'edit_services.php' ? 'active' : ''; ?>">
      <a href="edit_services.php"><span class="far fa-file mr-3"></span> Edit Services</a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'edit_packages.php' ? 'active' : ''; ?>">
      <a href="edit_packages.php"><span class="far fa-file mr-3"></span> Edit Packages</a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'edit_gallery.php' ? 'active' : ''; ?>">
      <a href="edit_gallery.php"><span class="far fa-file mr-3"></span> Edit Gallery</a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'edit_menu.php' ? 'active' : ''; ?>">
      <a href="edit_menu.php"><span class="far fa-file mr-3"></span> Menu Management</a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
      <a href="settings.php"><span class="fas fa-cogs mr-3"></span> Settings</a>
    </li>
  </ul>
</nav>
