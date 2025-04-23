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

  <!-- [Main Content] start -->
<div class="pc-container">
  <div class="pc-content">

    <?= view('layouts/breadcrumb', [
      'breadcrumb_item' => 'Dashboard',
      'breadcrumb_item_active' => 'Admin'
    ]) ?>

    <div class="row">
      <!-- [Saldo Toko] -->
      <div class="col-md-6 col-xl-6 py-4">
        <div class="card h-100">
          <div class="card-body">
            <h6 class="mb-2 f-w-400 text-muted">Saldo Toko</h6>
            <h4 class="mb-3">
              Rp<?= number_format($saldoToko, 0, ',', '.') ?>
            </h4>
            <p class="mb-0 text-muted text-sm">
              Saldo toko saat ini
            </p>
          </div>
        </div>
      </div>

      <!-- [Saldo Pribadi] -->
      <div class="col-md-6 col-xl-6 py-4">
        <div class="card h-100">
          <div class="card-body">
            <h6 class="mb-2 f-w-400 text-muted">Saldo Pribadi</h6>
            <h4 class="mb-3">
              Rp<?= number_format($saldoPribadi, 0, ',', '.') ?>
            </h4>
            <p class="mb-0 text-muted text-sm">
              Saldo pribadi saat ini
            </p>
          </div>
        </div>
      </div>

      <!-- [Total Penjualan] -->
      <div class="col-md-6 col-xl-3">
        <div class="card h-100">
          <div class="card-body">
            <h6 class="mb-2 f-w-400 text-muted">Total Penjualan</h6>
            <h4 class="mb-3">
              Rp<?= number_format($totalPenjualan, 0, ',', '.') ?>
              <span class="badge bg-light-primary border border-primary">
                <i class="ti ti-trending-up"></i> <?= $persenPenjualan ?>%
              </span>
            </h4>
            <p class="mb-0 text-muted text-sm">
              You made an extra 
              <span class="text-primary"><?= number_format($extraPenjualan, 0, ',', '.') ?></span> this year
            </p>
          </div>
        </div>
      </div>

      <!-- [Total Keuntungan] -->
      <div class="col-md-6 col-xl-3">
        <div class="card h-100">
          <div class="card-body">
            <h6 class="mb-2 f-w-400 text-muted">Total Keuntungan</h6>
            <h4 class="mb-3">
              Rp<?= number_format($totalKeuntungan, 0, ',', '.') ?>
              <span class="badge bg-light-success border border-success">
                <i class="ti ti-trending-up"></i> <?= $persenKeuntungan ?>%
              </span>
            </h4>
            <p class="mb-0 text-muted text-sm">
              You made an extra 
              <span class="text-success"><?= number_format($extraKeuntungan, 0, ',', '.') ?></span> this year
            </p>
          </div>
        </div>
      </div>

      <!-- [Jumlah Produk Terjual] -->
      <div class="col-md-6 col-xl-3">
        <div class="card h-100">
          <div class="card-body">
            <h6 class="mb-2 f-w-400 text-muted">Jumlah Produk Terjual</h6>
            <h4 class="mb-3">
              <?= $jumlahProdukTerjual ?> Produk
            </h4>
          </div>
        </div>
      </div>

      <!-- [Total Pengeluaran] -->
      <div class="col-md-6 col-xl-3">
        <div class="card h-100">
          <div class="card-body">
            <h6 class="mb-2 f-w-400 text-muted">Total Pengeluaran</h6>
            <h4 class="mb-3">
              Rp<?= number_format($totalPengeluaran, 0, ',', '.') ?>
              <span class="badge bg-light-danger border border-danger">
                <i class="ti ti-trending-down"></i> <?= $persenPengeluaran ?>%
              </span>
            </h4>
            <p class="mb-0 text-muted text-sm">
              You made an extra 
              <span class="text-danger"><?= number_format($extraPengeluaran, 0, ',', '.') ?></span> this year
            </p>
          </div>
        </div>
      </div>

    </div> <!-- row -->

    <!-- Grafik Keuangan dan Aktivitas Terbaru -->

    <div class="row">
      <div class="col-md-12 col-xl-8 py-4">
        <h5 class="mb-3">Grafik Keuangan</h5>
        <div class="card mb-2">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="mb-0">Keuangan</h5>
              <div>
                <button class="btn btn-sm btn-outline-primary me-2" onclick="loadChart('month')">Sebulan</button>
                <button class="btn btn-sm btn-outline-secondary" onclick="loadChart('year')">Setahun</button>
              </div>
            </div>
            <div id="grafikKeuangan" style="height: 350px;"></div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-xl-4 py-4">
        <h5 class="mb-3">Aktivitas Terbaru</h5>
        <?php foreach ($riwayat as $item): ?>
          <div class="card mb-2">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                <div class="me-3">
                  <i class="ti <?= $item['jenis'] == 'penjualan' ? 'ti-arrow-up-right text-success' : 'ti-arrow-down-right text-danger' ?> fs-4"></i>
                </div>
                <div>
                  <h6 class="mb-0"><?= ucfirst($item['jenis']) ?></h6>
                  <small class="text-muted">Rp<?= number_format($item['jumlah'], 0, ',', '.') ?> - <?= date('d M Y', strtotime($item['tanggal'])) ?></small>
                </div>
              </div>
              <span class="badge <?= $item['jenis'] == 'penjualan' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>">
                <?= $item['jenis'] == 'penjualan' ? '+' : '-' ?>Rp<?= number_format($item['keuntungan'], 0, ',', '.') ?>
              </span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div> <!-- pc-content -->
</div> <!-- pc-container -->
<!-- [Main Content] end -->


  <?= view('layouts/footer-block') ?>

  <!-- [Page Specific JS] start -->
  <script src="<?= base_url(); ?>assets/js/plugins/apexcharts.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/pages/dashboard-default.js"></script>
  <!-- [Page Specific JS] end -->
  
  <?= view('layouts/footer-js') ?>

  <script>
    let chart; // buat variabel chart global
    const chartEl = document.querySelector("#grafikKeuangan");

    const options = {
      chart: {
        type: "line",
        height: 350,
        toolbar: { show: false }
      },
      stroke: {
        curve: 'smooth',
        width: 3
      },
      dataLabels: { enabled: false },
      series: [],
      xaxis: {
        categories: [],
        labels: {
          style: {
            fontSize: '12px'
          }
        }
      },
      yaxis: {
        labels: {
          formatter: val => "Rp" + val.toLocaleString(),
          style: {
            fontSize: '12px'
          }
        }
      },
      colors: ['#6C5DD3', '#FF6A8D', '#28a745'], 
      tooltip: {
        y: {
          formatter: val => "Rp" + val.toLocaleString()
        }
      }
    };

    // Auto load sebulan pas page load
    window.addEventListener('DOMContentLoaded', () => {
      loadChart('month');
    });
  </script>


  <script>
    function loadChart(type) {
      fetch(`<?= base_url(); ?>/grafik-data?type=${type}`)
        .then(res => res.json())
        .then(data => {
          if (chart) {
            chart.updateOptions({
              xaxis: { categories: data.labels },
              series: data.series
            });
          } else {
            options.series = data.series;
            options.xaxis.categories = data.labels;
            chart = new ApexCharts(chartEl, options);
            chart.render();
          }
        });
    }
  </script>

</body>
<!-- [Body] end -->
</html>
