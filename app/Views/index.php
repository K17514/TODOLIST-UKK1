<div class="container py-5">
  <div class="row" style="padding-top: 100px;">

    <div class="mb-4">
      <div class="row g-4" id="produk-container">

        <!-- ADD BUTTON -->
        <div class="col-12">
          <a href="<?= base_url('home/input') ?>"
            class="btn custom-border-btn rounded-pill mb-3">
            Tambah Tugas
          </a>
        </div>

        <?php foreach ($tugas as $t): ?>
          <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 product-card">
              <div class="card-body d-flex justify-content-between align-items-center">

                <!-- LEFT CONTENT -->
                <div>
                  <h5 class="card-title mb-1"><?= esc($t->nama_tugas) ?></h5>
                  <p class="mb-0">Priority: <?= esc(strip_tags($t->prioritas)) ?></p>
                  <p class="mb-0">Status: <?= esc(strip_tags($t->status)) ?></p>
                  <p class="mb-0">
                    Tanggal:
                    <?= date('d F Y, H:i', strtotime($t->tanggal)) ?>
                  </p>

                </div>

                <!-- RIGHT ACTIONS -->
                <div class="d-flex align-items-center gap-2">

                  <!-- STATUS BUTTON -->
                  <?php if ($t->status === 'belum selesai'): ?>
                    <a href="<?= base_url('home/selesai/' . $t->id_tugas) ?>"
                      class="btn custom-border-btn rounded-pill">
                      Selesai
                    </a>
                  <?php else: ?>
                    <button class="btn btn-secondary rounded-pill" disabled>
                      Telah Selesai
                    </button>
                  <?php endif; ?>

                  <!-- DROPDOWN -->
                  <div class="dropdown">
                    <button class="btn btn-light rounded-circle"
                      type="button"
                      data-bs-toggle="dropdown"
                      aria-expanded="false">
                      ⋮
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item"
                          href="<?= base_url('home/edit/' . $t->id_tugas) ?>">
                          Edit
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item text-danger"
                          href="<?= base_url('home/delete/' . $t->id_tugas) ?>"
                          onclick="return confirm('Hapus tugas ini?')">
                          Delete
                        </a>
                      </li>
                    </ul>
                  </div>

                </div>

              </div>
            </div>
          </div>
        <?php endforeach; ?>

      </div>
    </div>

  </div>
</div>