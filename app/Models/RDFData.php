<?php

namespace App\Models;

use CodeIgniter\Model;

class RDFData extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'rdf_data';
	protected $primaryKey           = 'id_d';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_d','d_r1','d_r2','d_p','d_library','d_literal'
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

	function check($dt)
		{
			foreach($dt as $field=>$value)
				{
					$this->where($field,$value);
					//echo '<br>'.$field.'==>'.$value;
				}
			$dts = $this->first();

			if (!is_array($dts))
				{

					$this->insert($dt);
					return true;
				}
			return false;
		}

	function view_data($dt)
		{
			$RDF = new \App\Models\RDF();
			$sx = '';
			$ID = $dt['concept']['id_cc'];
			if (isset($dt['data']))
				{
					$dtd = $dt['data'];
					for ($r=0;$r < count($dtd);$r++)
						{
							$line = $dtd[$r];
							$sx .= bsc(lang($line['prefix_ref'].':'.$line['c_class']),2,
									'supersmall border-top border-1 border-secondary my-2');
							if ($line['d_r2'] != 0)
							{
								if ($ID == $line['d_r2'])
									{
										$link = URL.(PATH.'v/'.$line['d_r1']);
										$txt = $RDF->info($line['d_r1'],1);
										if (strlen($txt) > 0)
											{
												$link = '<a href="'.$link.'">'.$txt.'</a>';
											} else {
												$link = '<a href="'.$link.'">Link Inverso</a>';
											}
										
									} else {
										$link = URL.(PATH.'v/'.$line['d_r2']);
										$txt = $RDF->info($line['d_r2'],1);
										if (strlen($txt) > 0)
											{
												$link = '<a href="'.$link.'">'.$txt.'</a>';
											} else {
												$link = '<a href="'.$link.'">Link</a>';
											}
									}
								$sx .= bsc($link,		   10,'border-top border-1 border-secondary my-2');
							} else {
								$sx .= bsc($line['n_name'],10,'border-top border-1 border-secondary my-2');
							}
							
						}
				}
			return bs($sx);
		}

	function le($id)
		{
			$this->join('rdf_name', 'd_literal = rdf_name.id_n', 'LEFT');
			$this->join('rdf_class', 'rdf_data.d_p = rdf_class.id_c', 'LEFT');
			$this->join('rdf_prefix', 'rdf_class.c_prefix = rdf_prefix.id_prefix', 'LEFT');

    		$this->select('rdf_name.id_n, rdf_name.n_name, rdf_name.n_lang');
			$this->select('rdf_class.c_class, rdf_class.c_prefix, rdf_class.c_type');	
			$this->select('rdf_prefix.prefix_ref, rdf_prefix.prefix_url');
    		$this->select('rdf_data.*');	

			$where = "(d_r1 = $id) OR (d_r2 = $id)";
			$this->where($where);
			$dt = $this->FindAll();
			return($dt);
		}	
}
