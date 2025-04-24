<!DOCTYPE html>
<html lang="en">

<head>
  <?= view('layouts/head-page-meta', ['title' => 'Rencana Keuangan']) ?>
  <?= view('layouts/head-css') ?>

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <?= view('layouts/layout-vertical') ?>

  <div class="pc-container">
    <div class="pc-content">
      <?= view('layouts/breadcrumb', [
        'breadcrumb_item' => 'Pribadi',
        'breadcrumb_item_active' => 'Rencana Keuangan'
      ]) ?>

      <div class="row">
        <div class="col-md-12 col-xl-12">
          <div class="card">
            <div class="card-body">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRencanaModal">
                Tambah Rencana Keuangan
              </button>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Daftar Rencana Keuangan</h5>
            </div>
            <div class="card-body">
              <table id="rencanaKeuanganTable" class="table table-striped">
                <thead>
                  <tr>
                    <th>ID Rencana</th>
                    <th>Kategori</th>
                    <th>Target</th>
                    <th>Realisasi</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rencanaKeuangan as $item): ?>
                    <tr>
                      <td><?= $item['id'] ?></td>
                      <td><?= esc($item['kategori_nama']) ?></td>
                      <td>Rp <?= number_format($item['target'], 0, ',', '.') ?></td>
                      <td>Rp <?= number_format($item['realisasi'], 0, ',', '.') ?></td>
                      <td><?= date('F', mktime(0, 0, 0, $item['bulan'], 10)) ?></td>
                      <td><?= $item['tahun'] ?></td>
                      <td><?= esc($item['keterangan']) ?></td>
                      <td>
                        <button class="btn btn-sm btn-warning" onclick="editRencana(<?= $item['id'] ?>)">Edit</button>
                        <a href="<?= site_url('pribadi/rencana/delete/'.$item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for Adding Rencana Keuangan -->
  <div class="modal fade" id="addRencanaModal" tabindex="-1" aria-labelledby="addRencanaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addRencanaModalLabel">Tambah Rencana Keuangan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="<?= site_url('pribadi/rencana/store') ?>">
            <div class="mb-3">
              <label for="kategori_id" class="form-label">Kategori</label>
              <select class="form-select" id="kategori_id" name="kategori_id" required>
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategori as $kategoriItem): ?>
                  <option value="<?= $kategoriItem['id'] ?>"><?= esc($kategoriItem['nama']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="target" class="form-label">Target</label>
              <input type="number" class="form-control" id="target" name="target" required step="0.01" min="0">
            </div>
            <div class="mb-3">
              <label for="realisasi" class="form-label">Realisasi</label>
              <input type="number" class="form-control" id="realisasi" name="realisasi" value="0" step="0.01" min="0">
            </div>
            <div class="mb-3">
              <label for="bulan" class="form-label">Bulan</label>
              <select class="form-select" id="bulan" name="bulan" required>
                <option value="">Pilih Bulan</option>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                  <option value="<?= $i ?>"><?= date('F', mktime(0, 0, 0, $i, 10)) ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="tahun" class="form-label">Tahun</label>
              <input type="number" class="form-control" id="tahun" name="tahun" required min="2022" max="9999">
            </div>
            <div class="mb-3">
              <label for="keterangan" class="form-label">Keterangan</label>
              <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End Modal -->

  <!-- Modal Edit Rencana Keuangan -->
  <div class="modal fade" id="editRencanaModal" tabindex="-1" aria-labelledby="editRencanaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editRencanaModalLabel">Edit Rencana Keuangan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editRencanaForm" action="<?= site_url('pribadi/rencana/update') ?>" method="POST">
            <input type="hidden" id="edit_rencana_id" name="id">

            <div class="mb-3">
              <label for="edit_kategori_id" class="form-label">Kategori</label>
              <select class="form-select" id="edit_kategori_id" name="kategori_id" required>
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategori as $kategoriItem): ?>
                  <option value="<?= $kategoriItem['id'] ?>"><?= $kategoriItem['nama'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="edit_target" class="form-label">Target</label>
              <input type="number" class="form-control" id="edit_target" name="target" required>
            </div>

            <div class="mb-3">
              <label for="edit_realisasi" class="form-label">Realisasi</label>
              <input type="number" class="form-control" id="edit_realisasi" name="realisasi" required>
            </div>

            <div class="mb-3">
              <label for="edit_bulan" class="form-label">Bulan</label>
              <select class="form-select" id="edit_bulan" name="bulan" required>
                <option value="">Pilih Bulan</option>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                  <option value="<?= $i ?>"><?= date('F', mktime(0, 0, 0, $i, 10)) ?></option>
                <?php endfor; ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="edit_tahun" class="form-label">Tahun</label>
              <input type="number" class="form-control" id="edit_tahun" name="tahun" required>
            </div>

            <div class="mb-3">
              <label for="edit_keterangan" class="form-label">Keterangan</label>
              <textarea class="form-control" id="edit_keterangan" name="keterangan"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </form>
        </div>
      </div>
    </div>
  </div>



  <?= view('layouts/footer-block') ?>
  <?= view('layouts/footer-js') ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#rencanaKeuanganTable').DataTable({
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

  <script>
    function editRencana(id) {
      // Ambil data rencana berdasarkan ID
      $.ajax({
        url: '<?= site_url("pribadi/rencana/edit/") ?>' + id,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
          console.log(data);
          // Masukkan data ke dalam form modal
          $('#rencana_id').val(data.id);
          $('#edit_kategori_id').val(data.kategori_id);
          $('#edit_target').val(data.target);
          $('#edit_realisasi').val(data.realisasi);
          $('#edit_bulan').val(data.bulan);
          $('#edit_tahun').val(data.tahun);
          $('#edit_keterangan').val(data.keterangan);

          // Tampilkan modal
          $('#editRencanaModal').modal('show');
        }
      });
    }
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

</body>

</html>
