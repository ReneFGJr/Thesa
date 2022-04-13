<?php

namespace App\Controllers;

use App\Controllers\BaseController;

$this->session = \Config\Services::session();
$language = \Config\Services::language();

helper(['boostrap', 'graphs', 'sisdoc_forms', 'form', 'nbr']);
helper("URL");

define("PATH", $_SERVER['app.baseURL'] . $_SERVER['app.sufix']);
define("URL", $_SERVER['app.baseURL']);
define("MODULE", 'thesa/');
define('PREFIX', 'thesa.');

class Thesa extends BaseController
{

	public function __construct()
	{
		$this->Socials = new \App\Models\Socials();

		helper(['boostrap', 'URL', 'canvas']);
		define("LIBRARY", "5000");
		define("LIBRARY_NAME", "THESA");
	}

	public function index()
	{
		$view = \Config\Services::renderer();
		$ThOpen = new \App\Models\Thesaurus\ThThesaurus();
		$sx = '';
		$sx .= $this->cab();		
		$sx .= $this->navbar();
		$sx .= $view->render('paralax');
		$sx .= $ThOpen->about();
		$sx .= $this->footer();

		return $sx;
	}

	function edit_th($id)
		{
		$view = \Config\Services::renderer();
		$ThOpen = new \App\Models\Thesaurus\ThThesaurus();
		$sx = '';
		$sx .= $this->cab();		
		$sx .= $this->navbar();
		$sx .= $view->render('paralax');

		$hd = h(lang('thesa.edit'));
		$sx .= bs($hd.bsc($ThOpen->edit($id),12));

		$sx .= $this->footer();

		return $sx;
		}


	/************************************** Thesauros Abertos */
	function tho($id)
	{
		$sx = $this->cab();
		$view = \Config\Services::renderer();
		$sx .= $view->render('header/head_th');

		$tela1 = $view->render('header/head_search');
		$sx .= $tela1;

		return $sx;
	}

