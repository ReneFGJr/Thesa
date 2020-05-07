<?php
defined('BASEPATH') OR exit('No direct script access allowed');
global $lang;

/**
 * CodeIgniter RDF Helpers
 *
 * @package     CodeIgniter
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Rene F. Gabriel Junior <renefgj@gmail.com>
 * @link        http://www.sisdoc.com.br/
 * @version     v0.19.10.20
 */

/*
ALTER TABLE `rdf_form_class` ADD `sc_global` INT NOT NULL DEFAULT '0' AFTER `sc_ord`;
ALTER TABLE `rdf_form_class` ADD `sc_gropup` VARCHAR(20) NOT NULL AFTER `sc_ativo`;
*/
class rdf
{
	var $limit = 10;
	function __construct()
	{
		global $msg;
		$msg['class_title'] = 'Classes disponíveis';
		$msg['class_edit_new'] = 'criar nova Classe ou Propriedade';
		$msg['class_edit'] = 'Editar Classe ou Propriedade';
		$msg['class_parameters'] = 'Parametros da classe';
		$msg['class_proprieties'] = 'Propriedades da classe';
		$msg['add_new_propriety'] = 'nova propriedade';
		$msg['form_class_edit'] = 'Formulário de entrada de dados';
		$msg['sc_global'] = 'Global';
		$msg['sc_local'] = 'Classe somente deste sistema';
		$msg['return'] = '<< Voltar';
		$msg['edit'] = 'Editar';
		$msg['edit_class'] = 'Editar Classe';
		$msg['submit'] = 'Gravar >>';
		$msg['Classe_name'] = 'Classe';
		$msg['check_form'] = 'Checar formulário';
		$msg['form_text'] = 'Informe o texto';
	}

	function index($link,$path,$id,$id2,$id3)
	{        

		$dt = array();
		$sx = '';
		switch($path)
		{
			case 'id':
				$this->rdf_export($id);
				exit;
			break;			

			case 'xml':
				$this->rdf_export($id);
				exit;
			break;		

			case 'text':
			$sx = $this->text_edit($id);
			break;

			case 'form':
			$form = 0;
			$sx = $this-> form_ajax($id, $id2,$id3);
			return($sx);
			break;

			case 'check_form':			
			$sx = $this-> form_check($id);
			redirect(base_url(PATH.'config/class/view/'.$id));
			break;

			case 'save':
			$r1 = get("concept");
			$prop = get("prop");
			$r2 = get("resource");
			$this->set_propriety($r1, $prop, $r2);
			sleep(1);
			echo '<script> wclose(); </script>';
			break;


			/**************** FORMULARIOS **************/
			case 'forms':
			$sx .= msg('FORMS');
			$sx .= $this -> class_view_form($id);
			break;


			/**************** FORMULARIOS **************/
			case 'formss':
			$sx .= msg('FORMS');
			$sx .= $this->form_ed($id2,$id);
			break;				

			/**************** view **************/
			case 'view':
			$sx = '<div class="row">';
			$sx .= '<h1>'.msg('Classes').' '.msg('and').' '.msg('Proprieties').'</h1>';
			$sx .= $this->class_view($id);				
			$sx .= $this->class_view_data($id);
			$sx .= $this->class_view_form($id);
			$sx .= '</div>';						
			break;

			/**************** edit **************/
			case 'ed':
			$sx = '<div class="row">';
			$sx .= '<h1>'.msg('Classes').' '.msg('and').' '.msg('Proprieties').'</h1>';
			$sx .= $this->class_ed($id);				
			$sx .= '</div>';						
			break;

			case 'exclude':
			if ($id2 == 'confirm')
			{
				$this->data_exclude($id);
				$sx = '<script> wclose(); </script>';
				return($sx);
			}
			$sx = $this->cas_exclude($id);
			return($sx);
			break;

			case 'search':
			$sx = '';
			$sx = $this->ajax_search($id2,$id);
			echo $sx;
			exit;
			break;

			/************/
			/**************** AJAX SEARCH *******/
			case 'ajax_save':
			$q = get("q");
			$id = get("dd10");
			$pp = get("dd11");
			$tp = get("type");
			switch($tp)
			{
				/*************************** text **************/
				case 'text':
				$idn = $this->frbr_name($q);
				echo '<br>';
				echo '--id-->'.$id.'<br>';
				echo '--pp-->'.$pp.'<br>';
				$this->set_propriety($id,$pp,0,$idn);
				echo 'Saved';
				break;

				default:

				echo "############### ".$q;
							//$this->ajax_search($id);
				break;
			}
			break;

			/**************** AJAX SEARCH *******/
			case 'ajax_search':
			$q = get("q");
			echo "############### ".$q;
					//$this->ajax_search($id);
			break;

			/**************** AJAX EXCLUDE **************/
			case 'exclude':
			echo '<div class="col-md-12">';
			echo $this -> cas_exclude($id2);
			echo '</div>';
			exit;			
			break;

			case 'exclude_confirm':
			echo message("Excluído!");
			echo refresh('#',1);
			$this->data_exclude($id);

			break;

			/**************** AJAX INCLUDE **************/
			case 'include':	
			echo '<div class="col-md-12">';
			echo $this -> cas_include($id,$id2,$id3);
			echo '</div>';
			exit;			
			exit;
			break;

			/**************** AJAX **************/
			case 'ajax':	
			$chk = md5($id);	
			echo $this->ajax($id,$chk);
			exit;
			break;


			/**************** AJAX Update **************/
			case 'ajax_update':	
			$chk = md5($id);	
			echo $this->ajax_update($id,$chk);
			exit;
			break;				

			default:
			/**************** row **************/
			$ac = get("acao");
			if (perfil("#ADM") > 0)
			{
				$sx .= '<div class="col-md-12">';
				$sx .= '<h1>'.msg('Classes').' '.msg('and').' '.msg('Proprieties').'</h1>';
				$sx .= '| <a href="'.base_url(PATH.'config/class/ed/0/0').'">'.msg('new_class_propr').'</a> |';
				$sx .= '<hr>';
				$sx .= '</div>';
			}		
			if ($ac=='')				
			{
				$sx .= $this->class_row();
			} else {
				$sx .= $this->class_view($ac);
			}
			$sx .= '</div>';
			break;

			case 'authority' :
			if (perfil("#ADM") == 1) {
				if ($ac == 'update') {
					$sx .= $this -> frbr -> viaf_update();
				} else {
					$sx .= '<br><a href="' . base_url(PATH . 'config/authority/update') . '" class="btn btn-secondary">' . msg('authority_update') . '</a>';
				}
				$sx .= '<br><br><h3>' . msg('Authority') . ' ' . msg('viaf') . '</h3>';
				$sx .= $this -> frbr -> authority_class();
			}
			break;
		}	
		return($sx);
	}


