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

class Rest extends BaseController
{

	public function __construct()
	{
		$this->Socials = new \App\Models\Socials();

		helper(['boostrap', 'URL', 'canvas']);
		define("LIBRARY", "5000");
		define("LIBRARY_NAME", "THESA");
	}
	function index()
		{
			echo "Hello World";
		}
	function search()
	{
		echo "Hello World 2";
	}		
}