	private function cab($dt = array())
	{
		$title = 'Thesa';
		if (isset($dt['title'])) {
			$title = $dt['title'];
		}
		$sx = '<!doctype html>' . cr();
		$sx .= '<html>' . cr();
		$sx .= '<head>' . cr();
		$sx .= '<title>' . $title . '</title>' . cr();
		$sx .= '  <meta charset="utf-8" />' . cr();
		$sx .= '  <link rel="apple-touch-icon" sizes="180x180" href="' . URL.('img/icone/favicon.png') . '" />' . cr();
		$sx .= '  <link rel="icon" type="image/png" sizes="32x32" href="' . URL.('img/icone/favicon.png') . '" />' . cr();
		$sx .= '  <link rel="icon" type="image/png" sizes="16x16" href="' . URL.('img/icone/favicon.png') . '" />' . cr();
		$sx .= '  <!-- CSS -->' . cr();
		$sx .= '  <link rel="stylesheet" href="' . URL.('/css/bootstrap.css') . '" />' . cr();
		$sx .= '  <link rel="stylesheet" href="' . URL.('/css/style.css?v=0.0.10') . '" />' . cr();
		/* GOogle Fonts */
		$sx .= ' <style>
						@import url(\'https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap\');
					</style>';
		$sx .= ' ' . cr();
		$sx .= '  <!-- CSS -->' . cr();
		$sx .= '  <script src="' . URL.('/js/bootstrap.js?v=5.0.2') . '"></script>' . cr();
		$sx .= '<style>
					@font-face {font-family: "Handel Gothic";
					src: url("' . URL.('css/fonts/HandelGothic/handel_gothic.eot') . '"); /* IE9*/
					src: url("' . URL.('css/fonts/HandelGothic/handel_gothic.eot?#iefix') . '") format("embedded-opentype"), /* IE6-IE8 */
					url("' . URL.('css/fonts/HandelGothic/handel_gothic.svg#Handel Gothic') . '") format("svg"); /* iOS 4.1- */
					}
					@import url(\'https://fonts.googleapis.com/css2?family=Nunito:wght@200&family=Roboto:wght@100&display=swap\');
					</style>
					';

		$sx .= '</head>' . cr();

		if (get("debug") != '') {
			$sx .= '<style> div { border: 1px solid #000000;"> </style>';
		}
		return $sx;
	}

	private function navbar($dt = array())
	{
		$ThOpen = new \App\Models\Thesaurus\ThThesaurus();
		$title = 'Thesa';
		$url = PATH . MODULE;
		if (isset($_SESSION['th'])) {
			$url = PATH . MODULE .'th/'.$_SESSION['th'];
		}
		if (isset($dt['title'])) {
			$title = $dt['title'];
		}
		$title = '<img src="' . URL.('img/logo/logo_thesa.jpg') . '" style="height: 28px;">';
		$sx = '<nav class="navbar navbar-expand-lg navbar-light ">' . cr();
		$sx .= '  <div class="container-fluid">' . cr();
		$sx .= '    <a class="navbar-brand" href="' . $url . '">' . $title . '</a>' . cr();
		$sx .= '    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">' . cr();
		$sx .= '      <span class="navbar-toggler-icon"></span>' . cr();
		$sx .= '    </button>' . cr();
		$sx .= '    <div class="collapse navbar-collapse" id="navbarSupportedContent">' . cr();
		$sx .= '      <ul class="navbar-nav me-auto mb-2 mb-lg-0">' . cr();
		/*
			$sx .= '        <li class="nav-item">'.cr();
			$sx .= '          <a class="nav-link active" aria-current="page" href="#">Home</a>'.cr();
			$sx .= '        </li>'.cr();
			$sx .= '        <li class="nav-item">'.cr();
			$sx .= '          <a class="nav-link" href="#">Link</a>'.cr();
    		$sx .= '		</li>'.cr();
			*/

		$sx .= '			<li class="nav-item">
								<a class="nav-link" href="' . (PATH . MODULE . 'thopen/') . '">' . lang('thesa.th_open') . '</a>
							</li>';
		/*************************************** TH ATUAL */
		if ((isset($_SESSION['th'])) and ($_SESSION['th'] != '')) {
			$id = $_SESSION['th'];
			$sx .= '		<li class="nav-item">
								<a class="nav-link" href="' . (PATH .MODULE .'th/'.$id) . '">' . lang('thesa.th_actual') . '</a>
							</li>';
		}

		if ((isset($_SESSION['id'])) and ($_SESSION['id'] != '')) {
			$sx .= '		<li class="nav-item">
								<a class="nav-link" href="' . (PATH .MODULE .'th_my/') . '">' . lang('thesa.th_my') . '</a>
							</li>';
		}
		/*********************************** Configuração (Item do menu) */
		if ($ThOpen->access())
		{
			$sx .= '		<li class="nav-item">
								<a class="nav-link" href="' . (PATH . MODULE .'th_config/') . '">' . lang('thesa.th_config') . '</a>
							</li>';
		}

		$sx .= '
							<li class="nav-item">
								<a class="nav-link" href="' . (PATH . MODULE .'language') . '">' . lang('thesa.language') . '</a>
							</li>			
			';

		$sx .= '
							<li class="nav-item">
								<a class="nav-link" href="' . (PATH . MODULE .'help') . '">' . lang('thesa.Help') . '</a>
							</li>			
			';			

		$sx .= '      </ul>' . cr();
		$sx .= $this->Socials->nav_user();

		$sx .= '    </div>' . cr();
		$sx .= '  </div>' . cr();
		$sx .= '</nav>' . cr();
		return $sx;
	}
	
	
	function api($d1='',$d2='',$d3='')
		{
			$sx = $this->cab();
			$sx .= $this->navbar();
			$sa = bsc(h('API'),12);
			$sa .= bsc(URL.'/rest/v1/search?query=',12);
			$sa .= bsc(URL.'/rest/v1/data?uri=',12);
			$sx .= bs($sa);
			$sx .= $this->footer();
			return $sx;
		}

	function footer()
	{
		$sx = '<!-- Footer -->
					<footer class="page-footer font-small blue-grey lighten-5" style="margin-top: 50px;">

					<div style="background-color: #2192d1;">
						<div class="container">

						<!-- Grid row-->
						<div class="row py-4 d-flex align-items-center">

							<!-- Grid column -->
							<div class="col-md-6 col-lg-5 text-center text-md-left mb-4 mb-md-0">
							<h6 class="mb-0">Get connected with us on social networks!</h6>
							</div>
							<!-- Grid column -->

							<!-- Grid column -->
							<div class="col-md-6 col-lg-7 text-center text-md-right">

							<!-- Facebook -->
							<a class="fb-ic">
								<i class="fab fa-facebook-f white-text mr-4"> </i>
							</a>
							<!-- Twitter -->
							<a class="tw-ic">
								<i class="fab fa-twitter white-text mr-4"> </i>
							</a>
							<!-- Google +-->
							<a class="gplus-ic">
								<i class="fab fa-google-plus-g white-text mr-4"> </i>
							</a>
							<!--Linkedin -->
							<a class="li-ic">
								<i class="fab fa-linkedin-in white-text mr-4"> </i>
							</a>
							<!--Instagram-->
							<a class="ins-ic">
								<i class="fab fa-instagram white-text"> </i>
							</a>

							</div>
							<!-- Grid column -->

						</div>
						<!-- Grid row-->

						</div>
					</div>

					<!-- Footer Links -->
					<div class="container text-center text-md-left mt-5">

						<!-- Grid row -->
						<div class="row mt-3 dark-grey-text">

						<!-- Grid column -->
						<div class="col-md-3 col-lg-4 col-xl-3 mb-4">

							<!-- Content -->
							<h6 class="text-uppercase font-weight-bold">' . lang('thesa.COMPANY NAME') . '</h6>
							<hr class="teal accent-3 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">
							<p>Here you can use rows and columns to organize your footer content. Lorem ipsum dolor sit amet,
							consectetur
							adipisicing elit.</p>

						</div>
						<!-- Grid column -->

						<!-- Grid column -->
						<div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">

							<!-- Links -->
							<h6 class="text-uppercase font-weight-bold">' . lang('thesa.Products') . '</h6>
							<hr class="teal accent-3 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">
							<p>
							<a class="dark-grey-text" href="#!">MDBootstrap</a>
							</p>
							<p>
							<a class="dark-grey-text" href="#!">MDWordPress</a>
							</p>
							<p>
							<a class="dark-grey-text" href="#!">BrandFlow</a>
							</p>
							<p>
							<a class="dark-grey-text" href="#!">Bootstrap Angular</a>
							</p>

						</div>
						<!-- Grid column -->

						<!-- Grid column -->
						<div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">

							<!-- Links -->
							<h6 class="text-uppercase font-weight-bold">' . lang('social.Useful_links') . '</h6>
							<hr class="teal accent-3 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">
							<p>
							<a class="dark-grey-text" href="#!">' . 'rene.gabriel@ufrgs.br' . '</a>
							</p>
							<p>
							<a class="dark-grey-text" href="#!">' . lang('thesa.help') . '</a>
							</p>

						</div>
						<!-- Grid column -->

						<!-- Grid column -->
						<div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">

							<!-- Links -->
							<h6 class="text-uppercase font-weight-bold">' . lang('social.Develepoment') . '</h6>
							
							<img src="' . URL.('img/logo/logo_orcalab-70.png') . '" class="img-fluid">
							<hr class="teal accent-3 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">
							<img src="' . URL.('img/logo/logo_ppgcin-70.png') . '" class="img-fluid">
						</div>
						<!-- Grid column -->

						</div>
						<!-- Grid row -->

					</div>
					<!-- Footer Links -->

					<!-- Copyright -->
					<div class="footer-copyright text-center text-black-50 py-3">© 2019-' . date("Y") . ' Copyright:
						<a class="dark-grey-text" href="https://github.com/ReneFGJr/thesa" target="_github">GitHub / ReneFGJr / Find </a>
					</div>
					<!-- Copyright -->

					</footer>
					<!-- Footer -->';
		return $sx;
	}

	function contact()
	{
		$sx = '';
		$sx .= $this->cab();
		$sx .= $this->navbar();

		$sm = '<h3>' . lang('Contact') . '</h3>';
		$sm .= '<p>' . '<b>Rene Faustino Gabriel Junior</b>' . '<br/>';
		$sm .= 'rene.gabriel@ufrgs.br' . '<p/>';
		$sm .= '<br/>';
		$sm .= '<b>Como citar o Thesa</b><br/>
                    GABRIEL JUNIOR, R. F.; LAIPELT, R. C. F. Thesa: ferramenta para construçÃo de tesauro semântico aplicado interoperável. <b>Revista P2P e INOVAÇÃO</b>, v. 3, n. 2, 2017. DOI: 10.21721/p2p.2017v3n2.p124-145
                    ';
		$sx .= bs(bsc($sm, 12));
		$sx .= $this->footer();

		return $sx;
	}

	function term($id=0,$act='')
		{
			$ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();
			$ThConcept = new \App\Models\Thesaurus\ThConcept();
			$ThLiteral = new \App\Models\Thesaurus\ThLiteral();
			$ltr = '';

			$sa = $ThThesaurus->index($id);
			$sx = '';
			$sx .= $this->cab();
			$sx .= $this->navbar();
			$dt = $ThThesaurus->find($id);
			$sa .= $ThThesaurus->show($dt['id_pa'], '');
			$sx .= bs($sa);

			switch($act)
				{
					case 'add':
					$sx .= bs($ThLiteral->form($id));
					break;

					case 'concept':
					$sx .= bs($ThLiteral->associate($id));
					$sx .= '';
					break;					
				}
			

			$sx .= $this->footer();
			return $sx;
		}

	function tree($id = '', $ltr = '')
	{
		$Tree = new \App\Models\Graph\Tree();
		$ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();		
		$sx = '';
		$sx .= $this->cab();
		$sx .= $this->navbar();
		$sx .= $ThThesaurus->show($id, $ltr);

		$sx .= bs(bsc($Tree->Graph(1),12));
		$sx .= $this->footer();

		return $sx;
	}	

	function sistematic($id = '', $ltr = '')
	{
		$Sistematic = new \App\Models\Graph\Sistematic();
		$ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();		
		$sx = '';
		$sx .= $this->cab();
		$sx .= $this->navbar();
		$sx .= $ThThesaurus->show($id, $ltr);

		$sx .= bs(bsc($Sistematic->Graph(1),12));
		$sx .= $this->footer();

		return $sx;
	}	

		

	function th($id = '', $ltr = '')
	{
		$ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();		
		$sx = '';
		$sx .= $this->cab();
		$sx .= $this->navbar();
		$sx .= $ThThesaurus->show($id, $ltr);
		$ThThesaurus->setTh($id);

		if ($ltr != '')
			{
				$sx .= bs(bsc($ThThesaurus->terms($id,$ltr),12));
			}

		$sx .= $this->footer();

		return $sx;
	}

	function tools($act='',$d1='',$d2='',$d3='',$d4='')
		{
			$view = \Config\Services::renderer();
			$Tools = new \App\Models\Tools\Index();

			$sx = $this->cab();
			$sx .= $this->navbar();				
			$sx .= $view->render('paralax');
			$sx .= $Tools->index($act,$d1,$d2,$d3,$d4);
			$sx .= $this->footer();
			return $sx;			
		}

	function edit($id=0,$ac='')
		{
			$sx = $this->cab();
			$sx .= $this->navbar();		

			$Thesauros = new \App\Models\Thesaurus\Index();
			$sx = $Thesauros->edit($id,$ac);

			return $sx;
		}

	function schema($type,$id,$act='')
		{
			$sx = $this->cab();
			$sx .= $this->navbar();		

			$SchemaExternal = new \App\Models\Schema\SchemaExternal();
			
			if ($type == 'skos')
			{
				$sx .= $SchemaExternal->showID($id,$act);
			}	
			$sx .= $this->footer();

			return $sx;
		}

	function popup($d1='',$d2='',$d3='',$d4='',$d5='',$d6='')
		{
			$sx = '';
			$sec = true;
			$cab = $this->cab();

			switch($d1)
				{
					case 'prefLabel':
						$ThLiteral = new \App\Models\Thesaurus\ThLiteral();
						$ThLiteral->label_update($d2,$d1);
						$sx .= wclose();
						break;

						break;
					case 'propriety_del':
						$ThAssociate = new \App\Models\Thesaurus\ThAssociate();
						$ThAssociate->propriety_update($d2,0);
						$sx .= wclose();
						break;
					case 'propriety_undel':
						$ThAssociate = new \App\Models\Thesaurus\ThAssociate();
						$ThAssociate->propriety_update($d2,1);
						$sx .= wclose();
						break;


					case 'broader':
						$ThAssociate = new \App\Models\Thesaurus\ThAssociate();
						$sx .= $cab;
						$sx .= $ThAssociate->associate($d2,$d3,$d4,$d5,'TG');
					break;

					case 'narrower':
						$ThAssociate = new \App\Models\Thesaurus\ThAssociate();
						$sx .= $cab;
						$sx .= $ThAssociate->associate($d2,$d3,$d4,$d5,'TE');
					break;

					case 'related':
						$ThAssociate = new \App\Models\Thesaurus\ThAssociate();
						$sx .= $cab;
						$sx .= $ThAssociate->associate($d2,$d3,$d4,$d5,'TR');
					break;					

					case 'associate':
						$ThLiteral = new \App\Models\Thesaurus\ThLiteral();
						$sx = $cab;
						$sx .= $ThLiteral->term_concept($d2,$d3);
					break;

					case 'relations':
					$ThConfigRelations = new \App\Models\Thesaurus\ThConfigRelations();
					if ($d4 == 'del')
						{
							$sx .= $ThConfigRelations->excluding($d2,$d3,$d4,$d5,$d6);
							return $sx;
						}					
					break;

					case 'colaboration':
					$ThConfigColaboration = new \App\Models\Thesaurus\ThConfigColaboration();
					if ($d4 == 'del')
						{
							$sx .= $ThConfigColaboration->excluding($d2,$d3,$d4,$d5,$d6);
							return $sx;
						}					
					$sx = $cab;
					$sx .= $ThConfigColaboration->add_colaboration($d2,$d3,$d4,$d5,$d6);
					break;

					case 'relation_thesa':
					$ThConfigRelations = new \App\Models\Thesaurus\ThConfigRelations();
					$sx = $cab;
					$sx .= $ThConfigRelations->add_thesa_relations($d2,$d3,$d4,$d5,$d6);
					break;

					case 'relation_skos':
					$ThConfigRelations = new \App\Models\Thesaurus\ThConfigRelations();
					$sx = $cab;
					$sx .= $ThConfigRelations->add_skos_relations($d2,$d3,$d4,$d5,$d6);
					break;					
					
					default:
					$sx = $cab;
					$sx .= bsmessage('Popup não encontrado - '.$d1);
				}
			return $sx;
		}

	function search($q='')
		{
			echo $q;
		}

    function c($id,$tp= '')
        {
            return $this->v($id,$tp);
        }  	

	function v($id = '', $tp = '')
	{
		switch ($tp)
			{
				case 'rdf':
					$ThThesaurus = new \App\Models\Api\Index();
					$ThThesaurus->rdf($id);
					exit;
					break;
			}
		/****************************** Screen */
		$ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();		
		$ThConcept = new \App\Models\Thesaurus\ThConcept();
		$sx = '';
		$sx .= $this->cab();
		$sx .= $this->navbar();

		$dt = $ThConcept->find($id);
		$th = $dt['c_th'];
		$ltr = '';

		$sx .= $ThThesaurus->show($th, $ltr);
		$sx .= $ThThesaurus->v($id);

		$sx .= $this->footer();
		return $sx;
	}

	function a($id = '',$act='')
	{
		$ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();
		$ThConcept = new \App\Models\Thesaurus\ThConcept();
		
		$sx = '';
		$sx .= $this->cab();
		$sx .= $this->navbar();

		$dt = $ThConcept->find($id);
		$th = $dt['c_th'];
		$ltr = '';		
		$sx .= $ThThesaurus->show($th, $ltr);

		$sx .= $ThThesaurus->a($id,$act);

		$sx .= $this->footer();
		return $sx;
	}

	function thopen()
	{
		$view = \Config\Services::renderer();
		$ThOpen = new \App\Models\Thesaurus\ThThesaurus();
		$sx = '';
		$sx .= $this->cab();
		//$sx .= $this->view('paralax');			
		$sx .= $this->navbar();
		$sx .= $view->render('paralax');		
		$sx .= $ThOpen->index();
		$sx .= $this->footer();

		return $sx;
	}

	function th_my()
	{
		$view = \Config\Services::renderer();
		$ThOpen = new \App\Models\Thesaurus\ThThesaurus();
		$sx = '';
		$sx .= $this->cab();
		//$sx .= $this->view('paralax');			
		$sx .= $this->navbar();
		$sx .= $view->render('paralax');
		$sx .= $ThOpen->myth();
		$sx .= $this->footer();

		return $sx;
	}	

	function help($d1='',$d2='')
		{
			$sx = '';
			$Help = new \App\Models\Help\Index();
			$view = \Config\Services::renderer();
			$sx .= $this->cab();
			$sx .= $this->navbar();
			$sx .= $view->render('paralax');
			$sx .= $Help->index($d1,$d2);
			$sx .= $this->footer();			

			return $sx;
		}

	function social($d1='',$d2='',$d3='')
	{
		$Socials = new \App\Models\Socials();
		$view = \Config\Services::renderer();
		$sx = '';
		if ($d1=='login')
		{
			$sx .= $this->cab();
			$sx .= $this->navbar();
			$sx .= $view->render('paralax');
			$sx .= $Socials->index($d1,$d2,$d3);
			$sx .= $this->footer();
		} else {
			$sx .= $Socials->index($d1,$d2,$d3);
		}
		return $sx;
	}

	function th_config($id=0,$d1='',$d2='',$d3='')
		{
			$ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();
			$sx = '';
			$sx .= $this->cab();
			$sx .= $this->navbar();

			/********** Security */
			if ($ThThesaurus->access($id))
			{
				/********** Config */			
				$sx .= $ThThesaurus->config($id,$d1,$d2,$d3);
			} else {
				if ($id > 0)
				{
					$sx .= metarefresh(PATH.MODULE.'th/'.$id);
				} else {
					$sx .= metarefresh(PATH.MODULE);
				}
				
			}
			return $sx;
		}
}
