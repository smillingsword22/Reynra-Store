<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <?= view('layouts/head-page-meta', ['title' => 'Dashboard']) ?>
  <?= view('layouts/head-css') ?>
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body @@bodySetup>
  <?= view('layouts/layout-vertical') ?>
  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      <?= view('layouts/breadcrumb', [
        'breadcrumb_item' => 'Dashboard',
        'breadcrumb_item_active' => 'Admin'
      ]) ?>
      <!-- [ Main Content ] start -->
      <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Total Penjualan</h6>
              <h4 class="mb-3">Rp<?= number_format($totalPenjualan, 0, ',', '.') ?> <span class="badge bg-light-primary border border-primary"><i class="ti ti-trending-up"></i> <?= $persenPenjualan ?>%</span></h4>
              <p class="mb-0 text-muted text-sm">You made an extra <span class="text-primary"><?= number_format($extraPenjualan, 0, ',', '.') ?></span> this year</p>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Total Keuntungan</h6>
              <h4 class="mb-3">Rp<?= number_format($totalKeuntungan, 0, ',', '.') ?> <span class="badge bg-light-success border border-success"><i class="ti ti-trending-up"></i> <?= $persenKeuntungan ?>%</span></h4>
              <p class="mb-0 text-muted text-sm">You made an extra <span class="text-success"><?= number_format($extraKeuntungan, 0, ',', '.') ?></span> this year</p>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Jumlah Produk Terjual</h6>
              <h4 class="mb-3"><?= $jumlahProdukTerjual ?> Produk 
            </div>
          </div>
        </div>

        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Total Pengeluaran</h6>
              <h4 class="mb-3">Rp<?= number_format($totalPengeluaran, 0, ',', '.') ?> <span class="badge bg-light-danger border border-danger"><i class="ti ti-trending-down"></i> <?= $persenPengeluaran ?>%</span></h4>
              <p class="mb-0 text-muted text-sm">You made an extra <span class="text-danger"><?= number_format($extraPengeluaran, 0, ',', '.') ?></span> this year</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
  <?= view('layouts/footer-block') ?>

  <!-- [Page Specific JS] start -->
  <script src="<?= base_url(); ?>assets/js/plugins/apexcharts.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/pages/dashboard-default.js"></script>
  <!-- [Page Specific JS] end -->
  <?= view('layouts/footer-js') ?>
</body>
<!-- [Body] end -->

</html>