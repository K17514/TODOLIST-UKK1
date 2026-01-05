<section class="d-flex align-items-center justify-content-center pb-5" style="padding-top: 100px; background-color: #E0DBD8;">
  <div class="container">
    <div class="col-lg-12 col-12 mt-5 mb-4 mb-lg-4">
      <div class="d-flex flex-column" style="background-color: #FAF5F1; padding: 2rem; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); color: #292F36;">

        <h3 style="color: #292F36; font-weight: bold; margin-bottom: 1.5rem;">Edit User</h3>

        <form action="<?= base_url('home/simpan') ?>" method="POST">
          <table style="width: 100%;">
            <tr>
              <td style="padding-bottom: 10px;"><label for="nama_tugas" style="font-weight: 500; color: #292F36;">Nama Tugas</label></td>
              <td style="padding-bottom: 10px;">
                <input type="text" class="form-control" id="nama_tugas" name="nama_tugas" value="<?= $tugas->nama_tugas ?>" style="border: 1px solid #8F7A6E; border-radius: 8px; padding: 0.6rem; width: 100%;">
              </td>
            </tr>
            <tr>
              <td style="padding-bottom: 10px;"><label for="tanggal" style="font-weight: 500; color: #292F36;">Tanggal</label></td>
              <td style="padding-bottom: 10px;">
                <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" value="<?= $tugas->tanggal ?>" style="border: 1px solid #8F7A6E; border-radius: 8px; padding: 0.6rem; width: 100%;">
              </td>
            </tr>
            <tr>
              <td style="padding-bottom: 10px;"><label for="prioritas" style="font-weight: 500; color: #292F36;">Prioritas</label></td>
              <td style="padding-bottom: 10px;">
                <select class="form-control" id="prioritas" name="prioritas" required
                  style="border: 1px solid #8F7A6E; border-radius: 8px; padding: 0.6rem; width: 100%;">
                  <option value="1" <?= $tugas->prioritas == 1 ? 'selected' : '' ?>>
                    1 - DO IMMEDIATELY!
                  </option>
                  <option value="2" <?= $tugas->prioritas == 2 ? 'selected' : '' ?>>
                    2 - Penting
                  </option>
                  <option value="3" <?= $tugas->prioritas == 3 ? 'selected' : '' ?>>
                    3 - Ok
                  </option>
                  <option value="4" <?= $tugas->prioritas == 4 ? 'selected' : '' ?>>
                    4 - Optional
                  </option>
                  <option value="5" <?= $tugas->prioritas == 5 ? 'selected' : '' ?>>
                    5 - Sekedar ada
                  </option>
                </select>

              </td>
            </tr>
            <tr>
              <td></td>
              <td style="padding-top: 10px;">
                <input type="hidden" value="<?= $tugas->id_tugas ?>" name="id">
                <button type="submit" class="btn" style="background-color: #A41F13; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600;">Simpan</button>
                <button type="reset" class="btn" style="background-color: #8F7A6E; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px;">Reset</button>
                <button type="button" class="btn" style="background-color: #8F7A6E; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px;" onclick="window.history.back()">Kembali</button>
              </td>
            </tr>
          </table>
        </form>

      </div>
    </div>
  </div>
</section>