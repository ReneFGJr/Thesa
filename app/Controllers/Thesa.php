<?php

namespace App\Controllers;

use App\Controllers\BaseController;

$this->session = \Config\Services::session();
$language = \Config\Services::language();

helper(['boostrap', 'URL', 'graphs', 'sisdoc_forms', 'form', 'nbr']);

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
		$ThOpen = new \App\Models\ThThesaurus();
		$sx = '';
		$sx .= $this->cab();
		//$sx .= $this->view('paralax');			
		$sx .= $this->navbar();
		$sx .= $view->render('paralax');
		$sx .= $ThOpen->index();
		$sx .= $this->footer();

		return $sx;
	}

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
		$sx .= '  <link rel="stylesheet" href="' . URL.('/css/style.css?v=0.0.9') . '" />' . cr();
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
		$title = 'Thesa';
		if (isset($dt['title'])) {
			$title = $dt['title'];
		}
		$title = '<img src="' . URL.('img/logo/logo_thesa.jpg') . '" style="height: 28px;">';
		$sx = '<nav class="navbar navbar-expand-lg navbar-light ">' . cr();
		$sx .= '  <div class="container-fluid">' . cr();
		$sx .= '    <a class="navbar-brand" href="' . PATH . MODULE . '">' . $title . '</a>' . cr();
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

		$sx .= '
							<li class="nav-item">
								<a class="nav-link" href="' . (PATH . MODULE . 'thopen/') . '">' . lang('thesa.th_open') . '</a>
							</li>			
			';
		if ((isset($_SESSION['id'])) and ($_SESSION['id'] != '')) {
			$sx .= '
							<li class="nav-item">
								<a class="nav-link" href="' . (PATH .MODULE .'my_thesa/') . '">' . lang('thesa.th_open') . '</a>
							</li>			
					';
			$sx .= '
							<li class="nav-item">
								<a class="nav-link" href="' . (PATH . MODULE .'th_config/') . '">' . lang('thesa.th_open') . '</a>
							</li>			
			';
		}

		$sx .= '
							<li class="nav-item">
								<a class="nav-link" href="' . (PATH . MODULE .'language') . '">' . lang('thesa.language') . '</a>
							</li>			
			';

		$sx .= '      </ul>' . cr();
		$sx .= $this->Socials->nav_user();

		$sx .= '    </div>' . cr();
		$sx .= '  </div>' . cr();
		$sx .= '</nav>' . cr();
		return $sx;
	}

	private function footer()
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
	function th($id = '', $ltr = '')
	{
		$ThThesaurus = new \App\Models\ThThesaurus();
		$sx = '';
		$sx .= $this->cab();
		$sx .= $this->navbar();
		$sx .= $ThThesaurus->index($id, $ltr);
		$sx .= $this->footer();

		return $sx;
	}

    function c($id)
        {
            return $this->v($id);
        }  	

	function v($id = '')
	{
		$ThThesaurus = new \App\Models\ThThesaurus();
		$sx = '';
		$sx .= $this->cab();
		$sx .= $this->navbar();
		$sx .= $ThThesaurus->v($id);
		$sx .= $this->footer();
		return $sx;
	}
	function thopen()
	{
		$view = \Config\Services::renderer();
		$ThOpen = new \App\Models\ThThesaurus();
		$sx = '';
		$sx .= $this->cab();
		//$sx .= $this->view('paralax');			
		$sx .= $this->navbar();
		$sx .= $view->render('paralax');
		$sx .= $ThOpen->index();
		$sx .= $this->footer();

		return $sx;
	}
	function social($d1='',$d2='',$d3='')
	{
		$Socials = new \App\Models\Socials();
		$view = \Config\Services::renderer();
		$Socials->table='users';
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
}
