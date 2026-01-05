<section class="latest-podcast-section d-flex align-items-center justify-content-center pb-5" id="section_2" style="padding-top: 100px; background-color:#E0DBD8;">
    <div class="container">
        <div class="col-lg-12 col-12 mt-5 mb-4 mb-lg-4">
            <div class="custom-block d-flex flex-column" style="background-color:#FAF5F1; border-radius:16px; box-shadow:0 4px 10px rgba(0,0,0,0.1);">

                <div class="tab-content" id="inputTabsContent">

                    <!-- Produk Table -->
                    <div class="tab-pane fade show active" id="produk-table" role="tabpanel">
                        <a href="<?= base_url('home/formdatas') ?>" class="btn btn-success mb-3" style="align-self: flex-start; background-color:#292F36; border:none;">
                            <i class="fa fa-user-plus me-1"></i> + Tambah Produk
                        </a>
                        <table id="productTable" class="table table-hover datatable" style="background-color: #ffffff; border-radius: 10px; overflow: hidden;">
                            <thead style="background-color:#A41F13; color:#ffffff;">
                                <tr>
                                    <th>No<br><input type="text" class="form-control form-control-sm column-search" placeholder="Search No"></th>
                                    <th>Nama Tugas<br><input type="text" class="form-control form-control-sm column-search" placeholder="Search Nama"></th>
                                    <th>Status<br><input type="text" class="form-control form-control-sm column-search" placeholder="Search Status"></th>
                                    <th>Prioritas<br><input type="text" class="form-control form-control-sm column-search" placeholder="Search Priority"></th>
                                    <th>Tanggal<br><input type="text" class="form-control form-control-sm column-search" placeholder="Search Tanggal"></th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ms = 1;
                                foreach ($tugas as $key => $value) {
                                ?>
                                    <tr style="background-color: white;" onmouseover="this.style.backgroundColor='darkwhite'; this.style.color='black';" onmouseout="this.style.backgroundColor='white'; this.style.color='';">
                                        <td><?= $ms++ ?></td>
                                        <td><?= $value->nama_tugas?></td>
                                        <td><?= $value->status ?></td>
                                        <td><?= $value->prioritas ?></td>
                                        <td><?= $value->tanggal ?></td>
                                        <td>
                                            <a href="<?= base_url('home/edit/' . $value->id_tugas) ?>" class="btn btn-warning btn-sm" style="background-color:#edb047; border:none;">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a class="btn btn-danger btn-sm delete-btn" href="<?= base_url('home/hapus/' . $value->id_tugas) ?>" style="background-color:#8F7A6E; border:none;" onclick="return confirm('Are you sure?');">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</section>

</main>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="<?= base_url('js/bootstrap.bundle.min.js'); ?>"></script>



</body>

</html>