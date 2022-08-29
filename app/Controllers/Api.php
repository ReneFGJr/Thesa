<?php
namespace App\Controllers;

use App\Controllers\BaseController;

$language = \Config\Services::language();
$this->session = \Config\Services::session();

helper(['boostrap', 'graphs', 'sisdoc_forms', 'form', 'nbr']);
helper("URL");

define("PATH", $_SERVER['app.baseURL']);
define("URL", $_SERVER['app.baseURL']);
define("MODULE", 'thesa/');
define('PREFIX', 'thesa.');

class Api extends BaseController
{

	public function __construct()
	{
		$this->Socials = new \App\Models\Socials();

		helper(['boostrap', 'URL', 'canvas']);
		define("LIBRARY", "5000");
		define("LIBRARY_NAME", "THESA");
	}

	public function index($d1='',$d2='',$d3='',$d4='',$d5='')
	{
		$view = \Config\Services::renderer();
		$Api = new \App\Models\Api\Index();
		$sx = '';

        switch($d1)
            {
				case 'term':
					$APITerm = new \App\Models\Api\Functions\Term();
					$APITerm->index($d1, $d2, $d3, $d4, $d5);
				break;

				case 'thesa':
					$APIThesa = new \App\Models\Api\Functions\Thesa();
					$APIThesa->index($d1, $d2, $d3, $d4, $d5);
				break;

				case 'user':
					$APIUser = new \App\Models\Api\Functions\User();
					$APIUser->index($d1, $d2, $d3, $d4, $d5);
					break;

				case 'term':
					$APITerm = new \App\Models\Api\Functions\Term();
					$APITerm->index($d1,$d2,$d3,$d4,$d5);
					break;

                case 'help':
					$sx .= $Api->docummentation($d2, $d3);
					break;

                default:
                    $sx .= $Api->docummentation($d1,$d2);
                    break;
            }
		return $sx;
	}
}
