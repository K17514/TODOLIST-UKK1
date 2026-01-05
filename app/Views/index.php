<div class="container py-5">
  <div class="row"  style="padding-top: 80px;">
    <!-- ============================
         LEFT COLUMN: Produk & Kategori
    ============================= -->
    <div class="col-lg-9">
  <div class="mb-4">
    <div class="btn-group" role="group" id="kategoriButtons">
<!-- Tombol Paket Hemat -->
<!-- Tombol Paket -->
<button 
  id="kategoriBtnPaket" 
  class="btn btn-outline-secondary d-flex align-items-center gap-2" 
  onclick="showPaket()">
  Paket Hemat
</button>

  <!-- Tombol Kategori Produk -->
  <?php foreach ($kategori as $k): ?>
    <button 
      id="kategoriBtn<?= $k['id_kategori'] ?>" 
      class="btn btn-outline-secondary d-flex align-items-center gap-2" 
      onclick="showCategory(<?= $k['id_kategori'] ?>)">
      <img src="<?= base_url('images/' . $k['icon']) ?>" alt="<?= esc($k['nama_kategori']) ?>" width="20" height="20">
      <?= esc($k['nama_kategori']) ?>
    </button>
  <?php endforeach; ?>
</div>

  </div>
      <div class="row g-4" id="produk-container">
  <?php foreach ($produk as $p): ?>
    <div class="col-md-3 produk-item kategori-<?= $p['id_kategori'] ?>">
      <div class="card h-100 shadow-sm border-0 rounded-4 product-card">
        <img src="<?= base_url('images/' . $p['foto']) ?>" 
             class="card-img-top rounded-top-4" 
             alt="<?= esc($p['nama_produk']) ?>" 
             style="height: 200px; object-fit: cover;">

        <div class="card-body d-flex flex-column">
          <h5 class="card-title mb-2"><?= esc($p['nama_produk']) ?></h5>
          <p class="mb-2"><?= esc(strip_tags($p['description'])) ?></p>
          <p class="card-text text-muted mb-3">Rp <?= number_format($p['harga'], 0, ',', '.') ?></p>

          <div class="mt-auto">
            <button class="btn custom-border-btn w-100 rounded-pill btn-add-to-cart" 
                    data-id="<?= $p['id_produk'] ?>" 
                    data-nama="<?= esc($p['nama_produk']) ?>" 
                    data-harga="<?= $p['harga'] ?>"
                    <?= $p['status'] === 'kosong' ? 'disabled' : '' ?>>
              <?= $p['status'] === 'kosong' ? 'Habis' : 'Tambah ke Keranjang' ?>
            </button>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

      <!-- Container untuk Paket -->
<div class="row g-4" id="paket-container" style="display: none;">
  <?php foreach ($paket as $pk): ?>
    <?php
      // Cek ketersediaan produk dalam paket
      $tersedia = true;
      if (!empty($rows)) {
        foreach ($rows as $row) {
          if ($row->id_paket == $pk['id_paket'] && $row->status == 'kosong') {
            $tersedia = false;
            break;
          }
        }
      }
    ?>
    <div class="col-md-3">
      <div class="card h-100 shadow-sm border-0 rounded-4 product-card">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?= esc($pk['nama_paket']) ?></h5>
          <p class="card-text"><?= esc(strip_tags($pk['deskripsi'])) ?></p>
          <p class="card-text text-muted fw-bold">Rp <?= number_format($pk['harga_paket'], 0, ',', '.') ?></p>

          <!-- Tampilkan daftar produk dalam paket -->
          <?php if (!empty($pk['produk'])): ?>
            <ul class="mt-2 mb-3 ps-3">
              <?php foreach ($pk['produk'] as $produk): ?>
                <li><?= esc($produk) ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <div class="mt-auto">
            <?php if ($tersedia): ?>
              <button class="btn custom-border-btn w-100 rounded-pill btn-add-to-cart"
                data-id="<?= $pk['id_paket'] ?>"
                data-nama="<?= esc($pk['nama_paket']) ?>"
                data-harga="<?= $pk['harga_paket'] ?>"
                data-tipe="paket">
                Pesan Paket Ini
              </button>
            <?php else: ?>
              <button class="btn btn-secondary w-100 rounded-pill" disabled>Habis</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>


    </div>
    <!-- ============================
         RIGHT COLUMN: Invoice / Cart
    ============================= -->
    <div class="col-lg-3">
  <form id="checkout-form" action="<?= base_url('home/inputpemesanan') ?>" method="post">
    <div class="card shadow border-0 rounded-4">
      <div class="card-body">
        <h5 class="fw-bold mb-3">CART</h5>
        <div id="invoice-items" class="small mb-3" style="max-height: 250px; overflow-y: auto;">
          <div class="text-muted text-center">Keranjang kosong</div>
        </div>
        <hr>
        <div class="d-flex justify-content-between fw-bold mb-3">
          <span>Total:</span>
          <span id="total-amount">Rp 0</span>
        </div>

        <fieldset class="mb-4">
          <p class="fw-bold mb-2">Payment Method</p>
          <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" id="paymentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <span id="selectedText">--Pilih Metode</span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="paymentDropdown">
              <?php foreach ($metode_pembayaran as $key => $value): ?>
                 <li><a class="dropdown-item" href="#" data-value="<?=$value->id_metode?>"><?=$value->nama_metode?></a></li>
              <?php endforeach ?>
            </ul>
            <input type="hidden" name="payment_method" id="payment-method" value="cash">
            <input type="hidden" name="status" id="status" value="lunas"> 
          </div>
        </fieldset>

        <input type="hidden" name="cart_data" id="cart-data">

        <button type="submit" class="btn custom-btn w-100 rounded-pill">Place an Order</button>
      </div>
    </div>
  </form>
