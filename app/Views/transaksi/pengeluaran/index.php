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
        'breadcrumb_item' => 'Transaksi',
        'breadcrumb_item_active' => 'Pengeluaran'
      ]) ?>

      <div class="row">
        <div class="col-md-12 col-xl-12">
          <div class="card">
            <div class="card-body">
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModalPengeluaran">
                + Tambah Produk
              </button>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-xl-12">
          <h5 class="mb-3">Daftar Pengeluaran</h5>
          <div class="card tbl-card">
            <div class="card-body">
              <div class="table-responsive">
                <table id="pengeluaranTable" class="table table-hover table-bordered mb-0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Tanggal</th>
                      <th>Kategori</th>
                      <th>Jumlah</th>
                      <th>Keterangan</th>
                      <th class="text-end">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($pengeluaran as $p): ?>
                      <tr>
                        <td><?= esc($p['id']) ?></td>
                        <td><?= esc($p['tanggal']) ?></td>
                        <td><?= esc($p['kategori']) ?></td>
                        <td>Rp<?= number_format($p['jumlah'], 0, ',', '.') ?></td>
                        <td><?= esc($p['keterangan']) ?></td>
                        <td class="text-end">
                          <button type="button" class="btn btn-warning btn-sm btn-edit"
                            data-id="<?= $p['id'] ?>"
                            data-tanggal="<?= $p['tanggal'] ?>"
                            data-kategori="<?= $p['kategori'] ?>"
                            data-jumlah="<?= $p['jumlah'] ?>"
                            data-keterangan="<?= $p['keterangan'] ?>"
                            data-url="<?= base_url('pengeluaran/update/' . $p['id']) ?>"
                            data-bs-toggle="modal" data-bs-target="#editModal">
                            Edit
                          </button>
                          <button type="button" class="btn btn-danger btn-sm btn-delete"
                            data-id="<?= $p['id'] ?>"
                            data-nama="<?= esc($p['kategori']) ?>"
                            data-url="<?= base_url('pengeluaran/delete/' . $p['id']) ?>"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                            Hapus
                          </button>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?= view('layouts/footer-block') ?>
<!-- Modal Tambah Pengeluaran -->
<div class="modal fade" id="createModalPengeluaran" tabindex="-1" aria-labelledby="createModalPengeluaranLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="<?= base_url('transaksi/pengeluaran/store') ?>" method="post" id="formPengeluaran">
        <div class="modal-header">
          <h5 class="modal-title" id="createModalPengeluaranLabel">Tambah Pengeluaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Tanggal</label>
            <div class="col-sm-10">
              <input type="date" name="tanggal" class="form-control" required>
            </div>
          </div>

          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Kategori</label>
            <div class="col-sm-10">
              <select name="kategori" class="form-control">
                <option value="Adsense">Adsense</option>
                <option value="Marketing">Marketing</option>
                <option value="Operasional">Operasional</option>
                <!-- Tambah kategori lain jika diperlukan -->
              </select>
            </div>
          </div>

          <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Jumlah Pengeluaran</label>
            <div class="col-sm-10">
              <input type="number" name="jumlah" class="form-control" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
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

  <script>
    $(document).ready(function () {
      $('.btn-edit').on('click', function () {
        const id = $(this).data('id');
        const tanggal = $(this).data('tanggal');
        const kategori = $(this).data('kategori');
        const jumlah = $(this).data('jumlah');
        const keterangan = $(this).data('keterangan');
        const url = $(this).data('url');

        $('#editTanggal').val(tanggal);
        $('#editKategori').val(kategori);
        $('#editJumlah').val(jumlah);
        $('#editKeterangan').val(keterangan);
        $('#editForm').attr('action', url);
      });

      $('.btn-delete').on('click', function () {
        const nama = $(this).data('nama');
        const url = $(this).data('url');

        $('#deleteNama').text(nama);
        $('#formDelete').attr('action', url);
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
