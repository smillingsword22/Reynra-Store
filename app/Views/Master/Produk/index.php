<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <?= view('layouts/head-page-meta', ['title' => 'Master Produk']) ?>
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
        'breadcrumb_item' => 'Master',
        'breadcrumb_item_active' => 'Produk'
      ]) ?>

      <div class="row">
        <div class="col-md-12 col-xl-12">
          <div class="card">
            <div class="card-body">
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
                + Tambah Produk
              </button>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-xl-12">
          <h5 class="mb-3">Daftar Produk</h5>
          <div class="card tbl-card">
            <div class="card-body">
              <div class="table-responsive">
                <table id="produkTable" class="table table-hover table-bordered mb-0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nama Produk</th>
                      <th>Kategori</th>
                      <th>Modal</th>
                      <th>Harga Jual</th>
                      <th class="text-end">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($produk as $p): ?>
                      <tr>
                        <td><?= esc($p['id']) ?></td>
                        <td><?= esc($p['nama_produk']) ?></td>
                        <td><?= esc($p['kategori']) ?></td>
                        <td>Rp<?= number_format($p['harga_beli'], 0, ',', '.') ?></td>
                        <td>Rp<?= number_format($p['harga_jual'], 0, ',', '.') ?></td>
                        <td class="text-end">
                          <button type="button" class="btn btn-warning btn-sm btn-edit"
                            data-id="<?= $p['id'] ?>"
                            data-nama="<?= $p['nama_produk'] ?>"
                            data-beli="<?= $p['harga_beli'] ?>"
                            data-jual="<?= $p['harga_jual'] ?>"
                            data-url="<?= base_url('master/produk/update/' . $p['id']) ?>"
                            data-bs-toggle="modal" data-bs-target="#editModal">
                            Edit
                          </button>
                          <button type="button" class="btn btn-danger btn-sm btn-delete"
                            data-id="<?= $p['id'] ?>"
                            data-nama="<?= esc($p['nama_produk']) ?>"
                            data-url="<?= base_url('master/produk/delete/' . $p['id']) ?>"
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

  <!-- Modal Tambah Produk -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="<?= base_url('master/produk/store') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createModalLabel">Tambah Produk Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama_produk" class="form-label">Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select name="kategori" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              <option value="Apps Premium">Apps Premium</option>
              <option value="Suntik Followers">Suntik Followers</option>
              <option value="Top Up Games">Top Up Games</option>
              <option value="Joki Games">Joki Games</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="harga_beli" class="form-label">Harga Beli</label>
            <input type="number" name="harga_beli" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="harga_jual" class="form-label">Harga Jual</label>
            <input type="number" name="harga_jual" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Produk -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editForm" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" id="editNama" required>
          </div>
          <div class="mb-3">
            <label>Harga Beli</label>
            <input type="number" name="harga_beli" class="form-control" id="editBeli" required>
          </div>
          <div class="mb-3">
            <label>Harga Jual</label>
            <input type="number" name="harga_jual" class="form-control" id="editJual" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" id="formDelete">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Yakin ingin menghapus produk <strong id="deleteNama"></strong>?</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Ya, Hapus</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
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
      $('#produkTable').DataTable({
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
        const nama = $(this).data('nama');
        const beli = $(this).data('beli');
        const jual = $(this).data('jual');
        const url = $(this).data('url');

        $('#editNama').val(nama);
        $('#editBeli').val(beli);
        $('#editJual').val(jual);
        $('#editForm').attr('action', url);
      });
    });
  </script>

  <script>
    $(document).ready(function () {
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
