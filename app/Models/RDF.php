<?php

namespace App\Models;

use CodeIgniter\Model;

class RDF extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'rdf_concept';
	protected $primaryKey           = 'id_cc';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [];

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
			$RDFConcept = new \App\Models\RDFConcept();
			$dt['concept'] = $RDFConcept->le($id);
						
			$RDFData = new \App\Models\RDFData();
			$dt['data'] = $RDFData->le($id);

			return($dt);
		}

	function recover($dt=array(),$class='')
		{
			$rst = array();
			$id = $dt['concept']['id_cc'];
			$dt = $dt['data'];
			for ($r=0;$r < count($dt);$r++)
				{
					$line = $dt[$r];
					if ($line['c_class'] == $class)
					{
						if ($line['d_r1'] == $id)
						{
							array_push($rst,$line['d_r2']);
						} else {
							array_push($rst,$line['d_r1']);
						}
					}
				}	
			return $rst;
		}

	function info($id)
		{
			$sx = '';
			$id = round($id);
			$file = '.c/'.round($id).'/.name';
			
			if (file_exists(($file)))
				{
					return file_get_contents($file);
				}
			return '';
		}

	function export_index($class_name,$file='')
		{
			$RDFData = new \App\Models\RDFData();
			$RDFClass = new \App\Models\RDFClass();		
			$RDFConcept = new \App\Models\RDFConcept();
			$EventProceedingsIssue = new \App\Models\EventProceedingsIssue();

			$class = $RDFClass->Class($class_name);
			$rlt = $RDFConcept
						->join('rdf_name', 'cc_pref_term = rdf_name.id_n', 'LEFT')
						->select('id_cc, n_name, cc_use')
						->where('cc_class',$class)
						->where('cc_library',LIBRARY)
						->orderBy('n_name')						
						->findAll();
			
			$flx = 0;
			$fi = array();
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$name = $line['n_name'];

					$upper = ord(substr(mb_strtoupper(ascii($name)),0,1));
					if ($flx != $upper)
						{
							$flx = $upper;
							$fi[$flx] = '';
							
						}
					$link = '<a href="'.URL.(PATH.'v/'.$line['id_cc']).'">';
					$linka = '</a>';
					$fi[$flx] .= $link.$name.$linka.'<br>';
				}
				$s_menu = '<div id="list-example" class=""  style="position: fixed;">';
				$s_menu .= '<h5>'.lang($class_name).'</h5>';
				$s_cont = '<div data-spy="scroll" data-target="#list-example" data-offset="0" class="scrollspy-example">';
				$cols = 0;
				foreach($fi as $id_fi=>$content)
					{
						//$s_menu .= '<a class="list-group-item list-group-item-action" href="#list-item-'.$id_fi.'">'.chr($id_fi).'</a>';
						//$s_menu .= '<a class="border-left" href="#list-item-'.$id_fi.'">'.chr($id_fi).'</a> ';

						$s_menu .= '<a class="border-left" href="#list-item-'.$id_fi.'"><tt>'.chr($id_fi).'</tt></a> ';
						if (($cols++) > 6)
							{
								$cols = 0;
								$s_menu .= '<br>';
							}

						$s_cont .= '<h4 id="list-item-'.$id_fi.'">'.chr($id_fi).'</h4>
						<p>'.$content.'</p>';
					}
				$s_menu .= '</div>';
				$s_cont .= '</div>';

				$sx = bsc('<div style="width: 100%;">'.$s_menu.'</div>',1);
				$sx .= bsc($s_cont,11);
				$sx .= '<style> body {  position: relative; } </style>';
				file_put_contents($file,$sx);
		}

	function export($d1='',$d2=0,$d3='')
	{
		$RDFConcept = new \App\Models\RDFConcept();

		$sx = '';
		$d2 = round($d2);		
		$limit = 50;
		$offset = round($d2)*$limit;

		$sx .= '<h3>D1='.$d1.'</h3>';
		$sx .= '<h3>D2='.$offset.'</h3>';

		$dt = $RDFConcept->select('id_cc')
				->where('cc_library',LIBRARY)
				->orderBy('id_cc')
				->limit($limit,$offset)
				->findAll($limit,$offset);
		$sx .= '<ul>';
		for($r=0;$r < count($dt);$r++)
			{
				$idc = $dt[$r]['id_cc'];
				$sx .= '<li>'.$this->export_id($idc).'</li>';
			}
		$sx .= '</ul>';		
		if (count($dt) > 0)
		{
			$sx .= metarefresh(URL.(PATH.'export/rdf/'.(round($d2)+1)),2);
		} else {
			$sx .= bsmessage(lang('Export_Finish'));
		}
		$sx = bs(bsc($sx,12));
		return $sx;
	}	
	
	function export_id($id)
		{
			$sx = '';
			$id = round($id);
			if ($id > 0)
			{
				$dir = '.c/';
				if (!is_dir($dir)) { mkdir($dir); }
				$dir = '.c/'.round($id).'/';
				if (!is_dir($dir)) { mkdir($dir); }
			} else {
				$sx .= 'ID ['.$id.'] inválido<br>';
			}

			/*************************************************************** EXPORT */
			$RDFData = new \App\Models\RDFData();
			$RDFConcept = new \App\Models\RDFConcept();

			$dt = $this->le($id);

			$class = $dt['concept']['c_class'];
			$txt_name = $dt['concept']['n_name'];

			/******************************************************* ARQUIVOS ********/
			$file_name = $dir.'.name';

			/********************************************************** VARIÁVEIS ****/
			$txt_journal = '';
			$txt_author ='';

			/********************************************************** WORK *********/
			switch($class)
				{
					case 'Work':
						for ($w=0;$w < count($dt['data']);$w++)
							{
								$dd = $dt['data'][$w];
								$dclass = $dd['c_class'];
								switch($dclass)
									{
										case 'title':
										$txt_title = $dd['n_name'];
										break;

										case 'isWorkOf':
										$x = $this->le($dd['d_r2']);
										$txt_journal = $x['concept']['n_name'];
										break;

										case 'creator':
										$x = $this->le($dd['d_r2']);
										if (strlen($txt_author) > 0) { $txt_author .= '; '; }
										$txt_author .= $x['concept']['n_name'];
										break;										
									}
							}
						break;	
				}		
				

			/*************************************************************** HTTP ****/
			if (substr($txt_name,0,4) == 'http')
				{
					$txt_name = '<a href="'.$txt_name.'" target="_new">'.$txt_name.'</a>';
				}				

			/******************************************************** JOURNAL NAME  */
			if (strlen($txt_author) > 0)
				{
					$txt_name = $txt_author .'. '. $txt_title . '. <b>[Anais...]</b> '.$txt_journal.'.';
				}
			
			/******************************************************* SALVAR ARQUIVOS */
			if (strlen($txt_name) > 0) { file_put_contents($file_name,$txt_name); }

			$sx = $txt_name.' exported<br>';
			return $sx;
		}

	function view_data($dt)
		{
			$concept = $dt['concept'];
			$class = $dt['concept']['prefix_ref'].':'.$dt['concept']['c_class'];

			$sx = '';
			$sx .= '<h2>'.$dt['concept']['n_name'];
			$sx .= '<h5>'.$class.'</h5>';

			$sx .= bsc(lang('created_at'),2);
			$sx .= bsc(lang('updated_at'),2);
			$sx .= bsc(lang('cc_library'),1);
			$sx .= bsc(lang('prefix_url'),7);

			$url = $dt['concept']['prefix_url'].'#'.$dt['concept']['c_url'];
			$url = '<a href="'.$url.'" target="_new">'.$url.'</a>';
			$sx .= bsc($dt['concept']['created_at'],2);
			$sx .= bsc($dt['concept']['updated_at'],2);
			$sx .= bsc($dt['concept']['cc_library'],1);
			$sx .= bsc($url,7);

			$sx .= var_dump($dt,false);

			if (isset($dt['data']))
				{
					$dts = $dt['data'];
					print_r($dts);
				}
			

			$sx = bs($sx);
			return $sx;
		}

	function index($d1,$d2,$d3='',$cab='')
		{
			$sx = '';
			$type = get("type");
			switch($d1)
				{					
					case 'inport':
						$sx = $cab;
						switch($type)
							{
								case 'prefix':
								$this->RDFPrefix = new \App\Models\RDFPrefix();
								$sx .= $this->RDFPrefix->inport();
								break;

								case 'class':
								$this->RDFClass = new \App\Models\RDFClass();
								$sx .= $this->RDFClass->inport();
								break;
							}
					break;
					/************* Default */
					default:
						$sx = $cab;
						$sx .= lang('command not found').': '.$d1;
						$sx .= '<ul>';
						$sx .= '<li><a href="'.URL.(PATH.MODULE.'rdf/inport?type=prefix').'">'.lang('Inport Prefix').'</a></li>';
						$sx .= '<li><a href="'.URL.(PATH.MODULE.'rdf/inport?type=class').'">'.lang('Inport Class').'</a></li>';
						$sx .= '</ul>';
				}
			$sx = bs($sx);
			return $sx;
		}
}
