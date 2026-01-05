<?php

namespace App\Models;

use CodeIgniter\Model;

	class M_tugas extends Model
	{

		protected $table = 'tugas';
		protected $primaryKey = 'id_tugas';

		// ✅ MUST EXIST
		protected $allowedFields = [
			'nama_tugas',
			'prioritas',
			'status',
			'tanggal',
		];

		protected $returnType = 'object';

	}
