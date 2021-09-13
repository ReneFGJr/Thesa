<?php

namespace App\Models;

use CodeIgniter\Model;

class RDFConcept extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'rdf_concept';
	protected $primaryKey           = 'id_cc';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_cc','cc_class','cc_pref_term','cc_use',
		'cc_origin','cc_library'
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

	function le($id)
		{
			$this->join('rdf_name', 'cc_pref_term = rdf_name.id_n', 'LEFT');
			$this->join('rdf_class', 'rdf_concept.cc_class = rdf_class.id_c', 'LEFT');
			$this->join('rdf_prefix', 'rdf_class.c_prefix = rdf_prefix.id_prefix', 'LEFT');

			$this->select('rdf_class.c_class, rdf_class.c_type, rdf_class.c_url, rdf_class.c_equivalent');
    		$this->select('rdf_name.n_name, rdf_name.n_lang');    		
			$this->select('rdf_prefix.prefix_ref, rdf_prefix.prefix_url');
			$this->select('rdf_concept.*');
			$this->where('id_cc',$id);
			$dt = $this->First();
			//echo $this->db->getLastQuery();
			return($dt);
		}

	function concept($dt)
		{			
			/* Definição da Classe */
			$Class = new \App\Models\RDFClass();			
			$RDFdata = new \App\Models\RDFdata();
			$RDFLiteral = new \App\Models\RDFLiteral();
			$Property = new \App\Models\RDFClassProperty();

			$cl = $dt['Class'];
			$id_class = $Class->class($cl);

			$id_prefTerm = $RDFLiteral->name($dt['Literal']['skos:prefLabel']);

			/************************************************************* CREATE CONCEPT */
			$dtc = $this
						->where('cc_class',$id_class)
						->where('cc_pref_term',$id_prefTerm)
						->where('cc_library',LIBRARY)
						->First();

			/* Novo */
			if (!is_array($dtc))
				{
					$data['cc_class'] = $id_class;
					$data['cc_pref_term'] = $id_prefTerm;
					$data['cc_use'] = 0;
					$data['cc_origin'] = '';
					$data['cc_library'] = LIBRARY;
					$this->insert($data);

					$dtc = $this
						->where('cc_class',$id_class)
						->where('cc_pref_term',$id_prefTerm)
						->where('cc_library',LIBRARY)
						->First();					
				}

			$id_concept = $dtc['id_cc'];

			if ($id_concept > 0)
				{
					/******************************************************** Literal */
					if (isset($dt['Literal']))
						{
							foreach($dt['Literal'] as $prop => $name)
							{
								$id_prop = $Class->class($prop);
								$idl = $RDFLiteral->name($name);
								$data = array();
								$data['d_r1'] = $id_concept;
								$data['d_p'] = $id_prop;
								$data['d_r2'] = 0;
								$data['d_literal'] = $idl;
								$data['d_library'] = LIBRARY;
								$RDFdata->check($data);
							}
						}
					
					/******************************************************** Relations */					
					if (isset($dt['Relation']))
						{
							foreach($dt['Relation'] as $prop => $id_relation)
							{
								$id_prop = $Class->class($prop);
								$data = array();
								$data['d_r1'] = $id_concept;
								$data['d_p'] = $id_prop;
								$data['d_r2'] = $id_relation;
								$data['d_literal'] = 0;
								$data['d_library'] = LIBRARY;
								$RDFdata->check($data);
							}
						}						
				}
			return $id_concept;
		}
}
