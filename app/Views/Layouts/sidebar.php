<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="<?= base_url('dashboard') ?>" class="b-brand text-primary">
        <!-- ========   Change your logo from here   ============ -->
        <img src="<?= base_url('assets/images/logo-dark.svg') ?>" class="img-fluid logo-lg" alt="logo">
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <?= view('Layouts/menu-list') ?>
      </ul>
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end -->
