<?php
# version: 0.21.07.30

namespace App\Models;

use CodeIgniter\Model;
use \app\Model\MainModel;

use function App\Models\AI\Authority\check;

class Socials extends Model
{
	var $DBGroup              		= 'default';
	var $table                		= 'users';
	var $primaryKey          		 = 'id_us';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	var $allowedFields        =
	[
		'id_us', 'us_nome', 'us_email', 'us_affiliation',
		'us_image', 'us_genero', 'us_verificado',
		'us_login', 'us_password', 'us_autenticador',
		'us_oauth2', 'us_lastaccess', 'us_apikey_active', 'us_apikey', 'us_recover'
	];

	var $typeFields        = [
		'hi', 'string:100*',
		'string:100*', 'string:100*',
		'hidden', 'hidden', 'hidden',
		'hidden', 'hidden', 'hidden',
		'hidden', 'up'
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

	var $path;
	var $path_back;
	var $id = 0;
	var $error = 0;
	var $site = 'https://brapci.inf.br/#/';

	function generateApiKey($id)
	{
		$length = 32;
		// Generate random bytes
		$randomBytes = random_bytes($length / 2);
		// Convert the bytes to a hexadecimal string
		$apiKey = bin2hex($randomBytes);
		$data = date("Y-m-d H:i:s");
		$qu = "update users set us_apikey = '$apiKey', us_apikey_active = 1, us_apikey_date = '$data' where id_us = $id";
		$this->db->query($qu);
		return $apiKey;
	}

	function validaAPIKEY($apiKey)
		{
			$builder = $this->db->table('users');
			$builder->where('us_apikey',$apiKey);
			$query = $builder->get();
			foreach ($query->getResult() as $row) {
				return $row->id_us;
			}
			return 0;
		}

	function le($id=0)
		{
			$dt = $this
			->join('thesa_users', 'th_us_perfil = id_pe')
			->join('users', 'th_us_user = id_us')
			->where('id_us',$id)
			->first();
			return $dt;
		}

	function api_key($id)
		{
			$sx = '';
			$dt = $this->le($id);
			if (isset($dt['us_apikey']))
				{
					$sx .= '<tt>APIKEY: '.$dt['us_apikey'].'</tt>';
				} else {
					$sx .= '<tt>APIKEY: ';
					$sx .= $this->generateApiKey($id);
					$sx .= '</tt>';
				}
			return $sx;

		}

	function createAPIKEY_user($id)
		{
			$dt = $this->le($id);
			pre($dt);
		}

	function chagePassword($apikey,$pass)
		{
			$dt = $this->where('us_recover',$apikey)->first();
			$password = md5($pass);
			if ($dt != [])
				{
					$this
					->set('us_password', $password)
					->set('us_recover', '')
					->where('id_us', $dt['id_us'])
					->update();
					return True;
				} else {
					return false;
				}
		}


	function calcMD5($value = '', $id = 0)
	{
		if ($id == 0) {
			$id = $_SESSION['id'];
		}
		$value = substr($value . date("Ymd"), 6, 6);
		return $value;
	}

	function putPerfil($nvl = array(), $id = '')
	{
		$access = array();
		foreach ($nvl as $key => $value) {
			if (strlen($value) > 0) {
				$value = $this->calcMD5($value, $id);
				array_push($access, $value);
			}
		}
		$access = json_encode($access);
		return $access;
	}

	function index($cmd = '', $id = '', $dt = '', $cab = '')
	{
		$RSP = [];

			switch ($cmd) {

			case 'login':
				$rsp = $this->signin();
				return $rsp;
				break;
			case 'signup':
				$rsp = $this->signup();
				return $rsp;
				break;
			case 'forget':
				$rsp = $this->forgout();
				return $rsp;
				break;
			case 'logout':
				$sx = $this->logout();
				break;
			default:
				$access = $this->getAccess('#ADM#GER');
				if (!$access) {
					$RSP = $this->access_denied();
				}
				$RSP['cmd'] = $cmd;

				if ($cmd == '') {
					$RSP['message'] = h('Service not informed', 5);
				} else {
					$RSP['message'] = h('Service not found - [' . $cmd . ']', 5);
				}
				break;
		}
		return $RSP;
	}

	function getAccess()
		{
			return true;
		}

	function forgout()
		{
			$email = get('email');
			$RSP = array();
			$RSP['status'] = '200';
			$RSP['email'] = $email;
			$RSP['message'] = 'Was send e-mail to ' . $email;
			$RSP['status'] = '200';

			/************* SEND MAIL */
			$dt = $this->where('us_email', $email)->first();
			if ($dt == []) {
				$RSP['message'] = 'User not found';
				$RSP['status'] = '404';
				return $RSP;
			} else {
				$link = md5($dt['us_apikey']);
				$RSP['message'] = base_url('social/recover_password/'.$link);
			}
			$RSP['data'] = $dt;

			return $RSP;
		}

	function access_denied()
		{
			$RSP = array();
			$RSP['message'] = 'Access Denied';
			$RSP['status'] = '403';
			return $RSP;
		}

	function akikey_create($id)
		{
			$apikey = md5('thesa' . $id . date("YmdHis").$id);
			$dd = [];
			$dd['us_apikey'] = $apikey;
			$dd['us_apikey_date'] = date("Y-m-d H:i:s");
			$dd['us_apikey_active'] = 1;
			$this->set($dd)->where('id_us', $id)->update();
			return $apikey;
		}
	function signin()
		{
			$RSP = array();
			$RSP['message'] = 'Sign In';
			$RSP['status'] = '200';
			$RSP['email'] = get("email");
			$RSP['password'] = get("password");

			$dt = $this->where('us_email', $RSP['email'])->first();
			if ($dt == []) {
				$RSP['message'] = 'User not found';
				$RSP['status'] = '404';
				return $RSP;
			} else {
				$ky1 = $dt['us_password'];
				$ky2 = md5('thesa'.$RSP['password']);
				if ($dt['us_apikey'] == '')
					{ $this->akikey_create($dt['id_us']); }
				if ($ky1 != $ky2) {
					$RSP['message'] = 'Password Error';
					$RSP['status'] = '404';
					return $RSP;
				} else {
					$RSP['message'] = 'User found';
					$RSP['status'] = '200';
					$RSP['id'] = $dt['id_us'];
					$RSP['name'] = $dt['us_nome'];
					$RSP['email'] = $dt['us_email'];
					$RSP['image'] = $dt['us_image'];
				}
			}
			return $RSP;
		}
}
