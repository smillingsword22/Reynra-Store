<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <?= view('layouts/head-page-meta', ['title' => 'Transaksi Pengeluaran']) ?>
  <?= view('layouts/head-css') ?>

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<!-- [Head] end -->

<!-- [Body] Start -->
<body>
  <?= view('layouts/layout-vertical') ?>

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      <?= view('layouts/breadcrumb', [
        'breadcrumb_item' => 'Pribadi',
        'breadcrumb_item_active' => 'Transaksi'
      ]) ?>

      <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Saldo Pribadi</h5>
                </div>
                <div class="card-body">
                    <h4>Rp <?= number_format($saldo->saldo, 0, ',', '.') ?></h4>
                </div>
            </div>
        </div>
        <!-- [Table] Start -->
        <div class="col-md-12 col-xl-12">
          <div class="card">
            <div class="card-body">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transaksiModal">
                Tambah Transaksi
              </button>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Daftar Transaksi</h5>
            </div>
            <div class="card-body">
              <table id="pengeluaranTable" class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>ID Transaksi</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($transaksi as $item): ?>
                    <tr>
                      <td><?= esc($item['id']) ?></td>
                      <td><?= esc($item['kategori_nama']) ?></td>
                      <td>
                        <span class="text-<?= $item['tipe'] === 'masuk' ? 'success' : 'danger' ?>">
                          Rp <?= number_format($item['jumlah'], 0, ',', '.') ?>
                        </span>
                      </td>
                      <td><?= date('d M Y', strtotime($item['tanggal'])) ?></td>
                      <td>
                        <?php if ($item['tipe'] === 'masuk'): ?>
                          <span class="badge bg-success">Pemasukan</span>
                        <?php else: ?>
                          <span class="badge bg-danger">Pengeluaran</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- [Table] End -->
      </div>

      <!-- [Add Transaction Button] Start -->
      
      <!-- [Add Transaction Button] End -->
    </div>
  </div>
  <!-- [ Main Content ] end -->

  <?= view('layouts/footer-block') ?>

  <!-- Modal Tambah Transaksi -->
  <!-- Modal -->
<div class="modal fade" id="transaksiModal" tabindex="-1" aria-labelledby="transaksiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="transaksiModalLabel">Tambah Transaksi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= site_url('pribadi/transaksi/store') ?>" method="POST">
        <div class="modal-body">
          <?= csrf_field() ?>

          <!-- Tanggal -->
          <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
          </div>

          <!-- Kategori -->
          <div class="mb-3">
            <label for="kategori_id" class="form-label">Kategori</label>
            <select class="form-select" id="kategori_id" name="kategori_id" required>
              <option value="">Pilih Kategori</option>
              <!-- List kategori akan diisi dari database -->
              <?php foreach ($kategori_transaksi as $kategori): ?>
                <option value="<?= $kategori['id'] ?>"><?= $kategori['nama'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Tipe Transaksi -->
          <div class="mb-3">
            <label for="tipe" class="form-label">Tipe Transaksi</label>
            <select class="form-select" id="tipe" name="tipe" required>
              <option value="masuk">Pemasukan</option>
              <option value="keluar">Pengeluaran</option>
            </select>
          </div>

          <!-- Jumlah -->
          <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" required>
          </div>

          <!-- Keterangan -->
          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </div>
      </form>
    </div>
  </div>
</div>
  <?= view('layouts/footer-js') ?>

  <!-- [Page Specific JS] start -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function() {
      $('#pengeluaranTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(disaring dari total _MAX_ data)",
        },
        responsive: true
      });
    });
  </script>

  <?php if (session()->getFlashdata('success')): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          icon: 'success',
          title: 'Sukses!',
          text: '<?= session()->getFlashdata('success') ?>',
          showConfirmButton: false,
          timer: 2500
        });
      });
    </script>
  <?php endif; ?>

  <!-- [Page Specific JS] end -->

</body>
<!-- [Body] end -->

</html>