	#################################################### LE CONCEPT
	function le($id) {
		$CI = &get_instance();
		$sql = "select * from rdf_concept 
		INNER JOIN rdf_class ON cc_class = id_c
		LEFT JOIN rdf_prefix ON c_prefix = id_prefix
		LEFT JOIN rdf_name ON cc_pref_term = id_n
		WHERE id_cc = $id";
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			for ($r = 0; $r < count($rlt); $r++) {
				$line = $rlt[$r];
				return ($line);
			}
		} else {
			return ( array());
		}
	}
	/* Identifica ID da lista com as propriedades */
	function filter($dt,$prop)
	{
		$rst = array();
		for ($r=0;$r < count($dt);$r++)
		{
			$ln = $dt[$r];
			$class = trim($ln['c_class']);
			if (trim($ln['c_class']) == trim($prop))
			{
				$ids = $ln['d_r1'];
				array_push($rst,$ids);
			}
		}
		return($rst);
	}

	function find($n, $prop = '', $equal = 1) {
		$CI = &get_instance();
		/* EQUAL */
		$wh = "(n_name like '%" . $n . "%')";
		if ($equal == 1) {
			$wh = "(n_name = '" . $n . "')";
		} else {
			if (perfil("#ADM")) {
				echo "** ALERT - use like in " . $n . ' ********<br>';
			}
		}

		/* PROPRIETY */
		if (strlen($prop) > 0) {
			$class = $this -> find_class($prop);
			$wh .= "and ((d_p = $class) or (cc_class = $class))";
		} else {
			$wh .= '';
		}

		$sql = "select d_r1, c_class, d_r2, n_name from rdf_name
		INNER JOIN rdf_data on d_literal = id_n 
		INNER JOIN rdf_class ON d_p = id_c
		INNER JOIN rdf_concept ON id_cc = d_r1
		where $wh";

		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();

		if (count($rlt) > 0) {
			$line = $rlt[0];
			return ($line['d_r1']);
		}
		return (0);
	}	

	function le_dados($id,$tp=0)
	{
		$CI = &get_instance();
		$sql = "select * from rdf_data 
		left join rdf_name ON id_n = d_literal
		where id_d = ".round($id);
		$rrr = $CI -> db -> query($sql);
		$rrr = $rrr -> result_array();
		$line = $rrr[0];
		return($line);
	}	

	function le_data($id, $prop = '') {
		$CI = &get_instance();
		if (strlen($prop) > 0) {
			$wh = " AND (c_class = '$prop')";
		} else {
			$wh = '';
		}
		$cp = 'd_r2, d_r1, c_order, c_class, id_d, n_name, n_lang, prefix_ref';
		$cp_reverse = 'd_r2 as d_r1, d_r1 as d_r2, c_order, c_class, id_d, n_name, n_lang, prefix_ref';
		$sql = "select $cp,1 as rule from rdf_data as rdata
		INNER JOIN rdf_class as prop ON d_p = prop.id_c 
		INNER JOIN rdf_concept ON d_r2 = id_cc 
		INNER JOIN rdf_name on cc_pref_term = id_n
		LEFT JOIN rdf_prefix ON c_prefix = id_prefix
		WHERE d_r1 = $id and d_r2 > 0 " . $wh . cr() . cr();
		$sql .= ' union ' . cr() . cr();
		/* TRABALHOS */
		$sql .= "select $cp_reverse,2 as rule from rdf_data as rdata
		INNER JOIN rdf_class as prop ON d_p = prop.id_c 
		INNER JOIN rdf_concept ON d_r1 = id_cc 
		INNER JOIN rdf_name on cc_pref_term = id_n
		LEFT JOIN rdf_prefix ON c_prefix = id_prefix		
		WHERE d_r2 = $id and d_r1 > 0 " . $wh . cr() . cr();
		$sql .= ' union ' . cr() . cr();
		$sql .= "select $cp,3 as rule from rdf_data as rdata
		LEFT JOIN rdf_class as prop ON d_p = prop.id_c 
		LEFT JOIN rdf_concept ON d_r2 = id_cc 
		LEFT JOIN rdf_name on d_literal = id_n
		LEFT JOIN rdf_prefix ON c_prefix = id_prefix
		WHERE d_r1 = $id and d_r2 = 0 " . $wh . cr() . cr();

		/* USE */
		$prop = $this -> find_class("equivalentClass");
		$sqll = "SELECT * FROM rdf_data where (d_r2 = $id or d_r1 = $id) and d_p = $prop";

        //$sqll = "select * from rdf_concept where (cc_use = $id) and (id_cc <> cc_use)";
		$rrr = $CI -> db -> query($sqll);
		$rrr = $rrr -> result_array();
		for ($r = 0; $r < count($rrr); $r++) {
			$line = $rrr[$r];
			$iduse = $line['d_r1'];
			if ($iduse == $id) {
				$iduse = $line['d_r2'];
			}
			$sql .= ' union ' . cr() . cr();
			$sql .= "select $cp_reverse, " . (10 + $r) . " as rule from rdf_data as rdata
			INNER JOIN rdf_class as prop ON d_p = prop.id_c 
			INNER JOIN rdf_concept ON d_r1 = id_cc 
			INNER JOIN rdf_name on cc_pref_term = id_n
			WHERE d_r2 = $iduse and d_r1 > 0 and d_p <> $prop" . cr() . cr();
		}
		$sql .= " order by c_order, c_class, rule, n_lang desc, id_d";

		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		return ($rlt);
	}  

	/******************************** PREFIX AND SUFIX ************/
	function rdf_prefix($url = '') {
		$CI = &get_instance();
		$pre = substr($url, 0, strpos($url, ':'));
		$pos = substr($url, strpos($url, ':') + 1, strlen($url));
		$sx = $pre;
		$sql = "select * from rdf_prefix where prefix_ref = '$pre' ";
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$line = $rlt[0];
		$uri = trim($line['prefix_url']);
		return ($uri);
	}

	function rdf_sufix($url = '') {
		$pre = substr($url, 0, strpos($url, ':'));
		$pos = substr($url, strpos($url, ':') + 1, strlen($url));
		return ($pos);
	}


	function pagination($t) 
	{
		$pg = round('0' . get("pg"));
		$t = count($t);
		$l = $this -> limit;
		/***************************** math *************/
		if ($l == 0) 
		{
			return ('');
		}
		$p = ($t / $l);
		$p = (int)$p;
		if (($t / $l) > $p) { $p++; }

		$sx = '<div class="container">' . cr();
		$sx .= '<div class="row">' . cr();
		$sx .= '<nav aria-label="Page navigation example"><ul class="pagination">' . cr();
		$ds = 'disabled';
		if ($pg > 0) { $ds = ''; }
		$sx .= '<li class="page-item ' . $ds . '"><a class="page-link" href="?pg=' . ($pg - 1) . '">Previous</a></li>' . cr();
		for ($r = 0; $r < $p; $r++) 
		{
			$ac = '';
			if ($pg == $r) { $ac = 'active'; }
			$sx .= '<li class="page-item ' . $ac . '"><a class="page-link " href="?pg=' . $r . '">' . ($r + 1) . '</a></li>' . cr();
		}
		$ps = 'disabled';
		if ($pg < ($p - 1)) { $ps = ''; }
		$sx .= '<li class="page-item ' . $ps . '"><a class="page-link " href="?pg=' . ($pg + 1) . '">Next</a></li>' . cr();
		$sx .= '</ul></nav>' . cr();
		$sx .= '</div>';
		$sx .= '</div>';
		return ($sx);
	}	

	function related($id) {
		$CI = &get_instance();
		$pg = round('0' . get("pg"));
		$limit = $this->limit;
		$offset = $limit * $pg;
		/******************************************** by manifestation ********/
		$cl1 = $this -> find_class('isEmbodiedIn');
		$cl2 = $this -> find_class('isRealizedThrough');

		/** div **/
		$sx = '<div class="container">' . cr();
		$sx .= '<div class="row">' . cr();

		$sql = "SELECT dd3.d_r1 as w, count(*) as mn FROM `rdf_data` as dd1 
		left JOIN rdf_data as dd2 ON dd1.d_r1 = dd2.d_r2 
		left JOIN rdf_data as dd3 ON dd2.d_r1 = dd3.d_r2 
		LEFT JOIN rdf_class ON dd2.d_p = id_c
		where dd1.d_r2 = $id and dd2.d_p = 88 and dd3.d_p = 37
		group by w";
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$pags = $this -> pagination($rlt);
		for ($r = 0; $r < count($rlt); $r++) {
			if (($r >= $offset) and ($r < ($offset + $limit))) {
				$sx .= '<div class="col-lg-2 col-md-4 col-xs-3 col-sm-6 text-center" style="line-height: 80%; margin-top: 40px;">' . cr();
				$line = $rlt[$r];
				$idm = $line['w'];
                //$sx .= $this -> show_manifestation_by_works($idm, 0, 0);
				if ($line['mn'] > 1) {
					$sx .= '<br>';
					$sx .= '<a href="' . base_url(PATH . 'v/' . $idm) . '" class="small">';
					$sx .= '<span style="color:red"><i>' . msg('see_others_editions') . '</i></span>';
					$sx .= '</a>' . cr();
				}
				$sx .= '</div>';
			}
		}

		/******************************************** by expression ***********/
		if (count($rlt) == 0) {
			$sql = "SELECT dd2.d_r1 as w, count(*) as mn FROM `rdf_data` as dd1 left JOIN rdf_data as dd2 ON dd1.d_r1 = dd2.d_r2 LEFT JOIN rdf_class ON dd2.d_p = id_c where dd1.d_r2 = $id and dd2.d_p = 7
			group by w ";
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$pags = $this -> pagination($rlt);
			for ($r = 0; $r < count($rlt); $r++) 
			{
				if (($r >= $offset) and ($r < ($offset + $limit))) 
				{
					$sx .= '<div class="col-lg-2 col-md-4 col-xs-3 col-sm-6 text-center" style="line-height: 80%; margin-top: 40px;">' . cr();
					$line = $rlt[$r];
					$idm = $line['w'];
                    //$sx .= $this -> show_manifestation_by_works($idm, 0, 0);
					if ($line['mn'] > 1) 
					{
						$sx .= '<br>';
						$sx .= '<a href="' . base_url(PATH . 'v/' . $idm) . '" class="small">';
						$sx .= '<span style="color:red"><i>' . msg('see_others_editions') . '</i></span>';
						$sx .= '</a>' . cr();
					}
					$sx .= '</div>';
				}
			}
		}

		/** div **/
		$sx .= '</div>';
		$sx .= '</div>';

		return ($pags . $sx);
	}	  

	#################################################################### Classes
	function xxxx_classes($ac,$id,$chk)
	{
		switch($ac)
		{
			########## AJAX
			case 'ajax':
			$sx = '';
			$this->ajax($id,$chk);
			break;
			case 'ajax_update':

			########## FORM CLASS
			case 'form':
			$sx = '<div class="col-md-12"><h1>'.msg('form_class_edit').'</h1></div>'.cr();
			$sx .= $this->form_ed($chk,$id);
			break;
			########## SHOW
			case 'c':
			$sx = $this->class_show($id);
			break;


			########## DEFAULT
			default:
			$sx = '<div class="col-md-12"><h1>'.msg('class_title').'</h1></div>'.cr();
			$sx .= '<div class="col-md-12 small">| <a href="'.base_url(PATH.'config/class/ed/0/0').'" >'.msg('class_edit_new').'</a> |</div></br>';
			$sx .= $this->class_row();
			break;
		}
		return($sx);
	}

	function class_view($id)
	{
		$line = $this->le_class($id);
		if (count($line) == 0)
		{
			echo "OPS-2";
			exit;
			redirect(base_url(PATH.'class'));
		}
		switch($line['c_type'])
		{
			case 'C':
			$sx = '<div class="col-md-12">';
			$sx .= '<h1>'.$line['c_class'].'</h1>';
			$sx .= '<a href="'.base_url(PATH.'config/class/').'" class="btn btn-outline-primary nopr" style="margin-right: 10px;">'.msg('return').'</a>';
			$sx .= '<a href="'.base_url(PATH.'config/class/ed/'.$line['id_c']).'" class="btn btn-outline-primary nopr" style="margin-right: 10px;">'.msg('class_edit').'</a>';
			$sx .= '<a href="'.base_url(PATH.'config/class/forms/'.$line['id_c'].'/0').'" class="btn btn-outline-primary nopr" style="margin-right: 10px;">'.msg('form_edit_class').'</a>';			
			$sx .= '<a href="'.base_url(PATH.'config/class/check_form/'.$line['id_c'].'/0').'" class="btn btn-outline-primary nopr" style="margin-right: 10px;">'.msg('check_form').'</a>';			
			$sx .= $this->class_update_data($line);			
			$sx .= '</div>';
			$sx .= '<div class="col-md-1">'.cr();
			$sx .= '</div>'.cr();

			/* Propriedades */
			$sx .= '<div class="col-md-6">'.cr();
			$sx .= '<h4>'.msg('class_parameters').'</h4>';
			$sx .= 'Pesquisável: <b>'.sn($line['c_find']).'</b></br>'.cr();
			$sx .= 'Repetível: <b>'.sn($line['c_repetitive']).'</b></br>'.cr();
			$sx .= '<hr>';
			$sx .= 'URL Source: <b>'.$line['c_url'].'</b></br>'.cr();
			$sx .= 'Atualizado em <b>'.stodbr($line['c_url_update']).'</b></br>'.cr();
			$sx .= '</div>'.cr();

			break;
			case 'P':
			break;
			default:
			$sx = '<div class="col-md-12">';
			$sx .= msg_erro('000','Classe não definida - '.$line['c_type']);
			$sx .= '</div>';
			break;
		}
		return($sx);
	}

	function show($dt)
	{

		$sx = '<div class="col-12">';
		$sx .= 'no show defined';
		$sx .= '<h1 class="rdf_name">'.$dt['n_name'].'</h1>';
		$sx .= '</div>';

			//echo '<pre>';
			//print_r($dt);
		return($sx);
	}

	function extract_id($dt,$class,$id=0)
	{
		$rs = array();
		for ($r=0;$r < count($dt);$r++)
		{
			$prop = $dt[$r]['c_class'];
			if ($prop == $class)
			{
				$idr = $dt[$r]['d_r2'];
				if ($idr == $id)
				{
					$idr = $dt[$r]['d_r1'];
				}
				array_push($rs,$idr);
			}
		}
		return($rs);
	}

	function extract_content($dt,$class,$id=0)
	{
		$rs = array();
		for ($r=0;$r < count($dt);$r++)
		{
			$prop = $dt[$r]['c_class'];
			if ($prop == $class)
			{
				array_push($rs,$dt[$r]['n_name']);
			}
		}
		return($rs);
	}	

	function show_data($r) {
		$CI = &get_instance();
		if (strlen($r) == 0) {
			return ('');
		}
		$sx = '';
		$sx .= '<div class="container">';
		$sx .= '<div class="row">';
		$dt = $this->le($r);
		$class = $dt['c_class'];

		/****************************************** return if empty */
		if (count($dt) == 0) {
			redirect(base_url(PATH));
		}
		/**************************************************** show **/
		$line = $dt;
		$fcn = 'rdf_show_'.$line['c_class'];
		/* exportação */
		$sx .= 'Export: ';
		$sx .= '<a href="'.base_url(PATH.'rdf/xml/'.$r).'">RDF</a>';

		if (function_exists($fcn))
		{
			$fcn = '$sx .= '.$fcn.'($line);';
			eval($fcn);
		} else {			
			$sx .= $this->show($dt);
			$sx .= '<br/><br/>default: '.$fcn.' not found<br/><br/>';
		}
		$sx .= '</div>';
		$sx .= '</div>';

		$sx .= '<div class="container">';
		$sx .= '<div class="row">';

		$sx .= '<div class="col-10">class: ' . $line['c_class'] . '</div>';
		$sx .= '<div  class="col-2 text-right">'.$this->link($line['cc_origin']).'</div>';


		/*************************** Editar classe */
		if (perfil("#ADM"))
		{
			$sx .= '<div class="col-md-12 small">';
			$sx .= '| <a href="'.base_url(PATH).'">'.msg('return').' </a>';
			$sx .= '| <a href="'.base_url(PATH.'a/'.$r).'">'.msg('edit').'</a> |';
			$sx .= '</div>';
		}

		$sx .= '</div>';

		$sx .= '</div>';
		$sx .= '</div>';
		return ($sx);
	}	

	/***  FIND CLASS **/
	function find_class($class,$create=1) {
		$CI = &get_instance();
		$nclass = $class;
		$wh = '';
		$inner = '';
		if (strpos($class,':') > 0)
		{				
			$prefix = substr($class,0,strpos($class,':'));
			$class = substr($class,strpos($class,':')+1,strlen($class));
			$wh = " AND (prefix_ref = '$prefix') ";
			$inner = 'inner join rdf_prefix ON c_prefix = id_prefix ';
		}

		$sql = "select * from rdf_class
		$inner					
		WHERE (c_class = '$class') ".$wh;
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			if ($create == 1)
			{
				$this->class_create($nclass);
				$rlt = $CI -> db -> query($sql);
				$rlt = $rlt -> result_array();
			} else {
				return(0);
			}
		}
		$line = $rlt[0];
		return ($line['id_c']);
	}

	function class_create($class)
	{
		$CI = &get_instance();
		$pre = '';
		$type = 'P';
		if (strpos($class,':') >= 0)
		{
			$pre = substr($class,0,strpos($class,':'));
			$class = substr($class,strpos($class,':'),strlen($class));			
			$class = troca($class,':','');
			$class = troca($class,' ','');
		}
		if (substr($class,0,1) == UpperCaseSql(substr($class,0,1)))
		{
			$type = 'C';
		} 
		$pre_id = $this->prefix($pre);

		$sql = "select * from rdf_class where c_class = '$class' ";
		$rlt = $CI->db->query($sql);
		$rlt = $rlt->result_array();
		if (count($rlt) == 0)
		{
			$sqli = "insert into rdf_class
			(c_prefix, c_class, c_type )
			values
			($pre_id,'$class','$type')";

			$rlt = $CI->db->query($sqli);
			sleep(1);
			$rlt = $CI->db->query($sql);
			$rlt = $rlt->result_array();
		}
		return($rlt[0]['id_c']);
	}

	function prefixn($dt)
	{
		$pre = trim($dt['prefix_ref']);
		$class = trim($dt['c_class']);
		if (strlen($class) > 0)
		{
			if (strlen($pre) > 0)
			{
				$sx = $pre.':'.$class;
			} else {
				$sx = $class;
			}
		} else {
			$sx = '<i>'.msg('none').'</i>';
		}
		return($sx);

	}

	function prefix($pre)	
	{
		$CI = &get_instance();
		if (strlen($pre) == 0)
		{
			return(0);
		}
		$sql = "select * from rdf_prefix where prefix_ref = '$pre' ";
		$rlt = $CI->db->query($sql);
		$rlt = $rlt->result_array();
		if (count($rlt) == 0)
		{
			$sqli = "insert into rdf_prefix
			(prefix_ref,prefix_url,prefix_ativo)
			values
			('$pre','http://www.brapci.inf.br/ontology/$pre',1)";
			$rlt = $CI->db->query($sqli);
			sleep(1);
			$rlt = $CI->db->query($sql);
			$rlt = $rlt->result_array();
		}
		return($rlt[0]['id_prefix']);
	}

	function le_class($id)
	{
		$CI = &get_instance();
		$sql = "select * from rdf_class 
		LEFT JOIN rdf_prefix ON c_prefix = id_prefix
		where id_c = ".round($id);
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0)
		{
			$line = $rlt[0];
			return($line);
		} else {
			return(array());
		}
	}

	function class_row() {
		$CI = &get_instance();
		/**************** class *************************/
		$sql = "select * from rdf_class 
		LEFT JOIN rdf_prefix ON c_prefix = id_prefix
		where c_type = 'C' 
		order by prefix_ref, c_type, c_class";
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '';
		$tp = '';
		$lg = array('C' => 'Classe', 'P' => 'Propriedade');
		$sx .= '<div class="col-md-1">';
		$sx .= '<b>' . $lg['C'] . '</b>';
		$sx .= '</div>';

		########### class
		$sx .= '<div class="col-md-5">';
		$xp = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$prefix = $line['prefix_ref'];
			if (strlen($prefix) > 0)
			{
				$prefix .= ':';
			}
			if ($xp != $prefix)
			{
				$sx .= '<h5>'.$prefix.'</h5>';
				$xp = $prefix;
			}
			if (perfil("#ADM") > 0)
			{
				$link = '<a href="' . base_url(PATH . 'config/class/view/' . $line['id_c']) . '">';
				$linka = '</a>';
			} else {
				$link = '';
				$linka = '';
			}

			$sx .= msg($line['c_class']);
			$sx .= ' (' . $link . $prefix.$line['c_class'] . $linka . ')';

			if ($line['c_find'] == 1)
			{
				$sx .= ' <img src="'.base_url('img/icon/icon_find.png').'" style="height: 16px;">';
			}			
			$sx .= '<br>';
		}
		$sx .= '</div>';

		/**************** propriety **********************/
		$sql = "select * from rdf_class where c_type = 'P' order by c_type, c_class";
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx .= '<div class="col-md-1">';
		$sx .= '<b>' . $lg['P'] . '</b>';
		$sx .= '</div>';

		########### proprieties
		$sx .= '<div class="col-md-5">';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$xtp = $line['c_type'];
			if (perfil("#ADM") > 0)
			{			
				$link = '<a href="' . base_url(PATH . 'config/class/prop/' . $line['id_c']) . '">';
				$linka = '</a>';
			} else {
				$link = '';
				$linka = '';
			}
			$sx .= msg($line['c_class']);
			$sx .= ' (' . $link . $line['c_class'] . $linka . ')';
			if ($line['c_find'] == 1)
			{
				$sx .= ' <img src="'.base_url('img/icon/icon_find.png').'" style="height: 16px;">';
			}
			$sx .= '<br>';
		}
		$sx .= '</div>';
		return ($sx);
	}

	function class_ed($id)
	{
		$cp = array();
		array_push($cp, array('$H8', 'id_c', '', false, true));		
		array_push($cp, array('$Q id_prefix:prefix_ref:select * from rdf_prefix where prefix_ativo = 1', 'c_prefix', 'Prefix', true, true));
		array_push($cp, array('$S100', 'c_class', 'Classe', true, true));
		array_push($cp, array('$O : &C:Classe&P:Propriety', 'c_type', 'Tipo', true, true));
		array_push($cp, array('$O 1:SIM&0:NÃO', 'c_find', 'Busca', true, true));
		array_push($cp, array('$O 1:SIM&0:NÃO', 'c_vc', 'Vocabulário Controlado', true, true));
		array_push($cp, array('$S100', 'c_url', 'URL', false, true));
		array_push($cp, array('$B8', '', 'Gravar', false, true));
		$form = new form;
		$form -> id = $id;
		$sx = $form -> editar($cp, 'rdf_class');
		if ($form -> saved > 0) {
			if ($id > 0)
			{
				redirect(base_url(PATH . 'config/class/'.$id));			
			} else {
				redirect(base_url(PATH . 'config/class'));			
			}

		}	
		return($sx);	
	}

	function form_ed($id,$id2,$cl=0) {
		$form = new form;
		$form -> id = $id;
		$form -> return = base_url(PATH.'class/c/'.$cl);
		$cp = array();	
		$sqlc = "select * from rdf_class where c_type = 'C'";
		if (round($id2) > 0) 
		{ 
			$sqlc .= ' and id_c = '.$id2; 
			//$_POST['dd1'] = $id2;
		}
		$sqlp = "select * from rdf_class where c_type = 'P'";
		$sqlc2 = "select * from rdf_class where c_type = 'C'";
		if ($cl > 0)
		{
			$sqlc .= ' AND id_c = '.round($cl);
			$sqlc2 .= ' AND id_c <> '.round($cl);
		}

		array_push($cp, array('$H8', 'id_sc', '', false, false));
		array_push($cp, array('$Q id_c:c_class:' . $sqlc, 'sc_class', msg('resource'), true, true));
		array_push($cp, array('$Q id_c:c_class:' . $sqlp, 'sc_propriety', msg('propriety'), true, true));
		array_push($cp, array('$Q id_c:c_class:' . $sqlc2, 'sc_range', msg('range'), true, true));

		array_push($cp, array('$O 1:Ativo&0:Inativo', 'sc_ativo', msg('ativo'), true, true));
		array_push($cp, array('$R 0:'.msg('Yes').'&'.SYSTEM_ID.':'.msg('sc_local'), 'sc_global', msg('sc_global'), true, true));
		array_push($cp, array('$A', '', msg('sc_group'), False, true));
		array_push($cp, array('$S', 'sc_group', msg('sc_group'), False, true));
		array_push($cp, array('$[1:99]', 'sc_ord', msg('ordem'), true, true));

		$sx = $form -> editar($cp, 'rdf_form_class');	

		if ($form -> saved) {
			if (round($cl) > 0)
			{
				redirect(base_url(PATH.'class/c/'.$cl));			
			} else {
				$sx .= '
				<script>
				window.opener.location.reload();
				close();
				</script>
				';

			}

		}
		return ($sx);
	}	

	####################################### CONCEPT	
	function rdf_concept($term, $class, $orign = '') {
		$CI = &get_instance();
		/**** recupera codigo da classe *******************/
		$cl = $this -> find_class($class);
		$dt = date("Y/m/d H:i:s");
		if ($term == 0) {
			$sql = "select * from rdf_concept
			WHERE cc_class = $cl and cc_created = '$dt'
			ORDER BY id_cc";
		} else {
			if (strlen($orign) > 0) {
				$sql = "select * from rdf_concept
				WHERE cc_class = $cl and (cc_pref_term = $term or cc_origin = '$orign')";
			} else {
				$sql = "select * from rdf_concept
				WHERE cc_class = $cl and (cc_pref_term = $term)";
			}
		}
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$id = 0;
		$date = date("Y-m-d");

		if (count($rlt) == 0) {

			$sqli = "insert into rdf_concept
			(cc_class, cc_pref_term, cc_created, cc_origin, cc_update)
			VALUES
			($cl,$term,'$dt','$orign', '$date')";
			$rlt = $CI -> db -> query($sqli);
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$id = $rlt[0]['id_cc'];
		} else {
			$id = $rlt[0]['id_cc'];
			$line = $rlt[0];
			$compl = '';
			if ((strlen($orign) > 0) and ((strlen(trim($line['cc_origin'])) == 0) or ($line['cc_origin'] == 'ERRO:'))) {
				$compl = ", cc_origin = '$orign' ";
			}
			$sql = "update rdf_concept set cc_status = 1, cc_update = '$date' $compl where id_cc = " . $line['id_cc'];
			$rlt = $CI -> db -> query($sql);
		}
		return ($id);
	}

	/********************************************** Conta o número de classes ************/
	function index_count($lt = '', $class = 'Person', $nouse = 0) {
		$CI = &get_instance();
		$f = $this -> find_class($class);
        //$this -> check_language();

		$wh = '';
		if ($nouse == 1) {
			$wh .= " and C1.cc_use = 0 ";
		}

		$sql = "select n_name, id, count(*) as total, id From (
		select N1.n_name as n_name, C1.id_cc as id
		FROM rdf_concept as C1
		INNER JOIN rdf_name as N1 ON C1.cc_pref_term = N1.id_n
		LEFT JOIN rdf_concept as C2 ON C1.cc_use = C2.id_cc
		LEFT JOIN rdf_name as N2 ON C2.cc_pref_term = N2.id_n
		INNER JOIN rdf_data ON C1.id_cc = d_r2
		where C1.cc_class = " . $f . " $wh 
		) as tabela
		group by n_name, id
		ORDER BY n_name";                        

		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();

		return ($rlt);
	}
	/********************************************************************************** List Class */

	function rdf_list($lt = '', $class = '', $nouse = 0) {
		$CI = &get_instance();
		$f = $this -> find_class($class);
		//$this -> check_language();
		$wh = '';
		if ($nouse == 1) {
			$wh .= " and C1.cc_use = 0 ";
		}

		$sql = "select N1.n_name as n_name, N1.n_lang as n_lang, C1.id_cc as id_cc,
		N2.n_name as n_name_use, N2.n_lang as n_lang_use, C2.id_cc as id_cc_use         
		FROM rdf_concept as C1
		INNER JOIN rdf_name as N1 ON C1.cc_pref_term = N1.id_n
		LEFT JOIN rdf_concept as C2 ON C1.cc_use = C2.id_cc
		LEFT JOIN rdf_name as N2 ON C2.cc_pref_term = N2.id_n
		where C1.cc_class = " . $f . " $wh 
		ORDER BY N1.n_name";

		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$sx = '<div class="col"><div class="col-12">';
		$sx .= '<h5>' . msg('total_subject') . ' ' . number_format(count($rlt), 0, ',', '.') . ' ' . msg('registers') . '</h5>';
		$sx .= '<ul>';
		$l = '';
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$idx = $line['id_cc'];
			$name_use = trim($line['n_name']);

			$filex = 'c/' . $idx . '/name.nm';
			if (file_exists($filex)) {
				$name_use = load_file_local($filex);
			}

			$link = '<a href="' . base_url(PATH . 'v/' . $line['id_cc']) . '">';
			$linka = '</a>';
			if ($line['id_cc_use'] > 0) {
				$link = '';
				$linka = '';
				$x2 = ucase($line['n_name_use']);
				$link_use = '<a href="' . base_url(PATH . 'v/' . $line['id_cc_use']) . '">';
				$name_use = ' <i>use</i> ' . $link_use . $x2 . '</a>';
			}

			if ($line['id_cc_use'] == 0) {
				$xl = substr(UpperCaseSql(strip_tags($name_use)), 0, 1);
				if ($xl != $l) {
					$sx .= '<h4>' . $xl . '</h4>';
					$l = $xl;
				}
				$name = $link . $name_use . $linka . ' <sup style="font-size: 70%;">(' . $line['n_lang'] . ')</sup>';
				$sx .= '<li>' . $name . '</li>' . cr();
			}
		}
		$sx .= '</div></div>';
		return ($sx);
	}	

	/******************************************************************* RDF NAME ***/
	function rdf_name($n = '', $lang = 'pt_BR', $new = 1) 
	{
		$rs = $this->frbr_name($n, $lang, $new);
		return($rs);
	}
	function frbr_name($n = '', $lang = 'pt_BR', $new = 1) {		
		$CI = &get_instance();
		if (is_array($n))
		{
			return(0);
		}
		$n = trim($n);
		if ((strlen($n) == 0) or ($n == '--')) { return(0); }
		$lang = trim($lang);
		$lang = troca($lang, '@', '');
		if (strlen($lang) > 5) { $lang = substr($lang, 0, 5);
		}
		$n = troca($n, "'", "´");
		$n = troca($n, "  ", " ");
		if (strlen($n) == 0) {
			return (0);
		}
		/***************************************************************** LANGUAGE */
		$lang = $this -> language($lang);
		$md5 = md5(trim($n));
		$dt['title'] = $n;

		/************ BUSCA NOMES **************************************/
		$sql = "select * from rdf_name where (n_name = '" . $n . "') or (n_md5 = '$md5')";
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$sqli = "insert into rdf_name (n_name, n_lang, n_md5) values ('$n','$lang','$md5')";
			$rlt = $CI -> db -> query($sqli);
			sleep(0.3);
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
		}
		$line = $rlt[0];
		return ($line['id_n']);
	}

	/**************************************************************************** SET PROPRIETY *****/
	function set_propriety($r1, $prop, $r2, $lit = 0) {
		if (($r1 == 0) or (($r2 == 0) and ($lit == 0))) { return(False); }
		$CI = &get_instance();
		$rdf = new rdf;
		/****************************** Literal ************/
		if ((strlen($lit) > 0) AND ($lit != sonumero($lit)))
		{
			$lit = $rdf->frbr_name($lit, 'pt_BR');
		}

		/********* propriedade com o prefixo ***************/
		if (strpos($prop, ':')) {
			$prop = substr($prop, strpos($prop, ':') + 1, strlen($prop));
		}
		/*********************** recupera propriedade ID ***/
		if (!(sonumero($prop) == $prop))
		{
			$pr = $this -> find_class($prop);	
		} else {
			$pr = $prop;
		}


		$sql = "select * from (
		select * from rdf_data 
		WHERE (d_p = $pr and d_literal = $lit) ) as table1
		where ((d_r1 = $r1 AND d_r2 = $r2)
		OR (d_r1 = $r2 AND d_r2 = $r1))";
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$sql = "insert into rdf_data
			(d_r1, d_p, d_r2, d_literal)
			values
			('$r1','$pr','$r2',$lit)";
			$rlt = $CI -> db -> query($sql);
		} else {

		}
		return (true);
	}

	function set_propriety_update($r1, $prop, $r2, $lit = 0) {
		if (($r1 == 0) or (($r2 == 0) and ($lit == 0))) { return(False); }
		$CI = &get_instance();
		$rdf = new rdf;
		/****************************** Literal ************/
		if ((strlen($lit) > 0) AND ($lit != sonumero($lit)))
		{
			$lit = $rdf->frbr_name($lit, 'pt_BR');
		}

		/********* propriedade com o prefixo ***************/
		if (strpos($prop, ':')) {
			$prop = substr($prop, strpos($prop, ':') + 1, strlen($prop));
		}
		/*********************** recupera propriedade ID ***/
		if (!(sonumero($prop) == $prop))
		{
			$pr = $this -> find_class($prop);	
		} else {
			$pr = $prop;
		}

		$sql = "select * from rdf_data
				WHERE (d_p = $pr ) and (d_r1 = $r1)";
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$sql = "insert into rdf_data
			(d_r1, d_p, d_r2, d_literal)
			values
			('$r1','$pr','$r2',$lit)";
			$rlt = $CI -> db -> query($sql);
		} else {
			$line = $rlt[0];
			if ($line['d_r2'] != $r2 or ($line['d_literal'] != $lit))
			{
				$sql = "update rdf_data set
				d_r2 = '$r2',
				d_literal = '$lit'
				where id_d = ".$rlt[0]['id_d'];
				$rlt = $CI -> db -> query($sql);					
			} else {

			}
		}
		return (true);
	}

	/*****************************************************************  RDF CONCEPT **/
	function rdf_concept_create($class, $term, $orign = '', $lang = 'pt_BR')
	{
		$CI = &get_instance();
		$cl = $this -> find_class($class);
		$term = $this -> frbr_name($term, $lang);
		if ($term == 0) { return(0); }

		$dt = date("Y/m/d H:i:s");
		$date = date("Y-m-d");
		/*********** checar se não existe um termo já iserido *********************/
		$sql = "select * from rdf_concept 
		WHERE 
		cc_class = $cl AND cc_pref_term = $term ";
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$sqli = "insert into rdf_concept
			(cc_class, cc_pref_term, cc_created, cc_origin, cc_update)
			VALUES
			($cl, $term,'$dt','$orign','$date')";
			$rlt = $CI -> db -> query($sqli);
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
		}

		/**************** pref Term ****************************************************/
		$line = $rlt[0];
		$r1 = $line['id_cc'];
		$this -> set_propriety($r1, 'prefLabel', 0, $term);
		return ($r1);
	}
	function language($lang) {
		switch($lang) {
			case 'por' :
			$lang = 'pt_BR';
			break;
			case 'pt-BR' :
			$lang = 'pt_BR';
			break;
			case 'eng' :
			$lang = 'en';
			break;
			case 'en-US' :
			$lang = 'en';
			break;
		}
		return ($lang);
	}        	

    ################################################## DATA
	function form_ajax($idc, $form,$idx) {
		$CI = &get_instance();
		$sx = '';

		$sql = "select * 
		from rdf_form_class 
		INNER JOIN rdf_class ON id_c = sc_range
		where id_sc = $form";
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();

		/* Tipo do Range */
		$type = $rlt[0]['c_class'];
		if (count($rlt) > 0) {
			$line = $rlt[0];
		}

		/******************* TIPOS DE FORMULÁRIOS ****************/
		$dt['type'] = $type;
		switch($type) {
			case 'ISBN' :
			break;

			/**********************************************/
			default :
			$dt['type'] = $type;
			$dt['label1'] = msg($type);
			$sx .= '<div class="container">';
			$sx .= '<div class="row">';
			$sx .= '<div class="col-12">';
			$sx .= '<h1>'.msg($type).'</h1>';
			$sx .= $this -> cas_ajax($type, $idc, $dt);
			$sx .= '
			<div class="text-right" style="margin-top: 20px;">
			<input type="hidden" id="dd52" value="'.$line['sc_propriety'].'">
			<button type="button" id="cancel" class="btn btn-outline-danger" data-dismiss="modal">'.msg('cancel').'</button>
			<button type="button" id="submt" class="btn btn-outline-primary" disabled>'.msg('save').'</button>
			</div>

			<script>
			$("#cancel").click(function() { wclose(); });

			$("#submt").click(function() 
			{ 
				var $vlr = $("#dd51").val();
				var $prop = $("#dd52").val();
				var $id = '.$idx.';
				$.ajax(
				{
					type: "POST",
					url: "' . base_url(PATH . 'rdf/save') . '",
					data: "concept="+$id+"&prop="+$prop+"&resource="+$vlr,
					success: function(data){
						$("#dd51a").html(data);
					}
				}
				); 
			}
			);


			</script>
			';
			$sx .= '</div>';
			$sx .= '</div>';
			$sx .= '</div>';
			break;
		}
		return ($sx);
	}



	/************************************* checa formulário de dados ***********/
	function form_check($class=0)
	{
		$CI = &get_instance();
		$sql = "SELECT * FROM (
		select d_p, id_c as c from rdf_data 
		INNER JOIN rdf_concept ON d_r1 = id_cc
		INNER JOIN rdf_class ON cc_class = id_c
		where id_c = $class
		group by d_p, id_c
		) as tabela
		LEFT JOIN rdf_form_class as t1 ON c = t1.sc_class and d_p = sc_propriety and ((sc_library = 0) or (sc_library = ".LIBRARY.") or (sc_global = 1))
		LEFT JOIN rdf_class as t2 ON sc_propriety = t2.id_c";
		$rlt = $CI -> db -> query($sql);
		$rlt = $rlt -> result_array();
		for ($r=0;$r < count($rlt);$r++)
		{
			$line = $rlt[$r];
			$exist = $line['sc_class'];
			if ($exist == '')
			{
				$prop = $line['d_p'];
				$sql = "insert into rdf_form_class
				(sc_class,sc_propriety,sc_range,sc_library, sc_global, sc_ativo)
				values
				($class,$prop,0,".LIBRARY.",0,1)";
				$xrlt = $CI -> db -> query($sql);							
			}
		}
	}

	/*********************************** editar dados ***************************/
	function form($id, $dt) {
		$CI = &get_instance();
		$class = $dt['cc_class'];

		$sx = '';
		$js1 = '';     

		/***** editar classe */		
		if ((isset($dt['action'])) and ($dt['action'] == 'class'))
		{
			$sx .= '<div class="col-md-12">';
			$form = new form;
			$form->id = $id;
			$cp = array();
			array_push($cp,array('$H8','id_cc','',false,false));
			$op = "select * from rdf_class where c_type = 'C'";
			array_push($cp,array('$Q id_c:c_class:'.$op,'cc_class',msg('Classe_name'),true,true));
			$sx .= $form->editar($cp,'rdf_concept');
			$sx .= '</div>';
			if ($form->saved > 0)
			{
				redirect(base_url(PATH.'a/'.$dt['id_cc']));
			}
		}

		/***** checar formulário da classe */		
		if ((isset($dt['action'])) and ($dt['action'] == 'check_form'))
		{
			$this->form_check($dt['cc_class']);
			redirect(base_url(PATH.'a/'.$dt['id_cc']));
		}

		/***** checar formulário da classe */		
		if ((isset($dt['action'])) and ($dt['action'] == 'form'))
		{
			//form_ed($id,$cl=0)

			$sx .= $this->form_ed($id,1);
			//redirect(base_url(PATH.'a/'.$dt['id_cc']));
		}				

		/* complementos */
		switch($class) {
			default :
			$cp = 'n_name, cpt.id_cc as idcc, d_p as prop, id_d, d_literal';
			$sqla = "select $cp from rdf_data as rdata
			INNER JOIN rdf_class as prop ON d_p = prop.id_c 
			INNER JOIN rdf_concept as cpt ON d_r2 = id_cc 
			INNER JOIN rdf_name on cc_pref_term = id_n
			WHERE d_r1 = $id and d_r2 > 0";
			$sqla .= ' union ';
			$sqla .= "select $cp from rdf_data as rdata
			LEFT JOIN rdf_class as prop ON d_p = prop.id_c 
			LEFT JOIN rdf_concept as cpt ON d_r2 = id_cc 
			LEFT JOIN rdf_name on d_literal = id_n
			WHERE d_r1 = $id and d_r2 = 0";
			/*****************/
			$sql = "select * from rdf_form_class
			INNER JOIN rdf_class as t0 ON id_c = sc_propriety
			LEFT JOIN (" . $sqla . ") as t1 ON id_c = prop 
			LEFT JOIN rdf_class as t2 ON sc_propriety = t2.id_c
			where sc_class = $class 
			order by sc_ord, id_sc, t0.c_order";

			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$sx .= '<table width="100%" cellpadding=5>';
			$js = '';
			$xcap = '';
			$xgrp = '';
			for ($r = 0; $r < count($rlt); $r++) {
				$line = $rlt[$r];
				$grp = $line['sc_group'];
				if ($xgrp != $grp)
				{
					$sx .= '<tr>';
					$sx .= '<td colspan=3 class="middle" style="border-top: 1px solid #000000; border-bottom: 1px solid #000000;" align="center">';
					$sx .= msg($grp);
					$sx .= '</td>';
					$sx .= '</tr>';
					$xgrp = $grp;
				}


				$cap = msg($line['c_class']);

				/************************************************************** LINKS EDICAO */
				$idc = $id; /* ID do conceito */
				$form_id = $line['id_sc']; /* ID do formulário */
				/* $class =>  ID da classe */

				$furl = base_url(PATH.'rdf/form/'.$class.'/'.$line['id_sc'].'/'.$id);

				$link = '<a href="#" id="action_' . trim($line['c_class']) . '" 
				onclick="newxy(\''.$furl.'\',800,400);">';
				$linka = '</a>';
				$sx .= '<tr>';
				$sx .= '<td width="25%" align="right" valign="top">';

				if ($xcap != $cap) {
					$sx .= '<nobr><i>' . msg($line['c_class']) . '</i></nobr>';
					$sx .= '<td width="1%" valign="top">' . $link . '[+]' . $linka . '</td>';
					$xcap = $cap;
				} else {
					$sx .= '&nbsp;';
					$sx .= '<td>-</td>';
				}
				$sx .= '</td>';

				/***************** Editar campo *******************************************/
				$sx .= '<td style="border-bottom: 1px solid #808080;">';
				if (strlen($line['n_name']) > 0) {
					$linkc = '<a href="' . base_url(PATH . 'v/' . $line['idcc']) . '" class="middle">';
					$linkca = '</a>';
					if (strlen($line['idcc']) == 0)
					{
						$linkc = '';
						$linkca = '';
					}

					$sx .= $linkc . $line['n_name'] . $linkca;


					/********************** Editar caso texto */

					if (strlen($line['idcc']) == 0)
					{
						$onclick = ' onclick="newxy(\''.base_url(PATH.'rdf/text/'.$line['d_literal']).'\',600,400);"';
						$elink = ' <span style="cursor: pointer;" '.$onclick.'>';
						$elinka = '';
						$sx .= $elink . '<font style="color: red;" title="Editar texto">[ed]</font>' . $elinka;
					}

					/********************* Excluir lancamento */
					$onclick = ' onclick="newxy(\''.base_url(PATH.'rdf/exclude/'.$line['id_d']).'\',600,200);"';
					$link = ' <span style="cursor: pointer;" '.$onclick.'>';
					$sx .= $link . '<font style="color: red;" title="Excluir lancamento">[X]</font>' . $linka;
					$sx .= '</span>';
				}

				$sx .= '</td>';
				$sx .= '</tr>';				
			}
			$sx .= '</table>';
			break;
		}		
		return ($sx);
	}

	function ajax($id = '', $id2 = '', $id3 = '', $id4 = '') {
		$CI = &get_instance();
		$q = get("q");

		switch($id) {
			case 'inport' :
			$cl = $this -> le_class($id2);
			if (count($cl) == 0) {
				echo "Erro de classe [$id2]";
				exit ;
			}
			$url = trim($cl['c_url']);
			echo '-->'.$url;
			$t = read_link($url);
			$this -> frbr_core -> inport_rdf($t, $id2);
			$sql = "update rdf_class set c_url_update = '" . date("Y-m-d") . "' where id_c = " . $cl['id_c'];
			$rlt = $this -> db -> query($sql);
			$sx = '';
			break;



			default :
			if (strlen($q) > 0) {
				echo $this -> searchs -> ajax_q($q);
			} else {
                    //$type = $id2;
				echo '==>'.$id.'==>'.$id2.'==>'.$id3;
				$id_cc = $id; /* Classe */
				$f_id = $id2; /* Formulário */
				$id_c = $id3; /* Conceito */
				echo $this -> model($id_c, $f_id);
			}
			break;
		}
	}
	/**************************************** MODEL *****************/
	function xxModel($path = '', $id = 0, $dt = '') {
		$sx = '
		<div class="modal-header" >                    
		<h4 class="modal-title" id="myModalLabel">Modal - ' . $path . '</h4>
		<button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		</div>
		<div class="modal-body">';

		$sx .= $this -> form_ajax($path, $id, $dt);
		$sx .= '
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-warning" style="display: none;" id="save">Incluir</button>
		<button type="button" class="btn btn-primary" id="submt" disabled>Salvar</button>
		</div>                  
		';
		return ($sx);
	}

	function cas_include($id, $id2, $id3)
	{
		$dt = $this->le_class($id2);
		$class = trim($dt['c_class']);
		$sx = '<h4 style="margin-top: 20px;">'.msg($class).'</h4>';
		{
			/*********** direciona formulário ***/
			switch($class)
			{
				case 'prefLabel':
				if ($this->exist_prefLabel($id))
				{
					$sx .= message("Já existe um nome preferencial para este termo",5);	
					$sx .= '<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					</div>                  
					';
				} else {
					$this->update_prefLabel($id);
					$sx .= $this -> cas_text($id, $id2);	

				}			

				break;
				/**************** Default **********/
				default:
				$sx .= $this -> cas_ajax($id2, $id3);
				exit;
				break;
			}
		}
		return($sx);
	}

	function cas_exclude($id) {

		$dt = $this->le_dados($id);
		if (strlen($dt['n_name']) > 0)
		{
			echo '<h4>'.$dt['n_name'].'</h4>';
		}
		$sx = '<center><h1>'.msg('rdf_exclude_confirm').'</h1></center>';

		$sx .= '
		</div>		
		<div class="modal-footer">
		<button type="button" class="btn btn-default" onclick="wclose();" data-dismiss="modal">Cancelar</button>
		<a href="'.base_url(PATH.'rdf/exclude/'.$id.'/confirm').'" class="btn btn-warning" id="submt">Excluir</a>
		</div>                  
		';
		/**************** fim ******************/
		return ($sx);
	}	

	function cas_text($id, $prop, $dt = array()) {
		if (!isset($dt['label1'])) 
		{ 
			$dt['label1'] = msg('name');
		}

		$sx = '';
		$sx .= '<span style="font-size: 75%">'.msg('form_text').'</span><br>'.cr();
		$sx .= '<textarea col=80 row=3 id="dd51" name="dd51" class="form-control">'.cr();
		$sx .= '</textarea>'.cr();
		$sx .= '<input type="hidden" name="dd55" id="dd55" value="'.$prop.'">'.cr();
		$sx .= '<input type="hidden" name="dd54" id="dd54" value="'.$id.'">'.cr();
		$sx .= '<input type="hidden" name="dd53" id="dd53" value="text">'.cr();
		$sx .= '<br/>';
		$sx .= '
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-warning" style="display: none;" id="save">Incluir</button>
		<button type="button" class="btn btn-primary" id="submt">Salvar</button>
		</div>                  
		';		
		$sx .= '<div id="dd51a"></div>';
		$sx .= '<script>'.cr();
		$sx .= '	
		/************ submit ***************/
		jQuery("#submt").click(function() 
		{
			var $key = jQuery("#dd51").val();
			var $key2 = jQuery("#dd54").val();
			var $key3 = jQuery("#dd55").val();
			var $type = jQuery("#dd53").val();
			$.ajax(
			{
				type: "POST",
				url: "' . base_url(PATH . 'config/class/ajax_save/'.$id.'?nocab=t') . '",
				data: "q="+$key+"&dd10="+$key2+"&dd11="+$key3+"&type="+$type,
				success: function(data){
					$("#dd51a").html(data);
				}
			}
			);                           
		}
		);
		</script>';

		/**************** fim ******************/
		return ($sx);
	}		

	function cas_ajax($path, $id, $dt = array()) 
	{
		if (!isset($dt['label1'])) 
		{ 
			$dt['label1'] = msg('name');
		}

		/* */
		$type = '';
		if (isset($dt['type'])) {
			$type = $dt['type'];
		}
		$sx = '';
		$sx .= '<span style="font-size: 75%">filtro do [' . $dt['label1'] . ']</span><br>';
		$sx .= '<input type="text" id="dd50" name="dd50" class="form-control">'.cr();
		$sx .= '<span style="font-size: 75%">selecione o [' . $dt['label1'] . ']</span><br>'.cr();
		$sx .= '<div id="dd51a"><select class="form-control" size=5 name="dd51" id="dd51"></select></div>'.cr();
		$sx .= '<script>'.cr();
		$sx .= '
		/************ keyup *****************/
		jQuery("#dd50").keyup(function() 
		{
			var $key = jQuery("#dd50").val();
			$.ajax(
			{
				type: "POST",
				url: "' . base_url(PATH . 'rdf/search/' . $type . '/' . $id.'?nocab=T') . '",
				data:"q="+$key,
				success: function(data){
					$("#dd51a").html(data);
				}
			}
			);
		});';
		$sx .= '	
		/************ submit ***************/
		jQuery("#submt").click(function() 
		{
			var $key = jQuery("#dd51").val();
			$.ajax(
			{
				type: "POST",
				url: "' . base_url(PATH . 'rdf/save/' . $path . '/' . $id) . '",
				data: "q="+$key,
				success: function(data){
					$("#dd51a").html(data);
				}
				});                           
			}
			);
			</script>';

			/**************** fim ******************/
			return ($sx);
		}

		function view_data($id) {
			$CI = &get_instance();

			$data = $this -> le_data($id);
			$sx = '<table class="table">';
			$sx .= '<tr>';
			$sx .= '<th width="20%" style="text-align: right;">' . msg('propriety') . '</th>';
			$sx .= '<th width="80%">' . msg('value') . '</th>';
			$sx .= '</tr>';
			for ($r = 0; $r < count($data); $r++) {
				$line = $data[$r];
				$line['id'] = $id;
				$link = '';
				if ($line['d_r2'] > 0) {
					$link = '<a href="' . base_url(PATH . 'v/' . $line['d_r2']) . '">';
					if ($line['d_r2'] == $id) {
						$link = '<a href="' . base_url(PATH . 'v/' . $line['d_r1']) . '">';
					}
				}
				$sx .= '<tr>';
				$sx .= '<td align="right" valign="top">';
				$sx .= '<i>' . msg(trim($line['c_class'])) . '</i>';
				$sx .= '</td>';
				$sx .= '<td>';
				/********* INVERT ********/
				if (($line['d_r1'] == $id) and ($line['d_r2'] != 0)) {
					$idv = $line['d_r2'];
					$line['d_r2'] = $line['d_r1'];
					$line['d_r1'] = $idv;
				}
				$sx .= $this -> mostra_dados($line['n_name'], $link, $line);
				$sx .= ' <sup>(' . $line['n_lang'] . ')</sup>';
				$sx .= ' <sup>' . $line['rule'] . '</sup>';
				$sx .= '</td>';
				$sx .= '</tr>';
			}
			$sx .= '</table>';
			return ($sx);
		}

		function mostra_dados($n, $l = '', $line) 
		{
			$la = '';
			$idx = $line['d_r2'];
			if ($idx == $line['id']) {
				$idx = $line['d_r1'];
			}


			if (strlen($l) > 0) {
				$la = '</a>';
			}

			return ($l . $n . $la);
		}

		function search($d) 
		{
			if (!isset($d['dd1'])) 
			{
				return ('');
			}
			$dd1 = $d['dd1'];
			$dd1 = troca($dd1, ' ', ';') . ';';
			$dd1 = troca($dd1, "'", "´");
			$lns = splitx(';', $dd1);
			$sx = '';
			$wh = '';
			for ($r = 0; $r < count($lns); $r++) 
			{
				if (strlen($wh) > 0) 
				{ 
					$wh .= ' AND ';
				}
				$wh .= " (n_name like '%" . $lns[$r] . "%')";
			}
			if (strlen($wh) == 0) 
			{
				return ('');
			}
			$cps = 'c_class, id_c, n_name, id_cc';
			$sql = "select $cps from rdf_concept
			INNER JOIN rdf_name ON id_n = cc_pref_term 
			INNER JOIN rdf_class ON id_c = cc_class
			WHERE $wh AND c_find = 1  AND cc_library = " . LIBRARY . "
			group by $cps";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$sx .= '<div class="container">' . cr();
			$sx .= '<div class="row">' . cr();
			for ($r = 0; $r < count($rlt); $r++) 
			{
				$line = $rlt[$r];
				$class = $line['c_class'];
				$classC = $line['c_class'];
				if (substr($class,0,strlen('Tesauro')) == 'Tesauro')
				{
					$classC = 'Tesauro';
				}
				switch ($classC) {
					case 'CDU' :
					$idw = $line['id_cc'];
					$img = $this -> recupera_imagem($idw, 'img/icon/icone_cdu.jpg');
					$sx .= '<div class="col-lg-2 col-md-4 col-xs-3 col-sm-6 text-center" style="line-height: 80%; margin-top: 40px;">' . cr();
					$sx .= $this -> show_type($line, 'UDC', $img) . cr();
					$sx .= '</div>' . cr();
					break;

					case 'Corporate Body' :
					$idw = $line['id_cc'];
					$img = $this -> recupera_imagem($idw, 'img/icon/icone_build.jpg');
					$sx .= '<div class="col-lg-2 col-md-4 col-xs-3 col-sm-6 text-center" style="line-height: 80%; margin-top: 40px;">' . cr();
					$sx .= $this -> show_corporate($line) . cr();
					$sx .= '</div>' . cr();
					break;

					case 'SerieName' :
					$idw = $line['id_cc'];
					$img = $this -> recupera_imagem($idw);
					$sx .= '<div class="col-lg-2 col-md-4 col-xs-3 col-sm-6 text-center" style="line-height: 80%; margin-top: 40px;">' . cr();
					$sx .= $this -> show_seriename($line) . cr();
					$sx .= '</div>' . cr();
					break;

					case 'Tesauro' :
					$idw = $line['id_cc'];
				//$link = '<a href="' . base_url(PATH . 'v/' . $line['id_c']) . '" target="_new">';
					$link = '<a href="' . base_url(PATH . 'v/' . $idw) . '" target="_new">';
					$sx .= '<div class="col-lg-2 col-md-4 col-xs-3 col-sm-6 text-center" style="line-height: 80%; margin-top: 40px;">' . cr();
					$img = $this -> recupera_imagem($idw);                    
					$sx .= $link;
					$sx .= $img;
					$sx .= $line['n_name'];
					$sx .= '</a><br><sup>';
					$sx .= msg($line['c_class']);
					$sx .= '</sup>';
					$sx .= '</div>' . cr();
					break;

					case 'Work' :
					$idw = $line['id_cc'];
					$sx .= '<div class="col-lg-2 col-md-4 col-xs-3 col-sm-6 text-center" style="line-height: 80%; margin-top: 40px;">' . cr();
					$sx .= $this -> show_manifestation_by_works($idw) . cr();
					$sx .= '</div>' . cr();
					break;

					case 'BookChapter' :
					$idw = $line['id_cc'];
					$sx .= '<div class="col-lg-2 col-md-4 col-xs-3 col-sm-6 text-center" style="line-height: 80%; margin-top: 40px;">' . cr();
					$sx .= $this -> show_chapter($line) . cr();
					$sx .= '</div>' . cr();
					break;

					case 'Person' :
					$idw = $line['id_cc'];
					$img = $this -> recupera_imagem($idw);
					$sx .= '<div class="col-lg-2 col-md-4 col-xs-3 col-sm-6 text-center" style="line-height: 80%; margin-top: 40px;">' . cr();
					$sx .= $this -> show_person($line) . cr();
					$sx .= '</div>' . cr();
					break;

					case 'Item' :
					$idw = $line['id_cc'];
					$sx .= '<div class="col-lg-2 col-md-4 col-xs-3 col-sm-6 text-center" style="line-height: 80%; margin-top: 40px;">' . cr();
					$sx .= $this -> show_manifestation_by_item($idw) . cr();
					$sx .= '<br><br><span style="font-size: 12px;">Tombo:' . $line['n_name'] . '</span>';
					$sx .= '</div>' . cr();
					break;

					default :
					$idw = $line['id_cc'];
                    //$link = '<a href="' . base_url(PATH . 'v/' . $line['id_c']) . '" target="_new">';
					$link = '<a href="' . base_url(PATH . 'v/' . $idw) . '" target="_new">';
					$sx .= '<div class="col-lg-2 col-md-4 col-xs-3 col-sm-6 text-center" style="line-height: 80%; margin-top: 40px;">' . cr();
                    //$sx .= '<h1>[' . $class . ']</h1>';
					$sx .= $link;
					$sx .= $line['n_name'];
					$sx .= '</a>';
					$sx .= ' (';
					$sx .= msg($line['c_class']);
					$sx .= ')' . cr();
					$sx .= '</div>' . cr();
					break;
				}
			}
			$sx .= '</div>';
			$sx .= '</div>';
			return ($sx);
		}


		/***************** Gerador de ìndices **************/
		function index_work($lt = '') {
			$CI = &get_instance();
			$class = "Work";
			$f = $this -> find_class($class);

			$sql = "select * from rdf_concept 
			INNER JOIN rdf_name ON cc_pref_term = id_n
			where cc_class = " . $f . " AND cc_library = " . LIBRARY . "
			ORDER BY n_name";
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$sx = '<ul>';
			$l = '';
			for ($r = 0; $r < count($rlt); $r++) {
				$line = $rlt[$r];
				$xl = substr(LowerCaseSql($line['n_name']), 0, 1);
				if ($xl != $l) {
					$sx .= '<h4>' . UpperCaseSql($xl) . '</h4>';
					$l = $xl;
				}
				$link = '<a href="' . base_url(PATH . 'v/' . $line['id_cc']) . '">';
				$name = $link . $line['n_name'] . '</a>';
				$sx .= '<li>' . $name . '</li>' . cr();
			}
			return ($sx);
		}


		function index_author($lt = '') {
			$CI = &get_instance();
			$class = "Person";
			$f = $this -> find_class($class);

			$sql = "select * from rdf_concept 
			INNER JOIN rdf_name ON cc_pref_term = id_n
			where cc_class = " . $f . " AND cc_library = " . LIBRARY . "
			ORDER BY n_name";
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$sx = '<ul>';
			$l = '';
			for ($r = 0; $r < count($rlt); $r++) {
				$line = $rlt[$r];
				$xl = substr($line['n_name'], 0, 1);
				if ($xl != $l) {
					$sx .= '<h4>' . $xl . '</h4>';
					$l = $xl;
				}
				$link = '<a href="' . base_url(PATH . 'v/' . $line['id_cc']) . '">';
				$name = $link . $line['n_name'] . '</a>';
				$sx .= '<li>' . $name . '</li>' . cr();
			}
			$sx .= '<ul>';
			return ($sx);
		}

		function index_other($lt = '', $class = 'isPublisher') {
			$CI = &get_instance();
			$f = $this -> find_class($class);

			$sql = "select d_r2, n_name, id_cc from rdf_data
			LEFT JOIN rdf_concept on d_r2 = id_cc  
			LEFT JOIN rdf_name ON cc_pref_term = id_n
			where d_P = " . $f . " AND cc_library = " . LIBRARY . "
			GROUP BY d_r2, n_name, id_cc
			ORDER BY n_name";
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$sx = '<ul>';
			$l = '';
			for ($r = 0; $r < count($rlt); $r++) {
				$line = $rlt[$r];
				$xl = substr($line['n_name'], 0, 1);
				if ($xl != $l) {
					$sx .= '<h4>' . $xl . '</h4>';
					$l = $xl;
				}
				$link = '<a href="' . base_url(PATH . 'v/' . $line['id_cc']) . '">';
				$name = $link . $line['n_name'] . '</a>';
				$sx .= '<li>' . $name . '</li>' . cr();
			}
			$sx .= '<ul>';
			return ($sx);
		}

		function tools($tools,$ac,$id)
		{
			$sx = '';
			switch($tools) {
				case 'class' :
				$sx .= $rdf -> classes_lista();
				break;
				case 'class-ed' :
				$sx .= $rdf -> classes_ed($id);
				break;            
			}
			return($sx);			

		}

		/************************************************************************** CREATE C */
		function export_c($id)
		{
			$dt = $this->le($id);
			$class = $dt['c_class'];
			$file = 'name.nm';
			$txt = '';

			switch($class)
			{
				/********************************************** ENDERECO ******************/
				case 'Address':
				$dts = $this->le_data($id);
				$cep = $this->recupera($dts,'hasAddressCep');
				if (strlen($cep) > 0) { $cep = substr($cep,0,2).'.'.substr($cep,2,3).'-'.substr($cep,5,3); }
				$cidade = $this->recupera($dts,'hasAddressCity');
				$ativo = $this->recupera($dts,'isAddressActive');
				$tipo = $this->recupera($dts,'isAddressType');
				$bairro = $this->recupera($dts,'isNeighborhood');
				$rua = $this->recupera($dts,'isStreet');
				$ruanr = $this->recupera($dts,'isStreetNumber');
				$work = $this->recupera($dts,'workCorporateBody');

				$txt = '<i>'.$tipo.'</i>';
				if (strlen($work) > 0)
				{
					$txt .= '<br/><b>'.$work.'</b>';
				}
				$txt .= '<br>'.$rua.' '.$ruanr.'<br>'.$bairro.' - '.$cidade;
				if (strlen($cep) > 0)
					{ $txt .= '<br>CEP: '.$cep; }

				if (($ativo != 'SIM') and ($ativo != 'YES'))
				{
					$txt = '<s>'.$txt.'</s>';
				}		
				break;
			}
			/******************************************** SALVA ARQUIVOS *********************/
			if (strlen($txt) > 0)
			{
				$dir = 'c';
				check_dir($dir);
				$dir = 'c/'.$id.'/';
				check_dir($dir);

				if (strlen($file) > 0) { file_put_contents ($dir.$file,$txt) ;}
			}
			return(1);
		}

		function recupera($dt,$prop)
		{
			$vlr = '';
			for ($r=0;$r < count($dt);$r++)
			{
				$line = $dt[$r];
				if ($line['c_class'] == $prop)
				{
					$vlr = $line['n_name'];
				}
			}
			return($vlr);
		}

		function class_view_form($id='')
		{
			$CI = &get_instance();
			$sql = "select id_sc, sc_class, sc_propriety, sc_ord, id_sc,
			t1.c_class as c_class, t2.prefix_ref as prefix_ref,
			t3.c_class as pc_class, t4.prefix_ref as pc_prefix_ref,
			sc_group, sc_library
			FROM rdf_form_class
			INNER JOIN rdf_class as t1 ON t1.id_c = sc_propriety
			LEFT JOIN rdf_prefix as t2 ON t1.c_prefix = t2.id_prefix

			LEFT JOIN rdf_class as t3 ON t3.id_c = sc_range
			LEFT JOIN rdf_prefix as t4 ON t3.c_prefix = t4.id_prefix

			where sc_class = $id
			AND ((sc_global =1) or (sc_library = 0) or (sc_library = ".LIBRARY."))
			order by sc_ord";

			echo $sql;
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();	
			$sx = '<div class="col-md-12">';
			$sx .= '<h4>'.msg("Form").'</h4>';
			$sx .= '<table class="table">';
			$sx .= '<tr><th width="4%">#</th>';
			$sx .= '<th width="47%">'.msg('propriety').'</th>';
			$sx .= '<th width="42%">'.msg('range').'</th>';
			$sx .= '<th width="5%">'.msg('group').'</th>';
			$sx .= '</tr>';
			for ($r=0;$r < count($rlt);$r++)			
			{
				$line = $rlt[$r];
				$link = '<a href="#" onclick="newxy(\''.base_url(PATH.'config/class/formss/'.$line['sc_class'].'/'.$line['id_sc']).'\',800,600);">';
				$linka = '</a>';
				$sx .= '<tr>';

				$sx .= '<td align="center">';
				$sx .= $line['sc_ord'];
				$sx .= '</td>';

				/* CLASS */
				$prop = $this->prefixn($line);
				$sx .= '<td>';	
				$sx .= $link;			
				$sx .= msg($line['c_class']).' ('.$prop.')';
				$sx .= $linka;
				$sx .= '</td>';

				/* RANGE */
				$dt = array();
				$dt['c_class'] = $line['pc_class'];
				$dt['prefix_ref'] = $line['pc_prefix_ref'];
				$sx .= '<td>';
				$sx .= $this->prefixn($dt);
				$sx .= '</td>';

				/* GROUP */
				$dt = array();
				$sx .= '<td>';
				$sx .= $line['sc_group'];
				$sx .= '</td>';

				$sx .= '</tr>';
			}
			$sx .= '</table>';
			$sx .= '</div>';

			$link = '<a href="#" onclick="newxy(\''.base_url(PATH.'config/class/formss/'.$id.'/0').'\',800,600);">';
			$linka = '</a>';
			$sx .= $link.'novo'.$linka;

			return($sx);
		}
		function class_view_data($id = '') {
			$CI = &get_instance();
			$rdf = new rdf;
			$sx = '';
			$sx .= '<div class="col-md-6">';
			$sx .= '<h4>Dados</h4>';
			/********************************************/
			if (strlen($id) == 0) {
				$sql = "select * from rdf_class 
				WHERE c_type = 'C' and (c_vc = 1 or c_vc <> 1) 
				ORDER BY c_class ";
				$rlt = $CI -> db -> query($sql);
				$rlt = $rlt -> result_array();
				$sx .= '<ul>';
				for ($r = 0; $r < count($rlt); $r++) {
					$line = $rlt[$r];
					$link = '<a href="' . base_url(PATH.'vocabulary/' . $line['c_class']) . '">';
					$linka = '</a>';
					$sx .= '<li>' . $link . msg($line['c_class']) . $linka . '</li>';
				}
				$sx .= '</ul>';
			} else {
				$ln = $rdf -> data_classes($id);
				$sx .= '<ul>';
				for ($r = 0; $r < count($ln); $r++) {
					$l = $ln[$r];
					$link = '<a href="' . base_url(PATH.'v/' . $l['id_cc']) . '">';
					$linka = '</a>';
					$sx .= '<li>' . $link . $l['n_name'] . $linka . '</li>';
				}
				$sx .= '</ul>';
			}
			$sx .= '</div>';
			return ($sx);
		}

		function data_classes($d) {
			$CI = &get_instance();
			if (sonumero($d) == $d)
			{
				$id = $d;
			} else {
				$id = $this -> find_class($d);	
			}

			$sql = "select * from rdf_concept 
			INNER JOIN rdf_name ON cc_pref_term = id_N
			WHERE cc_class = $id
			ORDER BY n_name ";
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
			return ($rlt);
		}	
		function classes_lista() {
			$CI = &get_instance();
			/**************** class *************************/
			$sql = "select * from rdf_class where c_type = 'C' order by c_type, c_class";
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$sx = '';
			$tp = '';
			$lg = array('C' => 'Classe', 'P' => 'Propriedade');
			$sx .= '<div class="row">';
			$sx .= '<div class="col-md-1">';
			$sx .= '<b>' . $lg['C'] . '</b>';
			$sx .= '</div>';

			$sx .= '<div class="col-md-5">';
			for ($r = 0; $r < count($rlt); $r++) {
				$line = $rlt[$r];
				$link = '<a href="' . base_url(PATH . 'vocabulary_ed/' . $line['id_c']) . '">';

				$sx .= msg($line['c_class']);
				$sx .= ' (' . $link . $line['c_class'] . '</a>' . ')';
				$sx .= '<br>';
			}
			$sx .= '</div>';

			/**************** propriety **********************/
			$sql = "select * from rdf_class where c_type = 'P' order by c_type, c_class";
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
			$sx .= '<div class="col-md-1">';
			$sx .= '<b>' . $lg['P'] . '</b>';
			$sx .= '</div>';

			$sx .= '<div class="col-md-5">';
			for ($r = 0; $r < count($rlt); $r++) {
				$line = $rlt[$r];
				$xtp = $line['c_type'];
				$link = '<a href="' . base_url(PATH . 'vocabulary_ed/' . $line['id_c']) . '">';
				$sx .= msg($line['c_class']);
				$sx .= ' (' . $link . $line['c_class'] . '</a>' . ')';
				$sx .= '<br>';
			}
			$sx .= '</div>';
			$sx .= '</div>';
			return ($sx);
		}
		function class_update_data($dta)
		{	
			$CI = &get_instance();	
			$sx = '';	
			$id = $dta['id_c'];
			if (isset($dta['c_url']) and (strlen($dta['c_url']) > 10)) 
			{
				$sx = '
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
				' . msg('update_vocabulary') . '
				</button>

				<!-- Modal -->
				<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
				<div class="modal-dialog" role="document">
				<div class="modal-content">
				<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Importação de Vocabulários</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>

				<div class="modal-body" id="cnt">
				<span style="font-size:75%">Aguardando comando!</span>                                                            
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
				<input type="submit" id="dd50" class="btn btn-primary" value="Atualizar">
				</div>
				</div>
				</div>
				</div>';
				$sx .= '
				<script>
				jQuery("#dd50").click(function() 
				{ jQuery("#cnt").html("Buscando...");
				$.ajax(
				{ method: "POST", url: "' . base_url(PATH . 'config/class/ajax_update/' . $id.'?nocab=true') . '", data: { name: "John", location: "Boston" }}) .done(function( msg ) {	jQuery("#cnt").html(msg);}); }
				);
				</script>'; 
			}
			return($sx);
		}		
		function ajax_update($id)
		{
			$CI = &get_instance();
			$cl = $this -> le_class($id);
			if (count($cl) == 0) {
				echo "Erro de classe [$id]";
				exit ;
			}
			$url = trim($cl['c_url']);
			$t = read_link($url);
			$class = $cl['c_class'];
			$this -> inport_rdf($t, $class);
			$sql = "update rdf_class set c_url_update = '" . date("Y-m-d") . "' where id_c = " . $cl['id_c'];
			$rlt = $CI -> db -> query($sql);
			$sx = '';            		
		}
		function inport_rdf($t, $class = '') {
			if (strlen($class) == 0) {
				echo 'Classe não definida na importação';
				retur('');
			}
			$ln = $t;
			$ln = troca($ln, ';', ':.');
			$ln = troca($ln, chr(13), ';');
			$ln = troca($ln, chr(10), ';');
			$lns = splitx(';', $ln);
			for ($r = 0; $r < count($lns); $r++) {
				$ln = $lns[$r];
				$ln = troca($ln, chr(9), ';');

				$l = splitx(';', $ln);
				if (count($l) == 3) {
					$prop = $l[1];
					$term = $l[2];
					$resource = $l[0];
					if ($prop == 'skosxl:is_synonymous') {
						$prop = 'skos:altLabel';
					}
					if ($prop == 'skosxl:literalForm') {
						$prop = 'skos:altLabel';
					}
					if ($prop == 'skosxl:isSingular') {
						$prop = 'skos:altLabel';
					}
					switch($prop) {
						case 'skos:prefLabel' :
						$item = $this -> frbr_name($term);
						$p_id = $this -> rdf_concept($item, $class, $resource);
						$this -> set_propriety($p_id, $prop, 0, $item);
						break;
						default :
						$item = $this -> frbr_name($term);
						$p_id = $this -> rdf_concept_find_id($resource);
						if ($p_id > 0) {
							$this -> set_propriety($p_id, $prop, 0, $item);
						}
						break;
					}
				}
			}
			echo '<span style="color: #0000ff">Fim da importação</span>';
		}            	
		function rdf_concept_find_id($r) {
			$CI = &get_instance();
			$id = 0;
			$sql = "select * from rdf_concept where cc_origin = '$r'";
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
			if (count($rlt) > 0) {
				$line = $rlt[0];
				return ($line['id_cc']);
			}
			return ($id);
		}

		/*************************************** Exclude RDF Data *******************/
		function data_exclude($id) {
			$CI = &get_instance();
			$sql = "select * from rdf_data where id_d = " . $id;
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();
			if (count($rlt) > 0) {
				$line = $rlt[0];
				if ($line['d_r1'] > 0) {
					$sql = "update rdf_data set
					d_r1 = " . ((-1) * $line['d_r1']) . " ,
					d_r2 = " . ((-1) * $line['d_r2']) . " ,
					d_p  = " . ((-1) * $line['d_p']) . " 
					where id_d = " . $line['id_d'];
					$rlt = $CI -> db -> query($sql);
				}
			}
		}	

		function exist_prefLabel($id)
		{
			$CI = &get_instance();
			$prop = $this->find_class('prefLabel');

			$sql = "select * from rdf_data where d_r1 = ".$id." and d_p = ".$prop;
			$rlt = $CI->db->query($sql);
			$rlt = $rlt->result_array();
			if (count($rlt) > 0)
			{
				return(1);
			}
			return(0);	
		}

		function update_prefLabel($id)
		{
			$CI = &get_instance();
			$prop = $this->find_class('prefLabel');

			$sql = "select * from rdf_data where d_r1 = ".$id." and d_p = ".$prop;
			$rlt = $CI->db->query($sql);
			$rlt = $rlt->result_array();
			if (count($rlt) > 0)
			{
				$line = $rlt[0];
				$term = $line['d_literal'];
				print($line);
				exit;
			}
			return(0);	
		}	

		function ajax_search($id, $type = '') {

			$CI = &get_instance();
			$vlr = get("q");
			$wh = '';
			$wh2 = '';
			$sx = 'Busca: "'.$vlr.'"" em '.$type. ' ['.$id.']';
			$sx .= '<select name="dd51" id="dd51" size=5 class="form-control" onchange="change();">' . cr();

			/****************************** Busca ************/
			if (strlen($vlr) < 1) {
				$sx .= '<option></option>' . cr();
			} else {
				$vlr = troca($vlr, ' ', ';');
				$v = splitx(';', $vlr);
				for ($r = 0; $r < count($v); $r++) {
					if ($r > 0) {
						$wh .= ' and ';
					}
					$wh .= "(n_name like '%" . $v[$r] . "%') ";
				}
			}

			/* RANGE **********************************************/
			if (strlen($type) > 0) {				
				$ww = $this -> find_class($type,0);
				$wh2 = ' (cc_class = ' . $ww . ') ';

				$sql = "select * FROM rdf_class
				WHERE c_class_main = $ww";
				$rlt = $CI -> db -> query($sql);
				$rlt = $rlt -> result_array();
				for ($r = 0; $r < count($rlt); $r++) {
					$line = $rlt[$r];
					$wh2 .= ' OR (cc_class = ' . $line['id_c'] . ') ';
				}
				$wh2 = ' AND (' . $wh2 . ')';
			} else {
				$wh2 = '';
			}

			/***********************************************************************/
			if (strlen($wh) > 0) {
				$sql = "select * from rdf_name
				INNER JOIN rdf_data ON id_n = d_literal
				INNER JOIN rdf_concept ON d_r1 = id_cc
				INNER JOIN rdf_class ON id_c = d_p 
				WHERE ($wh) and (n_name <> '') $wh2 
				LIMIT 50";
				$rlt = $CI -> db -> query($sql);
				$rlt = $rlt -> result_array();

				for ($r = 0; $r < count($rlt); $r++) {
					$line = $rlt[$r];
					$sx .= '<option value="' . $line['id_cc'] . '">' . $line['n_name'] . '</option>' . cr();
				}
			}

			$sx .= '</select>' . cr();
			$sx .= '  <script>                 
			function change()
			{
				jQuery("#submt").removeAttr("disabled");
			}

			jQuery("#submt").attr("disabled","disabled");
			</script>';
			return ($sx);
		}		

		function btn_editar($id) {
			$sx = '<a href="' . base_url(PATH . 'a/' . $id) . '" class="btn btn-secondary">editar</a>';
			$sx .= ' <a href="' . base_url(PATH . 'authority_cutter/' . $id) . '" class="btn btn-secondary">atualizar Cutter</a>';
			return ($sx);
		}

		function btn_update($id) {
			$sx = '<a href="' . base_url(PATH . 'authority_inport_rdf/' . $id) . '" class="btn btn-secondary">atualizar dados</a> ';

			return ($sx);
		}	
		function person_work($id) {
			$CI = &get_instance();
			$r = array();
			$sql = "select d_r1, d_p, d_r2 from rdf_data 
			where (d_r1 = $id or d_r2 = $id)
			AND NOT (d_r1 = 0 OR d_r2 = 0)
			ORDER BY d_r1, d_p, d_r2";
			$rlt = $CI -> db -> query($sql);
			$rlt = $rlt -> result_array();

			$wk = array();
			$ww = array();
			for ($r = 0; $r < count($rlt); $r++) {
				$line = $rlt[$r];
				$p = $line['d_p'];
				$r1 = $line['d_r1'];
				if ($r1 != $id) {
					if (!isset($ww[$r1])) {
						array_push($wk, $r1);
					}
				}
				$r1 = $line['d_r2'];
				if ($r1 != $id) {
					if (!isset($ww[$r1])) {
						array_push($wk, $r1);
					}
				}
			}
			return ($wk);
		}
		function show_class($wk) {
			$CI = &get_instance();
			$sx = '';
			$ss = '<h5>' . msg('hasColaboration') . '</h5><ul>';
			$wks = array();
			for ($r = 0; $r < count($wk); $r++) {
				$id = $wk[$r];

				$data = $this -> le_data($id);
				for ($z = 0; $z < count($data); $z++) {
					$line = $data[$z];
					$cl = $line['c_class'];
					$vl = $line['n_name'];
					$id1 = $line['d_r1'];
					$id2 = $line['d_r2'];
					switch ($cl) {
						case 'hasTitle' :
						$link = '<a href="' . base_url(PATH . 'v/' . $id1) . '">';
						array_push($wks, $id1);
						break;
						case 'hasAuthor' :
						$link = '<a href="' . base_url(PATH . 'v/' . $id2) . '">';
						$ss .= '<li>' . $link . $vl . ' (' . ($cl) . ')</a></li>' . cr();
						break;
						default :
					}
				}
			}
			$ss .= '</ul>';
        //$sx .= '<div class="row img-person" >' . cr();
			for ($r = 0; $r < count($wks); $r++) {
				$wk = $wks[$r];
				$sx .= '<div class="col-md-2 text-center" style="line-height: 80%; margin-top: 40px;">';
				$sx .= $this -> show_manifestation_by_works($wk);
				$sx .= '</div>';
			}
        //$sx .= '</div>' . cr();

			return ($sx);
		}  

		/*********************** BIBLIOGRAFIC ***************/
		function show_manifestation_by_works($id = '', $img_size = 200, $mini = 0) {
			$CI = &get_instance();
			$img = base_url('img/no_cover.png');
			$data = $this -> le_data($id);
			$year = '';

			$title = '';
			$autor = '';
			$nautor = '';
			for ($r = 0; $r < count($data); $r++) {
				$line = $data[$r];
				$class = $line['c_class'];
            //echo '<br>'.$class;
				switch($class) {
					case 'hasTitle' :
					$title = $line['n_name'];
					break;
					case 'hasOrganizator' :
					if (strlen($autor) > 0) {
						$autor .= '; ';
						$nautor .= '; ';
					}
					$link = '<a href="' . base_url(PATH . 'v/' . $line['id_cc']) . '" class="small">';
					$autor .= $link . $line['n_name'] . ' (org.)' . '</a>';
					break;
					case 'hasAuthor' :
					if (strlen($autor) > 0) {
						$autor .= '; ';
						$nautor .= '; ';
					}

					$idx = $line['d_r1'];
					$link = '<a href="' . base_url(PATH . 'v/' . $idx) . '" class="small">';
					$autor .= $link . $line['n_name'] . '</a>';
					$nautor .= $line['n_name'];
					break;
				}
			}
			/* expression */
			$class = "isRealizedThrough";
			$id_cl = $this -> find_class($class);
			$sql = "select * from rdf_data 
			WHERE d_r1 = $id and
			d_p = $id_cl ";
			$xrlt = $CI -> db -> query($sql);
			$xrlt = $xrlt -> result_array();

			if (count($xrlt) > 0) {
				$ide = $xrlt[0]['d_r2'];
				/************************************ manifestation ********/
				$class = "isEmbodiedIn";
				$id_cl = $this -> find_class($class);
				$sql = "select * from rdf_data 
				WHERE d_r1 = $ide and
				d_p = $id_cl ";
				$xrlt = $CI -> db -> query($sql);
				$xrlt = $xrlt -> result_array();
				if (count($xrlt) > 0) {
					$idm = $xrlt[0]['d_r2'];

					/* Image */
					$dt2 = $this -> le_data($idm);
					for ($r = 0; $r < count($dt2); $r++) {
						$line = $dt2[$r];
						$class = $line['c_class'];
						if ($class == 'hasCover') {
							$img = base_url('_repositorio/image/' . $line['n_name']);
						}
						if ($class == 'dateOfPublication') {
							$year = '<br>' . $line['n_name'];
						}
					}
				}
			}

			$sx = '';
			$link = '<a href="' . base_url(PATH . 'v/' . $id) . '" style="line-height: 120%;">';
			$sx .= $link;
			$title_nr = $title;
			$sz = 45;
			if (strlen($title_nr) > $sz) {
				$title_nr = substr($title_nr, 0, $sz);
				while (substr($title_nr, strlen($title_nr) - 1, 1) != ' ') {
					$title_nr = substr($title_nr, 0, strlen($title_nr) - 1);
				}
				$title_nr = trim($title_nr) . '...';
			}

			if ($mini == 1) {
				$sx .= '<img src="' . $img . '" height="' . $img_size . '" style="box-shadow: 5px 5px 8px #888888; margin-bottom: 10px;" title="' . $title_nr . cr() . $nautor . cr() . troca($year, '<br>', '') . '">' . cr();
				$sx .= '</a>';
			} else {
				$sx .= '<img src="' . $img . '" height="200" style="box-shadow: 5px 5px 8px #888888; margin-bottom: 10px;"><br>' . cr();
				$sx .= '<span>' . $title_nr . '</span>';
				$sx .= '</a>';
				$sx .= '<br>';
				$sx .= '<i>' . $autor . '</i>';
				$sx .= $year;
			}
        //echo $line['c_class'].'<br>';
			return ($sx);
		}

		function show_manifestation_by_item($id = '') {

			$item = $this -> le_data($id);

			$idm = $this -> recupera_manifestacao_pelo_item($id);
			$mani = $this -> le_data($idm[0]);

			$ide = $this -> recupera_expressao_pela_manifestacao($idm[0]);
			$expr = $this -> le_data($ide[0]);

			$idw = $this -> recupera_work_pela_expressao($ide[0]);
			$work = $this -> le_data($idw[0]);

			$data = array();
			$data['manifestation'] = $mani;
			$data['expression'] = $expr;
			$data['work'] = $work;
			$data['item'] = $item;
			$data['id'] = $idm[0];
			$sx = $this -> show_item($data);
			return ($sx);
		}      
		function show_rdf($url) {
			$pre = substr($url, 0, strpos($url, ':'));
			$pos = substr($url, strpos($url, ':') + 1, strlen($url));
			$uri = $this -> rdf_prefix($url);
			$sx = '<a href="' . $uri . $pos . '" target="_new' . $url . '">' . $url . '</a>';
			return ($sx);
		}

		function export_json($id)
		{
			$dt = $this->le($id);
			$dd = $this->le_data($id);
			$sx = '{';

			/* Prefixo */
			$pre = $dt['prefix_ref'];    		
			if (strlen($pre) == 0)
			{
				$pre = 'brapci';
			}
			$sx .= '"id"'.				': "'.$id.'",'.cr();
			$sx .= '"a"'.				': "owl:class",'.cr();
			$sx .= '"'.$pre.':class"'.	': "'.$dt['c_class'].'",'.cr();

			/**************** DADOS *******/
			for ($r=0;$r < count($dd);$r++)
			{

				$pre = $dd[$r]['prefix_ref'];    		
				if (strlen($pre) == 0)
				{
					$pre = 'brapci';
				}
				$vlr = $dd[$r]['d_r2'];
				if ($vlr == 0)
				{
					$vlr = '"'.$dd[$r]['n_name'].'"';
				} else {
					if ($vlr == $id)
					{
						$vlr = $dd[$r]['d_r1'];
					}
					$vlr = '"brapci_v:'.$vlr.'"';
				}
				$sx .= '"'.$pre.':'.$dd[$r]['c_class'].'": ';
				$sx .= $vlr.' ,'.cr();
			}
			$sx .= '"date_timestamp_get": "'.date("Y-m-d H:i:s")."'";
			$sx .= "}";
			return($sx);
		}

		function export_rdf($id)
		{
			$dt = $this->le($id);
			$dd = $this->le_data($id);

			$sx = '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .'.cr();
			$sx .= '@prefix rdfs:  <http://www.w3.org/2000/01/rdf-schema#> .'.cr();
			$sx .= '@prefix owl:   <http://www.w3.org/2002/07/owl#> .'.cr();
			$sx .= '@prefix skos:  <http://www.w3.org/2004/02/skos/core#> .'.cr();
			$sx .= '@prefix skosxl: <http://www.w3.org/2008/05/skos-xl#> .'.cr();
			$sx .= '@prefix brapci: <'.base_url(PATH.'owl').'#> .'.cr();
			$sx .= '@prefix brapci_v: <'.base_url(PATH.'v').'#> .'.cr();
			$sx .= cr();
			$sx .= '<'.base_url(PATH.'v/'.$id).'>'.cr();

			$tab = chr(9);
			/* Prefixo */
			$pre = $dt['prefix_ref'];    		
			if (strlen($pre) == 0)
			{
				$pre = 'brapci';
			}
			$sx .= $tab.'a'.				$tab.'owl:class ;'.cr();
			$sx .= $tab.$pre.':class '.		$tab.$dt['c_class'].' ;'.cr();

			/**************** DADOS *******/
			for ($r=0;$r < count($dd);$r++)
			{

				$pre = $dd[$r]['prefix_ref'];    		
				if (strlen($pre) == 0)
				{
					$pre = 'brapci';
				}
				$vlr = $dd[$r]['d_r2'];
				if ($vlr == 0)
				{
					$vlr = '"'.$dd[$r]['n_name'].'"';
				} else {
					if ($vlr == $id)
					{
						$vlr = $dd[$r]['d_r1'];
					}
					$vlr = 'brapci_v:'.$vlr.' #'.$dd[$r]['n_name'];
				}
				$sx .= $tab.$pre.':'.$dd[$r]['c_class'];
				$sx .= $tab.$vlr.' ;'.cr();
			}
			return($sx);
		}
		function text_edit($id)
		{
			$cp = array();
			array_push($cp,array('$H8','id_n','',false,false));
			array_push($cp,array('$T80:6','n_name',msg('text'),true,true));
			$op = 'pt_BR:Portugues';
			$op .= '&en:Inglês';
			$op .= '&es:Espanhol';
			$op .= '&un:Multilingue';
			array_push($cp,array('$O '.$op,'n_lang',msg('language'),true,true));

			$table = 'rdf_name';
			$form = new form;
			$form->id = $id;
			$sx = $form->editar($cp,$table);

			if ($form->saved > 0)
			{
				$sx = '<script> wclose(); </script>';
			}
			return($sx);				
		}
		function link($c)
		{
			$s = $c;
			if (strpos($s,':'))
			{
				$pre = substr($s,0,strpos($s,':'));
				switch($pre)
				{
					case 'thesa':
					$lk = 'http://ufrgs.br/tesauros/index.php/thesa/c/'.sonumero($c);
					$s = '<a href="'.$lk.'" target="_new">'.$s.'</a>';
					break;
					default:
					$s = $s;
				}
			}
			return($s);
		}

		function rdf_export($id,$out=1)
			{
				/******* converte em Array ****/
				if (!is_array($id))
					{
						$id = array($id);
					}
				/* Coleta dados */
				$rdf = new rdf;
				$sx = '';
				for ($r=0;$r < count($id);$r++)
				{
					$file = '_c/'.$id[$r].'.xml';
					if (file_exists($file))
					{
						$sx .= file_get_contents($file);
					} else {
						$this->rdf_register($id[$r]);
						$sx .= file_get_contents($file);
					}
					
				}
				if ($out == 0)
				{
					/* gerar arquivo de exportação */
					header('Content-Type: application/xml; charset=utf-8');
					echo $this->rdf_header();
					echo $sx;
					echo $this->rdf_footer();
				} else {
					return($sx);
				}
			}
		function rdf_header()
			{
				/*********** Header do RDF ************/
				$sx = '<rdf:RDF ';
				$sx .= 'xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" ';
				$sx .= 'xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" ';
				$sx .= 'xmlns:lattes="http://www.w3.org/2000/01/rdf-schema#" ';				
				$sx .= 'xmlns:redd="'.base_url(PATH.'rdf/xml/').'" ';
				$sx .= 'xmlns:skos="http://www.w3.org/2000/01/skos" ';
				$sx .= 'xmlns:owl=http://www.w3.org/2002/07/owl# ';
				$sx .= '>'.cr();
				return($sx);
			}

		function rdf_footer()
			{

				/*********** Finalizar o RDF ************/
				$sx = '</rdf:RDF>';
				return($sx);
			}

		function rdf_register($id)
			{				
				global $ids;

				$hd = $this->le($id);
				$dt = $this->le_data($id);

				$sx = '<lattes:curriculolattes rdf:about="'.base_url(PATH.'rdf/id/'.$hd['id_cc']).'">'.cr();
				$sx .= '<owl:Class>'.trim($hd['c_class']).'</owl:Class>'.cr();
				$sx .= '<lattes:id>'.trim($hd['n_name']).'</lattes:id>'.cr();
				$sx .= '<lattes:published>'.trim($hd['n_name']).'</lattes:published>'.cr();
				
				for ($r=0;$r < count($dt);$r++)
				{
					$pref = trim($dt[$r]['prefix_ref']);
					if (strlen($pref) == 0) { $pref = 'lattes'; }
					$class = trim($dt[$r]['c_class']);
					$lang = trim($dt[$r]['n_lang']);
					if ($dt[$r]['d_r2'] > 0)
						{
							$idr = $dt[$r]['d_r2'];
							if ($idr == $id) { $idr = $dt[$r]['d_r1']; }
							$vlr = base_url(PATH.'rdf/id/'.$idr);
							$vlr = 'redd:'.$idr;
							$sx .= '<'.$pref.':'.$class.' resource="'.$vlr.'"/>'.cr();
							if (!isset($ids[$idr]))
								{
									$ids[$idr] = 0;
								}
						} else {
							$vlr =$dt[$r]['n_name'];
							$sx .= '<'.$pref.':'.$class.'>'.$vlr.'</'.$pref.':'.$class.'>'.cr();
						}				
				}
				$sx .= '</lattes:curriculolattes>'.cr();
				$file = '_c';
				dircheck($file);
				$file .= '/'.$hd['id_cc'].'.xml';
				file_put_contents($file,$sx);

			return($sx);
			}
	}
	?>