</div>

</div>

   <script>
let timeout, warningTimeout;

function resetTimer() {
    clearTimeout(timeout);
    clearTimeout(warningTimeout);
    warningTimeout = setTimeout(() => {
        const stayLoggedIn = confirm("You will be logged out in 5 seconds due to inactivity. Click OK to stay logged in.");
        if (stayLoggedIn) {
            resetTimer();
        }
    }, 10000);
    timeout = setTimeout(() => {
        fetch("<?= base_url('home/logout') ?>") 
            .then(() => {
                alert("Session expired due to inactivity.");
                window.location.href = "<?= base_url('home/login') ?>"; 
            });
    }, 120000); 
}
document.addEventListener("mousemove", resetTimer);
document.addEventListener("keypress", resetTimer);
document.addEventListener("click", resetTimer);
document.addEventListener("scroll", resetTimer);
resetTimer();


function showPaket() {
  // Tampilkan paket
  document.getElementById('produk-container').style.display = 'none';
  document.getElementById('paket-container').style.display = 'flex';

  // Highlight tombol
  const buttons = document.querySelectorAll('#kategoriButtons .btn');
  buttons.forEach(btn => btn.classList.remove('active-category'));
  const paketBtn = document.getElementById('kategoriBtnPaket');
  if (paketBtn) paketBtn.classList.add('active-category');
}

function showCategory(kategoriId) {
  const produkContainer = document.getElementById('produk-container');
  const paketContainer = document.getElementById('paket-container');
  const items = document.querySelectorAll('.produk-item');

  // Tampilkan produk
  produkContainer.style.display = 'flex';
  paketContainer.style.display = 'none';

  // Filter produk sesuai kategori
  items.forEach(item => {
    item.style.display = item.classList.contains('kategori-' + kategoriId) ? 'block' : 'none';
  });

  // Highlight tombol
  const buttons = document.querySelectorAll('#kategoriButtons .btn');
  buttons.forEach(btn => btn.classList.remove('active-category'));
  const activeBtn = document.getElementById('kategoriBtn' + kategoriId);
  if (activeBtn) activeBtn.classList.add('active-category');
}
showCategory(<?= $kategori[0]['id_kategori'] ?? 1 ?>);


const invoiceItems = document.getElementById("invoice-items");
const totalAmount = document.getElementById("total-amount");
let cart = [];

document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const nama = btn.dataset.nama;
        const harga = parseInt(btn.dataset.harga);
        const tipe = btn.dataset.tipe || 'produk';  // Default tipe adalah 'produk' jika tidak ada data-tipe
        const existing = cart.find(p => p.id === id);

        if (existing) {
            existing.qty += 1;
            existing.total += harga;
        } else {
            cart.push({
                id, 
                nama, 
                harga, 
                qty: 1, 
                total: harga, 
                note: '',
                tipe: tipe,  // Menambahkan tipe ke dalam cart
            });
        }
        renderCart(); // Setelah menambah cart, panggil renderCart untuk memperbarui tampilan
    });
});

renderCart();

  document.querySelectorAll('.dropdown-item').forEach(function(item) {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      const value = this.getAttribute('data-value');
      const label = this.textContent.trim();  

      document.getElementById('payment-method').value = value;
      document.getElementById('selectedText').textContent = label;
    });
  });
// Saat render cart, simpan juga data cart ke input hidden
function renderCart() {
  invoiceItems.innerHTML = '';
  let total = 0;
  if (cart.length === 0) {
    invoiceItems.innerHTML = '<div class="text-muted text-center">Keranjang kosong</div>';
  } else {
    cart.forEach((item, index) => {
      invoiceItems.innerHTML += `
        <div class="d-flex justify-content-between mb-2 align-items-center">
          <div>${item.nama} x${item.qty}</div>
          <div class="d-flex align-items-center gap-2">
            <div>Rp ${item.total.toLocaleString('id-ID')}</div>
            <button class="btn btn-sm btn-outline-danger btn-remove-item" data-index="${index}" title="Hapus item">&times;</button>
          </div>
        </div>
        <div class="mb-3">
          <textarea class="form-control form-control-sm" placeholder="Catatan" data-index="${index}">${item.note || ''}</textarea>
        </div>
      `;
      total += item.total;
    });
  }
  totalAmount.textContent = 'Rp ' + total.toLocaleString('id-ID');

  // simpan cart ke input hidden dalam format JSON
  document.getElementById('cart-data').value = JSON.stringify(cart);

  // tombol hapus item
  document.querySelectorAll('.btn-remove-item').forEach(btn => {
    btn.addEventListener('click', () => {
      const index = btn.dataset.index;
      cart.splice(index, 1);
      renderCart();
    });
  });

  // input catatan
  document.querySelectorAll('.form-control-sm').forEach(input => {
    input.addEventListener('input', function () {
      const index = input.dataset.index;
      cart[index].note = input.value;
      document.getElementById('cart-data').value = JSON.stringify(cart);
    });
  });
}

document.addEventListener('DOMContentLoaded', function () {
    const paymentItems = document.querySelectorAll('.dropdown-item');
    const selectedText = document.getElementById('selectedText');
    const paymentMethodInput = document.getElementById('payment-method');
    const statusInput = document.getElementById('status');

    paymentItems.forEach(item => {
      item.addEventListener('click', function (e) {
        e.preventDefault();
        const selectedValue = this.getAttribute('data-value');
        const selectedName = this.textContent;

        // Update dropdown display & hidden input
        selectedText.textContent = selectedName;
        paymentMethodInput.value = selectedValue;

        // Ubah status kalau bukan cash
        if (selectedValue !== '2') {
          statusInput.value = 'menunggu pembayaran';
        } else {
          statusInput.value = 'proses';
        }
      });
    });
  });

</script>