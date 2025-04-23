<!DOCTYPE html>
<html lang="en">

<head>
  <?= view('layouts/head-page-meta', ['title' => 'Transaksi Penjualan']) ?>
  <?= view('layouts/head-css') ?>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body>
  <?= view('layouts/layout-vertical') ?>

  <div class="pc-container">
    <div class="pc-content">
      <?= view('layouts/breadcrumb', [
        'breadcrumb_item' => 'Transaksi',
        'breadcrumb_item_active' => 'Penjualan'
      ]) ?>

      
      <div class="row">
        <div class="col-md-12 col-xl-12">
          <div class="card">
            <div class="card-body">
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
                + Tambah Penjualan
              </button>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-xl-12">
          <h5 class="mb-3">Daftar Penjualan</h5>
          <div class="card tbl-card">
            <div class="card-body">
              <div class="table-responsive">
                <table id="penjualanTable" class="table table-hover table-bordered mb-0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Tanggal</th>
                      <th>Total</th>
                      <th>Metode Pembayaran</th>
                      <!-- <th>Keterangan</th> -->
                      <th class="text-end">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($penjualan as $p): ?>
                      <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= $p['tanggal'] ?></td>
                        <td>Rp<?= number_format($p['total'], 0, ',', '.') ?></td>
                        <td><?= esc($p['metode_pembayaran']) ?></td>
                        <td class="text-end">
                          <button type="button" class="btn btn-info btn-sm btn-view"
                            data-id="<?= $p['id'] ?>"
                            data-bs-toggle="modal"
                            data-bs-target="#detailModal">
                            View
                          </button>
                          <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-btn" data-id="<?= $p['id'] ?>">
                            Hapus
                          </a>
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

  <!-- Modal Detail Penjualan -->
  <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Penjualan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="detailContent">
            <p>Loading...</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Penjualan -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form action="<?= base_url('transaksi/penjualan/store') ?>" method="post" id="formPenjualan">
        <div class="modal-header">
          <h5 class="modal-title" id="createModalLabel">Tambah Penjualan</h5>
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
            <label class="col-sm-2 col-form-label">Metode Pembayaran</label>
            <div class="col-sm-10">
              <select name="metode_pembayaran" class="form-control" required>
                <option value="Cash">Cash</option>
                <option value="Transfer">Transfer</option>
                <option value="QRIS">QRIS</option>
              </select>
            </div>
          </div>

          <hr>
          <h6>Detail Produk</h6>
          <table class="table table-sm table-bordered" id="produkTable">
            <thead>
              <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="produkList">
              <!-- Row pertama -->
            </tbody>
          </table>
          <button type="button" class="btn btn-sm btn-secondary" id="addRow">+ Tambah Produk</button>

          <div class="mt-3 text-end">
            <h5>Total: Rp<span id="grandTotal">0</span></h5>
            <input type="hidden" name="total" id="totalInput">
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



  <?= view('layouts/footer-block') ?>
  <?= view('layouts/footer-js') ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(document).ready(function() {
      $('#penjualanTable').DataTable({
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

      $('.btn-view').on('click', function () {
        const id = $(this).data('id');
        $('#detailContent').html('<p>Sedang memuat data...</p>');
        $.get("<?= base_url('transaksi/penjualan/detail/') ?>" + id, function (res) {
          $('#detailContent').html(res);
        });
      });
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', function (e) {
        e.preventDefault();
        
        const id = this.getAttribute('data-id');
        const deleteUrl = `<?= base_url('transaksi/penjualan/delete/') ?>/${id}`;

        Swal.fire({
          title: 'Apakah Anda yakin?',
          text: "Data ini akan dihapus dan tidak dapat dikembalikan.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, Hapus!',
          cancelButtonText: 'No, Batalkan!',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            // Jika Yes, maka redirect ke URL delete
            window.location.href = deleteUrl;
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Jika No, tampilkan notifikasi batal
            Swal.fire(
              'Batal!',
              'Data tidak dihapus.',
              'info'
            );
          }
        });
      });
    });

  </script>

  <script>
    const produkData = <?= json_encode($produk) ?>;

    function formatRupiah(angka) {
      return angka.toLocaleString('id-ID');
    }

    function hitungSubtotal(row) {
      const harga = parseFloat(row.querySelector('.harga').value || 0);
      const qty = parseInt(row.querySelector('.qty').value || 0);
      const subtotal = harga * qty;
      row.querySelector('.subtotal').innerText = 'Rp' + formatRupiah(subtotal);
      return subtotal;
    }

    function hitungTotal() {
      let total = 0;
      document.querySelectorAll('#produkList tr').forEach(row => {
        total += hitungSubtotal(row);
      });
      document.getElementById('grandTotal').innerText = formatRupiah(total);
      document.getElementById('totalInput').value = total;
    }

    function tambahRow() {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>
          <select name="produk_id[]" class="form-select produk-select" required>
            <option value="">Pilih Produk</option>
            ${produkData.map(p => `
              <option value="${p.id}" data-harga="${p.harga_jual}">${p.nama_produk}</option>
            `).join('')}
          </select>
        </td>
        <td><input type="number" class="form-control harga" name="harga[]" readonly></td>
        <td><input type="number" class="form-control qty" name="qty[]" min="1" value="1" required></td>
        <td><span class="subtotal">Rp0</span></td>
        <td><button type="button" class="btn btn-danger btn-sm btn-remove">Hapus</button></td>
      `;
      document.getElementById('produkList').appendChild(row);
    }

    document.getElementById('addRow').addEventListener('click', tambahRow);

    document.addEventListener('change', function (e) {
      if (e.target.classList.contains('produk-select')) {
        const harga = e.target.selectedOptions[0].dataset.harga;
        const row = e.target.closest('tr');
        row.querySelector('.harga').value = harga;
        hitungTotal();
      }

      if (e.target.classList.contains('qty')) {
        hitungTotal();
      }
    });

    document.addEventListener('click', function (e) {
      if (e.target.classList.contains('btn-remove')) {
        e.target.closest('tr').remove();
        hitungTotal();
      }
    });

    // auto tambah satu row saat modal dibuka
    $('#createModal').on('shown.bs.modal', function () {
      if (document.querySelectorAll('#produkList tr').length === 0) {
        tambahRow();
      }
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

  <?php if (session()->getFlashdata('error')): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          icon: 'error',
          title: 'Terjadi Kesalahan!',
          text: '<?= session()->getFlashdata('error') ?>',
          showConfirmButton: true
        });
      });
    </script>
  <?php endif; ?>

</body>
</html>
