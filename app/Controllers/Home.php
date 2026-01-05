<?php

namespace App\Controllers;

use App\Models\M_tugas;


class Home extends BaseController
{
    public function __construct()
    {
        $this->model = new M_tugas();
    }

    // public function index()
    // {
    //     $tugas = $this->model->tampil();
    //         echo view('header');
    //         echo view('tampildata', ['tugas' => $tugas]);
    // }

    public function index()
    {
        $data ['tugas'] = $this->model
            ->orderBy('prioritas', 'ASC')
            ->findAll();
        echo view('header');
        echo view('index', $data);
        echo view('footer');
    }

    public function selesai($id)
    {
        $this->model->update($id, ['status' => 'selesai']);
        return redirect()->back()->with('success', 'Tugas selesai.');
    }

    public function inputview()
    {
        echo view('header');
        echo view('input');
        echo view('footer');
    }

    public function input()
    {
        $nama_tugas = $this->request->getPost('nama_tugas');
        $tanggal = $this->request->getPost('tanggal');
        $prioritas = $this->request->getPost('prioritas');
        $this->model->insert([
            'nama_tugas' => $nama_tugas,
            'tanggal' => $tanggal,
            'prioritas' => $prioritas,
            'status' => "belum selesai"
        ]);
        return redirect()->to('');
    }


    public function editview($id)
    {
        $parent['tugas'] = $this->model->find($id);

        echo view('header');
        echo view('edit', $parent);
        echo view('footer');
    }

    public function simpan()
    {
        $id = $this->request->getPost('id');

        $this->model->update($id, [
            'nama_tugas' => $this->request->getPost('nama_tugas'),
            'prioritas'  => $this->request->getPost('prioritas'),
            'tanggal'    => $this->request->getPost('tanggal'),
        ]);
        return redirect()->to('')->with('success', 'Berhasil edit data.');
    }


    public function hapus($id)
    {
        $this->model->delete($id);
        return redirect()->to('')->with('success', 'Product has been soft deleted.');
    }
}
