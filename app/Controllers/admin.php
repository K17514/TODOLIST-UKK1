<?php

namespace App\Controllers;
use App\Models\M_belajar;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use TCPDF;

class Admin extends BaseController
{
    public function __construct()
    {
        $this->model = new M_belajar(); // Initialize the model once
        $this->webDetail = $this->model->getWebDetails();
    }

    public function index()
    {
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('index');
        echo view ('footer');
    }


















public function settings()
    {
        if (session()->get('level')==1){
        $id_user = session()->get('id_user');
        $parent['child'] = $this->model->tampil1('web_detail','id_detail',true);
        $this->model->log_activity($id_user, "User accessed settings");
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('admin/settings', $parent);
        echo view ('footer');
        }else{
            return redirect()->to('/home/login');
        }
    }
public function update_setting()
{
   if (session()->get('level')==1){

    $webId = 1; // Assuming you have only one row in `web_detail`

    // Fetch existing data
    $currentData = $this->model->getWhereOpt('web_detail', ['id_detail' => $webId], true);

    // Get new title input
    $newTitle = $this->request->getPost('fullName');
    if (!$newTitle) {
        return redirect()->back()->with('error', 'Website title is required.');
    }

    // Prepare update data
    $data = ['title' => $newTitle];

    // Handle file upload
    $file = $this->request->getFile('profile_image');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $uploadPath = 'images/';
        $newFileName = 'logo_' . time() . '.' . $file->getExtension();

        if ($file->move($uploadPath, $newFileName)) {
            // Delete old logo if it exists
            $oldFileName = $currentData->logo ?? null;
            if ($oldFileName && file_exists($uploadPath . $oldFileName)) {
                unlink($uploadPath . $oldFileName);
            }
            $data['logo'] = $newFileName;
        } else {
            return redirect()->back()->with('error', 'Failed to upload the logo.');
        }
    }

    // Update the settings
    $this->model->edit('web_detail', $data, ['id_detail' => $webId]);

    // Log activity
    $this->model->log_activity(session()->get('id_user'), "Updated website settings");

    return redirect()->to('/admin/settings')->with('successprofil', 'Settings updated successfully.');
    }else{
            return redirect()->to('/home/login');
        }
}





















public function tampiluser()
{
    if (session()->get('level') == 1 || session()->get('level') == 2) {
        $where = [
            'deleted_at' => null
        ];
        // Pass 'false' for multiple results, and 'id_user' as the orderBy field
        $parent['child'] = $this->model->getWhereOpt('user', $where, false, 'id_user');
        
        echo view('header',['webDetail' => $this->webDetail]);
        echo view('menu');
        echo view('admin/tampiluser', $parent);
    } else {
        return redirect()->to('/home/login');
    }
}

public function deleted_data()
{
    if (session()->get('level') == 1) {
        $deletedData = [
            'user' => $this->model->getDelete('user', "deleted_at IS NOT NULL"),
            'produk' => $this->model->getDelete('produk', "deleted_at IS NOT NULL"),
            'kategori' => $this->model->getDelete('kategori', "deleted_at IS NOT NULL"),
            'paket' => $this->model->getDelete('paket', "deleted_at IS NOT NULL"),
        ];

        return view('header',['webDetail' => $this->webDetail])
         . view('menu')
            . view('admin/deleted_data', ['deletedData' => $deletedData])
            . view('footer');
    }
    return redirect()->to('home/login');
}


public function log_activity()
    {
        if (session()->get('level') == 1 || session()->get('level') == 1){
        $where = session()->get('id_user');
        $parent['child']=$this->model->join('log_activity','user','log_activity.id_user=user.id_user','id_log');
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view ('admin/log_activity',$parent);
        }else{
            return redirect()->to('/home/login');
        }
    }















    public function formuser()
    {
        if (session()->get('level') == 1 || session()->get('level') == 2){
            echo view('header',['webDetail' => $this->webDetail]);
            echo view ('menu');
            echo view ('admin/inputuser');
            echo view ('footer');
        }else if (session()->get('level')>0){
            return redirect()->to('/error');
        }else{
            return redirect()->to('/home/login');
        }
    }










