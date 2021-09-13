<?php

namespace App\Models;

use CodeIgniter\Model;

class RDFLiteral extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'rdf_name';
	protected $primaryKey           = 'id_n';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_n','n_name','n_lock','n_lang'
	];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	function name($name,$lg='pt-BR')
		{
			$dt = $this->where('n_name',$name)->First();
			if (!is_array($dt))
				{
					$data['n_name'] = $name;
					$data['n_lock'] = 0;
					$data['n_lang'] = $lg;
					$this->insert($data);
					$dt = $this->where('n_name',$name)->First();
					return $dt['id_n'];
				}
			return $dt['id_n'];
		}
}
