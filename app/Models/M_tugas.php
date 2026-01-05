<?php

namespace App\Models;
use CodeIgniter\Model;

class M_tugas extends Model
{
public function tampil() {
    return $this->db->table("tugas")
    				->orderBy("prioritas", 'desc')
                    ->get()
                    ->getResult();
}
	public function hapus(){
		return $this->db->table("tugas")
						->delete("id_tugas");
}
	public function getWhere(){
		return $this->db->table("tugas")
						->getWhere("id_tugas")
						->getRow();
}
	public function edit($data){
		return $this->db->table("tugas")
						->update($data, "id_tugas");
}
	public function input($data){
		return $this->db->table("tugas")
						->insert($data);
}

}

	
