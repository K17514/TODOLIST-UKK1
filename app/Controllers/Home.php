<?php

namespace App\Controllers;

use App\Models\M_tugas;


class Home extends BaseController
{
    public function __construct()
    {
        $this->model = new M_tugas();
    }

    public function index()
    {
        $tugas = $this->model->tampil();
            echo view('header');
            echo view('tampildata', ['tugas' => $tugas]);
    }





    public function input_produk()
    {
        $nama_produk = $this->request->getPost('nama_produk');
        $temperatur = $this->request->getPost('temperatur');
        $deskripsi = $this->request->getPost('deskripsi');
        $kategori = $this->request->getPost('kategori');
        $status = $this->request->getPost('status');
        $harga = $this->request->getPost('harga');
        $harga_modal = $this->request->getPost('harga_modal');
        $foto = $this->request->getPost('foto_produk');

        $data = array(
            'nama_produk' => $nama_produk . ' ' . $temperatur,
            'id_kategori' => $kategori,
            'status' => $status,
            'harga' => $harga,
            'harga_modal' => $harga_modal,
            'description' => $deskripsi,
            'created_by' => session()->get('id_user'),
            'foto' => $newFileName
        );
        $file = $_FILES["foto_produk"];
        $validExtensions = ["jpg", "png", "jpeg"];
        $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
        $timestamp = time();
        $newFileName = $timestamp . "_" . $file["name"];
        move_uploaded_file($file["tmp_name"], "images/" . $newFileName);
        $data['foto'] = $newFileName;

        $this->model->input('produk', $data);
        return redirect()->to('home/');
    }





    public function edit_produk($id)
    {
        if (session()->get('level') == 1 || session()->get('level') == 2 || session()->get('level') == 3 || session()->get('level') == 4) {
            $where = array('id_produk' => $id);
            $parent['child'] = $this->model->getWhere('produk', $where);
            $parent['kategori'] = $this->model->tampilexist('kategori', 'id_kategori');
            echo view('header', ['webDetail' => $this->webDetail]);
            echo view('menu');
            echo view('eproduk', $parent);
            echo view('footer');
        } else {
            return redirect()->to('/home/login');
        }
    }

    public function simpan_produk()
    {
        if (session()->get('level') == 1 || session()->get('level') == 2 || session()->get('level') == 3 || session()->get('level') == 4) {
            $idProduk = $this->request->getPost('id');
            if (!$idProduk) {
                return redirect()->back()->with('error', 'ID Produk tidak valid.');
            }

            date_default_timezone_set('Asia/Jakarta');
            $now = date('Y-m-d H:i:s');

            $data = [
                'nama_produk' => $this->request->getPost('nama_produk'),
                'id_kategori' => $this->request->getPost('kategori'),
                'status' => $this->request->getPost('status'),
                'harga' => $this->request->getPost('harga'),
                'updated_by' => session()->get('id_user'),
                'updated_at' => $now
            ];

            // Handle file upload
            $file = $this->request->getFile('foto');

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $validExtensions = ['jpg', 'jpeg', 'png'];
                $extension = $file->getClientExtension();

                if (in_array(strtolower($extension), $validExtensions)) {
                    $newFileName = time() . '_' . $file->getRandomName();
                    $uploadPath = 'images/';

                    if ($file->move($uploadPath, $newFileName)) {
                        // Delete old file if exists
                        $currentData = $this->model->getWhere('produk', ['id_produk' => $idProduk]);
                        if ($currentData && isset($currentData->foto)) {
                            $oldFilePath = $uploadPath . $currentData->foto;
                            if (file_exists($oldFilePath)) {
                                unlink($oldFilePath);
                            }
                        }

                        $data['foto'] = $newFileName;
                    } else {
                        return redirect()->back()->with('error', 'Gagal memindahkan file.');
                    }
                } else {
                    return redirect()->back()->with('error', 'Format gambar tidak valid. Gunakan jpg, jpeg, atau png.');
                }
            }

            // Update database
            $this->model->edit('produk', $data, ['id_produk' => $idProduk]);
            return redirect()->to('home/tampildata')->with('success', 'Berhasil edit data.');
        } else {
            return redirect()->to('/home/login');
        }
    }








    public function hapus_produk($id)
    {
        if (in_array(session()->get('level'), [1, 2, 3, 4])) {
            date_default_timezone_set('Asia/Jakarta'); // Set zona waktu
            $now = date('Y-m-d H:i:s'); // Ambil waktu sekarang

            // Soft delete user dengan mengupdate kolom deleted_at
            $this->model->edit('produk', ['deleted_at' => $now, 'deleted_by' => session()->get('id_user')], ['id_produk' => $id]);

            return redirect()->to('home/tampildata')->with('success', 'Product has been soft deleted.');
        } else {
            return redirect()->to('/home/login');
        }
    }



}