public function input_user()
{

    $username = $this->request->getPost('username');
    $email = $this->request->getPost('email');
    $password = MD5($this->request->getPost('password'));
    $level = $this->request->getPost('level');

    // Check if email or username already exists
    $existingUsername = $this->model->getWhere('user', ['username' => $username]);

    if ($existingUsername) {
        return redirect()->to('/home/register')->with('error', 'Username already taken.');
    }

    $data = [
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'level' => $level,
        'created_by' => session()->get('id_user')
    ];
    $this->model->input('user', $data);
    return redirect()->to('/admin/tampiluser');
}












    public function hapus_user($id)
{
    if (session()->get('level') == 1 || session()->get('level') == 2) {
        date_default_timezone_set('Asia/Jakarta'); // Set zona waktu
        $now = date('Y-m-d H:i:s'); // Ambil waktu sekarang

        // Soft delete user dengan mengupdate kolom deleted_at
        $this->model->edit('user', ['deleted_at' => $now, 'deleted_by' => session()->get('id_user')], ['id_user' => $id]);

        return redirect()->to('admin/tampiluser')->with('success', 'User has been soft deleted.');
    } else {
        return redirect()->to('/home/login');
    }
}



















    public function edit_user($id)
    {
         if (session()->get('level') == 1 || session()->get('level') == 2){
        $where= array('id_user' =>$id);
        $parent['child']=$this->model->getWhere('user',$where);
        echo view('header',['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('admin/euser', $parent);
        echo view('footer');      
        }else{
            return redirect()->to('/home/login');
        }    
    } 

public function simpan_user()
{
    if (session()->get('level') == 1 || session()->get('level') == 2) {
        $where = ['id_user' => $this->request->getPost('id')];
        date_default_timezone_set('Asia/Jakarta');
         $now = date('Y-m-d H:i:s'); // Ambil waktu sekarang

        // Fetch current user data (just in case)
        $existingUser = $this->model->getWhere('user', $where);

        if (!$existingUser) {
            return redirect()->to('admin/tampiluser')->with('error', 'User not found');
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'level' => $this->request->getPost('level'),
            'updated_by' => session()->get('id_user'),
            'updated_at' => $now
        ];

        // If password field is not empty, hash & update it
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $data['password'] = md5($newPassword); // or password_hash if you switch to stronger hashing
        }

        $this->model->edit('user', $data, $where);

        return redirect()->to('admin/tampiluser')->with('success', 'User updated successfully');
    } else {
        return redirect()->to('/home/login');
    }
}

















public function detailuser($id)
{
    if (session()->get('level') == 1) {
        $where = array('id_user' => $id);
        $user = $this->model->getWhere('user', $where);

        if ($user) {
            // Load user names for created_by, updated_by, deleted_by
            $user->created_by_name = $this->model->getUsernameById($user->created_by);
            $user->updated_by_name = $this->model->getUsernameById($user->updated_by);
            $user->deleted_by_name = $this->model->getUsernameById($user->deleted_by);
        }

        $data['child'] = $user;
        
        echo view('header', ['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('admin/user_info', $data);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}



public function detailproduk($id)
{
    if (session()->get('level') == 1) {
        $where = array('id_produk' => $id);
        $produk = $this->model->getWhere('produk', $where);

        if ($produk) {
            // Load user names for created_by, updated_by, deleted_by
            $produk->created_by_name = $this->model->getUsernameById($produk->created_by);
            $produk->updated_by_name = $this->model->getUsernameById($produk->updated_by);
            $produk->deleted_by_name = $this->model->getUsernameById($produk->deleted_by);
        }

        $data['child'] = $produk;
        
        echo view('header', ['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('admin/produk_info', $data);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}

public function detailkategori($id)
{
    if (session()->get('level') == 1) {
        $where = array('id_kategori' => $id);
        $kategori = $this->model->getWhere('kategori', $where);

        if ($kategori) {
            // Load user names for created_by, updated_by, deleted_by
            $kategori->created_by_name = $this->model->getUsernameById($kategori->created_by);
            $kategori->updated_by_name = $this->model->getUsernameById($kategori->updated_by);
            $kategori->deleted_by_name = $this->model->getUsernameById($kategori->deleted_by);
        }

        $data['child'] = $kategori;
        
        echo view('header', ['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('admin/kategori_info', $data);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}

public function detailpaket($id)
{
    if (session()->get('level') == 1) {
        $where = array('id_paket' => $id);
        $paket = $this->model->getWhere('paket', $where);

        if ($paket) {
            // Load user names for created_by, updated_by, deleted_by
            $paket->created_by_name = $this->model->getUsernameById($paket->created_by);
            $paket->updated_by_name = $this->model->getUsernameById($paket->updated_by);
            $paket->deleted_by_name = $this->model->getUsernameById($paket->deleted_by);
        }

        $data['child'] = $paket;
        
        echo view('header', ['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('admin/paket_info', $data);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}


public function detailnota($id)
{
    if (session()->get('level') == 1) {
        $where = array('id_nota' => $id);
        $nota = $this->model->getWhere('nota', $where);

        if ($nota) {
            // Load user names for created_by, updated_by, deleted_by
            $nota->created_by_name = $this->model->getUsernameById($nota->created_by);
            $nota->updated_by_name = $this->model->getUsernameById($nota->updated_by);
            $nota->deleted_by_name = $this->model->getUsernameById($nota->deleted_by);
        }

        $data['child'] = $nota;
        
        echo view('header', ['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('admin/nota_info', $data);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}

public function detailmetode($id)
{
    if (session()->get('level') == 1) {
        $where = array('id_metode' => $id);
        $metode = $this->model->getWhere('metode_pembayaran', $where);

        if ($metode) {
            // Load user names for created_by, updated_by, deleted_by
            $metode->created_by_name = $this->model->getUsernameById($metode->created_by);
            $metode->updated_by_name = $this->model->getUsernameById($metode->updated_by);
            $metode->deleted_by_name = $this->model->getUsernameById($metode->deleted_by);
        }

        $data['child'] = $metode;
        
        echo view('header', ['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('admin/metode_info', $data);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}































public function restore()
{
    if (session()->get('level') == 1) {
        $table = $this->request->getPost('table');
        $id = $this->request->getPost('id');

        $primaryKeys = [
            'produk' => 'id_produk',
            'kategori' => 'id_kategori',
            'paket' => 'id_paket',
            'user' => 'id_user',
        ];
        if (!empty($table) && !empty($id) && isset($primaryKeys[$table])) {
            $primaryKey = $primaryKeys[$table]; 

            $this->model->edit($table, ['deleted_at' => NULL], [$primaryKey => $id]);

            $id_user = session()->get('id_user');
            $this->model->log_activity($id_user, "You have restored an/a $table");

            return redirect()->to('admin/deleted_data')->with('success', 'Data restored successfully.');
        }
    }
    return redirect()->to('home/login');
}

public function restore_all()
{
    if (session()->get('level') == 1) {
        $table = $this->request->getPost('table');

        if (!empty($table)) {
            $this->model->edit($table, ['deleted_at' => NULL], ['deleted_at IS NOT NULL' => NULL]);


            $id_user = session()->get('id_user');
            $this->model->log_activity($id_user, "You have restored all $table");
            return redirect()->to('admin/deleted_data')->with('success', 'All data restored successfully.');
        }
    }
    return redirect()->to('home/login');
}




















    public function killuser($id)
    {
         if (session()->get('level') == 1 || session()->get('level') == 2){
        $where= array('id_user' =>$id);
        $this->model->hapus('user',$where);
        return redirect()->to('admin/deleted_data');
        }else{
            return redirect()->to('/home/login');
        }
    }

    public function killproduk($id)
    {
         if (session()->get('level') == 1){
        $where= array('id_produk' =>$id);
        $this->model->hapus('produk',$where);
        return redirect()->to('admin/deleted_data');
        }else{
            return redirect()->to('/home/login');
        }
    }

    public function killkategori($id)
    {
         if (session()->get('level') == 1){
        $where= array('id_kategori' =>$id);
        $this->model->hapus('kategori',$where);
        return redirect()->to('admin/deleted_data');
        }else{
            return redirect()->to('/home/login');
        }
    }

    public function killpaket($id)
    {
         if (session()->get('level') == 1){
        $where= array('id_paket' =>$id);
        $this->model->hapus('paket_detail',$where);
        $this->model->hapus('paket',$where);
        return redirect()->to('admin/deleted_data');
        }else{
            return redirect()->to('/home/login');
        }
    }

    public function killmetode($id)
    {
         if (session()->get('level') == 1){
        $where= array('id_metode' =>$id);
        $this->model->hapus('metode_pembayaran',$where);
        return redirect()->to('home/tampildata');
        }else{
            return redirect()->to('/home/login');
        }
    }


    public function kill_all()
    {
        if (session()->get('level') != 1) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        $id_user = session()->get('id_user');

        $db = \Config\Database::connect();
        $table = $this->request->getPost('table');

        if (!in_array($table, ['produk', 'kategori', 'paket', 'user'])) {
            return redirect()->to(base_url('admin/deleted_data'))
            ->with('error', 'Invalid table selected.');
        }
    // Disable foreign key checks before deletion
        $db->query("SET FOREIGN_KEY_CHECKS=0");



        if ($table === 'produk') {
            $db->query("DELETE FROM produk WHERE deleted_at IS NOT NULL");
            $this->model->log_activity($id_user, "You have massacred the products");


        } elseif ($table === 'kategori') {
            $db->query("DELETE FROM kategori WHERE deleted_at IS NOT NULL");
            $this->model->log_activity($id_user, "You have massacred the courses");



         } elseif ($table === 'paket') {
            $db->query("
                DELETE FROM paket_detail 
                WHERE id_paket IN (
                    SELECT id_paket FROM paket WHERE deleted_at IS NOT NULL
                    )
                ");

            $db->query("DELETE FROM paket WHERE deleted_at IS NOT NULL");
            $this->model->log_activity($id_user, "You have massacred the courses");





        } elseif ($table === 'user') {
    // Delete users where deleted_at is not null
            $db->query("DELETE FROM user WHERE deleted_at IS NOT NULL");

            $this->model->log_activity($id_user, "You have massacred the users");


        }

    // Re-enable foreign key checks after deletion
        $db->query("SET FOREIGN_KEY_CHECKS=1");

        return redirect()->to(base_url('admin/deleted_data'))
        ->with('success', ucfirst($table) . ' records permanently deleted.');
    }

}









