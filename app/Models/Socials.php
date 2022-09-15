<?php
# version: 0.21.07.30

namespace App\Models;

use CodeIgniter\Model;
use \app\Model\MainModel;

use function App\Models\AI\Authority\check;

class Socials extends Model
{
	protected $DBGroup              = 'default';
	var $table                		= 'users';
	var $primaryKey          		 = 'id_us';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	var $allowedFields        =
	[
		'id_us', 'us_nome', 'us_email',
		'us_image', 'us_genero', 'us_verificado',
		'us_login', 'us_password', 'us_autenticador',
		'us_oauth2', 'us_lastaccess'
	];

	var $typeFields        = [
		'hi', 'string:100*', 'string:100*',
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

	function user()
	{
		$sx = '';
		if (isset($_SESSION['user']['name'])) {
			$user = $_SESSION['user']['name'];
			$sx = '
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						' . $user . '
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="' . PATH . MODULE . '/social/perfil' . '">Perfil</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="' . PATH . MODULE . '/social/logout' . '">Logout</a>
						</div>
					</li>
				';
		}
		return ($sx);
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
		$sx = '';

		if (strlen($cmd) == 0) {
			$cmd = get("cmd");
		}

		switch ($cmd) {
			case 'session':
				if ($_SERVER['CI_ENVIRONMENT'] == 'development') {
					$id = 1;
					echo '<pre>';
					print_r($_SESSION);
					echo '</pre>';
					$newdata = [
						'id'  => $id,
						'email'     => 'Usuário Test (ADMIN)',
						'access' => $this->putPerfil(array(0 => '#ADM'), $id),
						'time' => time()
					];
					$session = session();
					$session->set($newdata);

					echo '<pre>';
					print_r($_SESSION);
					echo '</pre>';
				}
				break;
			case 'test':
				if ($_SERVER['CI_ENVIRONMENT'] == 'development') {
					$id = 1;

					$newdata = [
						'id'  => $id,
						'email'     => 'Usuário Test (ADMIN)',
						'access' => $this->putPerfil(array(0 => '#ADM'), $id)
					];
					$session = session();
					$session->set($newdata);
					$this->log_insert($id);
					echo metarefresh(PATH);
					exit;
				}
				$sx = bsmessage('Usuário não pode ser ativado no ambiente de produção');
				break;
			case 'login':
				$sx = $cab;
				$sx .= $this->login();
				break;
			case 'ajax':
				$sx = $this->ajax($id);
				break;
			case 'signin':
				$sx = $this->ajax($cmd);
				break;
			case 'signup':
				$sx = $this->ajax($cmd);
				break;
			case 'perfil':
				$sx .= $cab;
				$sx .= $this->perfil($id);
				break;

				/********************************************* USERS */
			case 'users':
				$sx .= $cab;
				switch ($id) {
					case 'viewid':
						$sx .= $this->perfil($dt);
						break;
					case 'edit':
						$sx .= bs(12);
						$sx .= h("Users - Editar", 1);
						$this->id = $dt;
						$this->path = '/social/users/';
						$this->path_back = '/social/users';
						$sx .= form($this);
						break;

					case 'delete':
						$sx .= h("Serviços - Excluir", 1);
						$this->id = $dt;
						form_del($this);
						$sx = metarefresh('/social/users');
						break;

					default:
						$sx .= h($id);
						$sx .= h($dt);
						$sx .= $this->users();
						break;
				}
				break;
				/********************************************* GROUPS */
			case 'groups':
				$sx .= $cab;
				$sx .= $this->groups();
				break;
			case 'group_useredit':
				$sx .= $cab;
				$sx .= $this->group_user_edit($id);
				break;

				/********************************************* PERFIS */
			case 'perfis':
				$sx .= $cab;
				$sx .= breadcrumbs();
				$sx .= $this->perfis($id, $dt);
				break;
			case 'perfis_add':
				$sx .= $cab;
				$sx .= breadcrumbs();
				$sx .= $this->perfis_add($id, $dt);
				break;
			case 'profile':
				$sx .= $cab;
				$sx .= $this->perfil_list();
				break;
			case 'view':
				$sx .= h("Usuários - View", 1);
				$this->id = $id;
				$dt =
					[
						'services' => $this->paginate(3),
						'pages' => $this->pager
					];
				$sx .= tableview($this, $dt);
				break;

			case 'logout':
				$sx = $this->logout();
				break;

			default:
				$sx = $cab;
				$sx .= breadcrumbs();
				$st =  h(lang('Social'), 1);
				$access = $this->getAccess('#ADM#GER');

				if (!$access) {
					$sx .= $this->access_denied();
					return $sx;
					exit;
				}

				if ($cmd == '') {
					$st .= h('Service not informed', 5);
				} else {
					$st .= h('Service not found - [' . $cmd . ']', 5);
				}

				if ($this->getAccess('#ADM#GER')) {
					$st .= $this->menu(9);
				}

				$sx .= bs(bsc($st, 12));

				break;
		}
		return $sx;
	}

	function menu($nivel = 0)
	{
		$menu = array();
		$menu['social/users'] = '/social.users_list';
		$menu['social/perfis'] = '/social.users_perfis';
		$menu['social/convert'] = '/social.users_convert';
		$sx = bs(bsc(bsmenu($menu), 12));
		return $sx;
	}

	function access_denied()
	{
		$sx = bsmessage(lang('social.access_denied'), 3);
		$sx = bs(bsc($sx, 12));
		return $sx;
	}

	function getID($t = '')
	{
		if (isset($_SESSION['id'])) {
			$id = round($_SESSION['id']);
		} else {
			$id = 0;
		}
		return $id;
	}
	function getAccess($t = '')
	{
		if (isset($_SESSION['id'])) {
			/************************************************************* Checa Admin */
			$user = trim($_SESSION['email']);
			if (($user == 'admin') or ($user == 'renefgj@gmail.com')) {
				return 1;
			}

			/********************************************* Check */
			$tp = explode('#', $t);

			for ($i = 0; $i < count($tp); $i++) {
				$ta = $this->calcMD5('#' . $tp[$i]);
				if (isset($_SESSION['access'])) {
					$ac = $_SESSION['access'];
					$ac = (array)json_decode($ac);
					for ($r = 0; $r < count($ac); $r++) {
						if ($ac[$r] == $ta) {
							return true;
						}
					}
				}
			}
		}
		return false;
	}

	function users()
	{
		$cmd = '';
		$id = 0;
		$url = geturl();
		$url = explode('/', $url);
		if (isset($url[5])) {
			$cmd = $url[5];
		}
		if (isset($url[6])) {
			$id = $url[6];
		}
		$this->path = PATH . MODULE . '/social/users';
		$this->path_back = PATH . MODULE . '/social/users';

		$sx = tableview($this);

		$sx = bs(bsc($sx, 12));
		return $sx;
	}

	function perfis_add($d1, $d2)
	{
		$this->setPerfilDb();
		$dt = $this->find($d1);
		$sx = $this->header_perfil($dt);

		$sf = form_open();
		$sf .= lang('social.user_name') . ' ' . lang('social.search');
		$sf .= '<div class="input-group mb-3">
					<input type="text" name="user.name" class="form-control" placeholder="' . lang('social.user_name') . '" aria-label="' . lang('social.user_name') . '" aria-describedby="basic-addon2">
					<div class="input-group-append">
						<input type="submit" class="btn btn-outline-primary" type="button" value="' . lang('social.search') . '">
					</div>
					</div>	';
		$sf .= form_close();
		$sx .= $sf;
		$sx = bs(bsc($sx), 12);


		/**************************************************************************** ADD */
		$assign = get("assign");
		$user = get("user");
		if ((strlen($assign) > 0) and (strlen($user) > 0)) {
			$check = md5($d1 . $user . date("Ymd"));
			if (($check == $assign) and (round($user) > 0)) {
				$check = md5($user . $d1);
				$sql = "select * from users_perfil_attrib where pa_user = '$user' and pa_perfil = '$d1' ";
				$dt = $this->db->query($sql)->getResult();
				if (count($dt) == 0) {
					$sql = "insert into users_perfil_attrib (pa_user, pa_perfil, pa_check) values ('$user','$d1','$check')";
					$dt = $this->db->query($sql);
				}
				return ($sx);
			} else {
				$sx = bsmessage("ERRO de CHECK", 3);
				return $sx;
			}
			exit;
		}
		/*************************************************************************** FIND */
		$name = get("user_name");
		if (strlen($name) > 0) {
			$this->setUserDb();
			$sql = "select * from users
								left join users_perfil_attrib ON pa_user = id_us
								where (us_nome like '%$name%') or (us_email like '%$name%')";
			$dt = $this->db->query($sql)->getResult();

			for ($r = 0; $r < count($dt); $r++) {
				$line = (array)$dt[$r];
				if ($line['id_pa'] == '') {
					$link = '<a href="' . base_url(PATH . MODULE . '/social/perfis_add/' . $d1 . '/') . '?user=' . $line['id_us'] . '&assign=' . md5($d1 . $line['id_us'] . date("Ymd")) . '">';
					$link .= lang('social.add_perfil');
					$link .= '</a>';
					$sx .= bsc($line['id_us'], 1);
					$sx .= bsc($line['us_nome'], 5);
					$sx .= bsc($line['us_email'], 5);
					$sx .= bsc($link, 1);
				} else {
					$sx .= bsc($line['id_us'], 1);
					$sx .= bsc($line['us_nome'], 5);
					$sx .= bsc($line['us_email'], 5);
					$sx .= bsc(lang('social.already_seted'), 1);
				}
			}
		}
		$sx = bs($sx);

		return $sx;
	}

	function perfis($cmd = '')
	{
		$cmd = '';
		$url = geturl();
		$url = explode('/', $url);
		if (isset($url[5])) {
			$cmd = $url[5];
		}
		if (isset($url[6])) {
			$id = $url[6];
		} else {
			$id = 0;
		}

		/****************************** Config DataBase */
		$this->setPerfilDB();
		$this->path = PATH . MODULE . '/social/perfis';

		switch ($cmd) {
			case 'viewid':
				$sx = h('viewid ->' . $id, 1);
				if ($id == 0) {
					return (metarefresh($this->path));
				}
				$sx .= $this->view_perfil_id($id);
				break;
			case 'edit':
				$this->id = $id;
				$this->path_back = PATH . MODULE . '/social/perfis';
				$sx = form($this);
				break;
			default:
				$sx = tableview($this);
				break;
		}
		$sx = bs(bsc($sx, 12));
		return $sx;
	}

	function setPerfilAtribDb()
	{
		$this->table = "users_perfil_attrib";
		$this->primaryKey = "id_pa";
		$this->allowedFields = ['id_pa', 'pa_user', 'pa_perfil', 'pa_check'];
	}

	function setPerfilDb()
	{
		$this->table = "users_perfil";
		$this->primaryKey = "id_pe";
		$this->allowedFields = ['id_pe', 'pe_abrev', 'pe_descricao', 'pe_nivel'];
		$this->typeFields = ['hidden', 'string:100', 'string:100', '[0-9]'];
	}

	function change_password($id)
	{
		$pw1 = get("password_old");
		$pw2 = get("password");
		$pw3 = get("password_confirm");

		$sx = 'Change Password';

		if (($pw1 != '') and ($pw2 != '') and ($pw3 != '')) {
			$sx = bsmessage(lang('social.password_change_ok'), 1);
		} else {
			$this->table = '*';
			$this->primaryKey = "id_pe";
			$this->allowedFields = ['id_us', 'password_old', 'password', 'password_confirm'];
			$this->typeFields = ['hidden', 'password', 'password', 'password'];
			$this->path = PATH . MODULE . '/social/perfil';
			$this->path_back = '#';
			$sx .= form($this);
		}



		return $sx;
	}

	function setUserDb()
	{
		$this->table = "users";
		$this->primaryKey = "id_us";
		$this->allowedFields =
			[
				'id_us', 'us_nome', 'us_email',
				'us_image', 'us_genero', 'us_verificado',
				'us_login', 'us_password', 'us_autenticador',
				'us_oauth2', 'us_lastaccess'
			];

		$this->typeFields =
			[
				'hi',
				'st100*',
				'st100*',
				'hi', 'hi', 'hi',
				'st50', 'hidden', 'hi',
				'hi', 'up'
			];
	}

	function header_perfil($dt)
	{
		$sx = '';
		$sx .= bsc(lang('social.perfil'), 10, 'small');
		$sx .= bsc(lang('social.abbrev'), 1, 'small');
		$sx .= bsc(h($dt['pe_descricao'], 1), 10);
		$sx .= bsc(h($dt['pe_abrev'], 4), 2);
		$sx .= bsc('<hr>', 12);
		$sx = bs($sx);
		return $sx;
	}

	function view_perfil_id($id)
	{
		$this->setPerfilDb();
		$dt = $this->find($id);
		$sx = $this->header_perfil($dt);
		$sx .= $this->view_perfil_members($id);
		return $sx;
	}

	function view_perfil_members($id)
	{
		$sx = '';
		$sx .= '<a href="' . PATH . MODULE . '/social/perfis_add/' . $id . '">' . lang('social.perfis.add.user') . '</a>';
		$this->setPerfilDb();
		$dt = $this
			->join('users_perfil_attrib', 'pa_perfil = id_pe')
			->join('users', 'pa_user = id_us', 'left')
			->where('id_pe', $id)
			->findAll();
		$sx .= '<table class="table">';
		$sx .= '<tr><th>#</th><th>' . lang('social.id_us') . '</th><th>' . lang('social.us_nome') . '</th></tr>';
		for ($r = 0; $r < count($dt); $r++) {
			$line = $dt[$r];
			$sx .= '<tr>';
			$sx .= '<td>' . ($r + 1) . '</td>';
			$sx .= '<td>' . $line['us_nome'] . '</td>';
			$sx .= '<td>' . $line['us_nome'] . '</td>';
			$sx .= '<td>' . $line['us_nome'] . '</td>';
			$sx .= '<td>' . $line['us_nome'] . '</td>';
			$sx .= '</tr>';
		}
		if (count($dt) == 0) {
			$sx .= '<tr><td colspan=5>' . bsmessage(lang('social.no_members'), 3) . '</td></tr>';
		}
		$sx .= '</table>';
		return $sx;
	}

	function ajax($cmd)
	{
		$rsp = array();
		$cmd = get("cmd");

		$rsp['status'] = '9';
		$rsp['message'] = 'service not found';
		switch ($cmd) {
			case 'test':
				$rsp['status'] = 1;
				$rsp['message'] = 'Teste OK';
				return json_encode($rsp);
				break;
			case 'signin':
				$rsp = $this->signin();
				return $rsp;
				break;
			case 'signup':
				$rsp = $this->signup();
				return $rsp;
				break;
			case 'forgout':
				$rsp = $this->forgout();
				return $rsp;
				break;
			default:
				$sx = 'Command not found - ' . $cmd;
				$sx .= '<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>';
				return $sx;
				break;
		}
	}

	function perfil($id = 0)
	{
		$sx = '';
		$id = round($id);
		if ($id == 0) {
			$id = $this->getID();
		}
		if ($id > 0) {
			$sx .= $this->perfil_show($id);
		}
		return $sx;
	}

	function perfil_show($id = 0)
	{
		$sx = '';
		if ($id > 0) {
			$dt = $this->Find($id);
			$sx .= breadcrumbs(array('social.home' => PATH . MODULE, '/social.perfil' => PATH . MODULE . '/social/perfil'));
			$sx .= $this->perfil_show_header($dt);
			$sx .= $this->my_library($dt);
			$logs = $this->logs($id);
			$rese = $this->my_reasearchs($id);
			$setings = $this->my_setings($id);
			$sx .= bs(
				bsc($setings, 4) .
					bsc($rese, 4) .
					bsc($logs, 4)
			);
			//$sx .= view('Socials/Pages/profile.php', $dt);
		} else {
			$sx = metarefresh(getenv("app.baseURL"));
			return $sx;
		}
		return bs($sx);
	}

	function image($id)
	{
		$dir = '_repository';
		dircheck($dir);
		$dir = '_repository/users/';
		dircheck($dir);
		$dir = '_repository/users/' . $id . '/';
		dircheck($dir);
		$filename = $dir . 'user.png';
		if (file_exists($filename)) {
			$img = '_repository/users/' . $id . '/user.png';
		} else {
			$img = '/img/genre/no_image_she_he.jpg';
		}
		return URL . $img;
	}

	function my_library($id)
	{
		$sx = '
				<div class="card-header pb-0 p-3">
            		<h6 class="mb-1">' . lang('Social.Access') . '</h6>
            		<p class="text-sm">' . lang('Social.Access_info') . '</p>
          		</div>
			';
		return $sx;
	}

	function group_user_add($gr = 0)
	{
		$id = get("id");
		$act = get("act");
		$chk = get("chk");

		if (($id != '') and ($act == 'add')) {
			$chk2 = md5($id . $_SESSION['id']);

			$sql = "select * from users_group_members
							where grm_library = " . LIBRARY . " and grm_group = $gr
							and grm_user = $id";
			$db = $this->db->query($sql);
			$us = $db->getResult();

			if (count($us) == 0) {
				$sql = "insert users_group_members
										(grm_group, grm_user, grm_library)
										values
										($gr, $id, " . LIBRARY . ")";
				$db = $this->db->query($sql);
				return '';
			}
		}

		$sx = '';
		$sx .= '<hr>';
		$sx .= h(lang('social.group_user_add'), 1);
		$sx .= form_open();
		$sx .= '<label for="exampleInputEmail1">' . lang('social.user_name') . '</label>';
		$sx .= '<div class="input-group mb-3">';
		$sx .= form_input(array('name' => 'search', 'class' => 'form-control', 'placeholder' => lang('social.group_user_add_name')));
		$sx .= form_submit(array('name' => 'action', 'value' => lang('social.user_find'), 'class' => 'btn btn-primary'));
		$sx .= '</div>';
		$sx .= form_close();

		$act = get("action");
		$search = get("search");
		if (($act != '') and ($search != '')) {
			$sx .= h(lang('social.users_found'), 5);
			$sql = "select * from users
								left join users_group_members ON grm_group = $gr AND grm_user = id_us
								where us_nome like '%$search%'
								order by us_nome";
			$db = $this->db->query($sql);
			$us = $db->getResult();

			$sx .= '<table class="table table-sm table-striped">';
			$sx .= '<tr>';
			$sx .= '<th width="45%">' . lang('social.user') . '</th>';
			$sx .= '<th width="45%">' . lang('social.email') . '</th>';
			$sx .= '<th width="10%">#</th>';
			$sx .= '</tr>';

			for ($r = 0; $r < count($us); $r++) {
				$line = (array)$us[$r];

				$pre = 'id=' . $line['id_us'] . '&act=add&chk=' . md5($line['id_us'] . $_SESSION['id']);
				$remove = '<a href="' . PATH . MODULE . '/social/group_useredit/' . $gr . '/' . $line['id_us'] . '?' . $pre . '" class="btn-outline-primary ps-2 pe-2 rounded">';
				$remove .= lang('social.user_add');
				$remove .= '</a>';
				$sx .= '<tr>';
				$sx .= '<td>' . $line['us_nome'] . '</td>';
				$sx .= '<td>' . $line['us_email'] . '</td>';
				if ($line['grm_group'] == '') {
					$sx .= '<td>' . $remove . '</td>';
				} else {
					$sx .= '<td><span class="text-primary">' . lang('social.already') . '</span></td>';
				}

				$sx .= '</tr>';
			}
			$sx .= '</table>';
		}
		return $sx;
	}

	function group_user_edit($gr = 0)
	{
		$user_add = $this->group_user_add($gr);

		$sx = '';
		$sx .= breadcrumbs();
		$sql = "select * from users_group
						where id_gr = $gr
						and gr_library = " . LIBRARY;
		$db = $this->db->query($sql);
		$dt = $db->getResult();

		$line = (array)$dt[0];

		$sx .= h($line['gr_name'], 2);
		$tp = $line['gr_hash'];

		/************************ Usuarios */
		$sql = "select * from users_group_members
						INNER JOIN users ON grm_user = id_us
						where grm_group = $gr
						and grm_library = " . LIBRARY . "
						order by us_nome";
		$db = $this->db->query($sql);
		$us = $db->getResult();

		$sx .= '<table class="table table-sm table-striped">';
		$sx .= '<tr>';
		$sx .= '<th width="45%">' . lang('Social.user') . '</th>';
		$sx .= '<th width="45%">' . lang('Social.email') . '</th>';
		$sx .= '<th width="10%">' . $tp . '</th>';
		$sx .= '</tr>';

		for ($r = 0; $r < count($us); $r++) {
			$line = (array)$us[$r];

			$pre = 'id=' . $line['id_grm'] . 'act=delete&chk=' . md5($line['id_grm'] . $_SESSION['id']);
			$remove = '<a href="' . PATH . MODULE . '/social/group_useredit/' . $gr . '/' . $line['id_us'] . '?' . $pre . '" class="btn-outline-danger ps-2 pe-2 rounded">';
			$remove .= lang('social.remove');
			$remove .= '</a>';
			$sx .= '<tr>';
			$sx .= '<td>' . $line['us_nome'] . '</td>';
			$sx .= '<td>' . $line['us_email'] . '</td>';
			$sx .= '<td>' . $remove . '</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';

		$sx .= $user_add;

		$sx .= '<div class="mt-5 mb-5" style="height: 100px;"></div>';

		$sx = bs(bsc($sx, 12));
		return $sx;
	}

	function groups($id = 0)
	{
		$sql = "select * from users_group
						LEFT JOIN users_group_members ON id_gr = grm_group
						LEFT JOIN users ON grm_user = id_us
						where gr_library = " . LIBRARY . "
						order by gr_name, us_nome";
		$db = $this->db->query($sql);
		$dt = $db->getResult();

		$sx = '';
		$xgr = '';
		$ed = $this->getAccess("#ADM");
		for ($r = 0; $r < count($dt); $r++) {
			$line = (array)$dt[$r];
			$gr = $line['gr_name'];
			if ($gr != $xgr) {
				$link = '<a href="' . PATH . MODULE . '/social/group_useredit/' . $line['id_gr'] . '" title="' . lang("social.group_user_edit") . '">';
				$linka = '</a>';
				$edi = ' ' . $link . bsicone('user+', 32) . $linka;
				$sx .= h($gr . $edi, 3, 'mt-3');
				$xgr = $gr;
			}
			$name = $line['us_nome'];
			if (strlen($name) > 0) {
				$link = '<a href="' . PATH . MODULE . '/social/perfil/' . $line['id_us'] . '">';
				$linka = '</a>';
				$sx .= $link . $name . $linka . '. ';
			}
		}
		$sx = bs(bsc($sx, 12));
		return $sx;
	}

	/*************************************************** SEYYINGS */
	function my_setings($id)
	{
		$sx = '
			<div class="card h-100">
					<div class="card-header pb-0 p-3">
						<h6 class="mb-0">' . lang('social.my_settings') . '</h6>
					</div>
					<div class="card-body p-3">
						<h6 class="text-uppercase text-body text-xs font-weight-bolder">' . lang('social.my_settings') . '</h6>
						<ul class="list-group">';
		$sx .= '<li class="list-group-item border-0 px-0">' . lang('social.my_settings_info') . '</li>';
		$sx .= $this->change_password($id);
		$sx .= '
						</ul>
					</div>
					</div>
					';
		return $sx;
	}


	/*************************************************** RESEARSH */
	function my_reasearchs($id)
	{
		$sx = '
			<div class="card h-100">
					<div class="card-header pb-0 p-3">
						<h6 class="mb-0">' . lang('social.researchs_register') . '</h6>
					</div>
					<div class="card-body p-3">
						<h6 class="text-uppercase text-body text-xs font-weight-bolder">' . lang('social.research') . '</h6>
						<ul class="list-group">';
		$sx .= '<li class="list-group-item border-0 px-0">' . lang('social.research_info') . '</li>';
		$sx .= '
						</ul>
					</div>
					</div>
					';
		return $sx;
	}

	/*************************************************** LOGS */

	function log_insert($id)
	{
		$ip = ip();
		$sql = "insert into users_log
				(ul_user, ul_ip)
				values
				($id,'$ip')";
		$dt = $this->db->query($sql);
	}

	function logs($id)
	{
		if (is_array($id)) {
			$id = $id['id_us'];
		}
		$sx = '
			<div class="card h-100">
					<div class="card-header pb-0 p-3">
						<h6 class="mb-0">' . lang('social.Logs_register') . '</h6>
					</div>
					<div class="card-body p-3">
						<h6 class="text-uppercase text-body text-xs font-weight-bolder">' . lang('social.logs') . '</h6>
						<ul>';

		$sql = "select * from users_log
					where ul_user = $id
					order by id_ul desc limit 10";
		$dt = $this->db->query($sql)->getResult();

		if (count($dt) == 0) {
			$sx .= '<li class="list-group-item border-0 px-0">
							<div class="form-check form-switch ps-0">
								<span class="text-warning">' . lang('social.no_logs') . '</span>
							</div>
						</li>';
		} else {
			for ($r = 0; $r < count($dt); $r++) {
				$line = (array)$dt[$r];
				$hora = $line['ul_access'];
				$hora = substr($hora, strlen($hora) - 8, 10);
				$sx .= '
						<li class="list-group-item border-0 px-0">
							' . stodbr(sonumero($line['ul_access'])) . '
							' . $hora . '
							(' . $line['ul_ip'] . ')
						</li>';
			}
		}
		$sx .= '
						</ul>
					</div>
					</div>
					';
		return $sx;
	}

	function perfil_show_header($dt)
	{
		$sx = '
			<div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
			<div class="row gx-4">
					<div class="col-auto">
					<div class="avatar avatar-xl position-relative">
						<img src="' . $this->image($dt['id_us']) . '" alt="profile_image" style="height: 100px;" class="shadow-sm img-fluid img-thumbnail">
					</div>
					</div>
					<div class="col-auto my-auto">
					<div class="h-100">
						<h5 class="mb-1">' . $dt['us_nome'] . '</h5>
						<p class="mb-0 font-weight-bold text-sm">' . $dt['us_login'] . '</p>
					</div>
					</div>
					<div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
					<div class="nav-wrapper position-relative end-0">
						<ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
						<li class="nav-item">
							<a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="http://brapci3/index.php/social/message" role="tab" aria-selected="false">
							<svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
								<title>document</title>
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF" fill-rule="nonzero">
									<g transform="translate(1716.000000, 291.000000)">
									<g transform="translate(154.000000, 300.000000)">
										<path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" opacity="0.603585379"></path>
										<path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z">
										</path>
									</g>
									</g>
								</g>
								</g>
							</svg>
							<span class="ms-1">Messages</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="http://brapci3/index.php/social/message" role="tab" aria-selected="true">
							<svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
								<title>settings</title>
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
									<g transform="translate(1716.000000, 291.000000)">
									<g transform="translate(304.000000, 151.000000)">
										<polygon class="color-background" opacity="0.596981957" points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667">
										</polygon>
										<path class="color-background" d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z" opacity="0.596981957"></path>
										<path class="color-background" d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z">
										</path>
									</g>
									</g>
								</g>
								</g>
							</svg>
							<span class="ms-1">Settings</span>
							</a>
						</li>
						</ul>
					</div>
					</div>
				</div>
				</div>
				';
		return $sx;
	}

	function signin()
	{
		$sx = '';
		$user = get("user");
		$pwd = get("pwd");
		$dt = $this->user_exists($user);

		if (isset($dt[0])) {
			if ($dt[0]['us_password'] == md5($pwd)) {
				$_SESSION['id'] = $dt[0]['id_us'];
				$_SESSION['user'] = $dt[0]['us_nome'];
				$_SESSION['email'] = $dt[0]['us_email'];
				$_SESSION['access'] = substr(md5('#ADMIN'), 6, 6);
				$_SESSION['check'] = substr($_SESSION['id'] . $_SESSION['id'], 0, 10);

				$sx .= '<h2>' . lang('social.success') . '<h2>';
				$sx .= '<meta http-equiv="refresh" content="2;URL=\'' . PATH . COLLECTION . '\'">';
				$this->log_insert($dt[0]['id_us']);
			} else {
				$sx .= '<h2>' . lang('ERROR') . '<h2>';
				$sx .= '<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>';
			}
		} else {
			$sx .= '<h2>' . lang('social.user_error') . '<h2>';
			$sx .= '<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>';
		}
		return $sx;
	}

	function forgout()
		{
			$email = get("email");
			$dt = $this->where('us_email',$email)->findAll();

			if (count($dt) == 0)
				{
					$sx = '<h2>'.lang('Socials.email_not_found').'</h2>';
					$sx .= '<span class="psw" onclick="showForgotPassword()">Voltar</span>';
					echo $sx;
					exit;
				}
			$sx = '<h2>' . lang('social.email_send_your_account') . '</h2>';
			$sx .= '<small>'.lang('social.forgout_info').'</small>';
			$sx .= '<span class="psw" onclick="showLogin()()">'.lang('social.return_login').'</span>';
			echo $sx;
			//sendemail();

		}

	function signup()
	{
		$sx = '';
		$user = get("signup_email");
		$name = get("signup_name");
		$inst = get("signup_institution");

		if (!check_email($user)) {
			$sx .= '<h2>' . lang('social.email_invalid') . '<h2>';
			$sx .= '<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>';
			return $sx;
		}

		$dt = $this->user_exists($user);

		if (!isset($dt[0])) {
			$this->user_add($user, $name, $inst);
			$sx .= '<h2>' . lang('social.social_user_add_success') . '<h2>';
			$sx .= '<hr>';
			$sx .= '<h2>' . lang('social.social_check_you_email') . '<h2>';
			$sx .= '<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>';
		} else {
			$sx .= '<h2>' . lang('social.user_already') . '<h2>';
			$sx .= '<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>';
		}
		return $sx;
	}

	function user_add($user, $name, $inst)
	{
		$pw1 = substr(md5($user), 0, 6);
		$data = [
			'us_email' => $user,
			'us_nome' => $name,
			'us_affiliation' => $inst,
			'us_password'  => md5($pw1),
			'us_autenticador' => 'MD5'
		];
		$this->insert($data);
	}

	function user_exists($email = '')
	{
		$dt = array();
		if (strlen($email) > 0) {
			$dt = $this->where('us_email', $email)->findAll();
		}
		return $dt;
	}


	function logout()
	{
		$session = \Config\Services::session();
		$url = \Config\Services::url();
		$session->destroy();
		helper('url');
		//redirect(PATH.MODULE, 'refresh');
		return redirect()->to('/');
	}

	function nav_user()
	{
		$sx = '';
		if ($this->loged()) {
			$email = $_SESSION['email'];
			$sx = '';
			$sx .= '<ul class="navbar-nav ml-auto" >';
			$sx .= '        <li class="nav-item dropdown ml-auto">' . cr();
			$sx .= '          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . cr();
			$sx .= '            ' . $email . cr();
			$sx .= '          </a>' . cr();
			$sx .= '          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">' . cr();
			$sx .= '            <li><a class="dropdown-item" href="' . (PATH . MODULE . '/social/?cmd=perfil') . '">' . lang('social.perfil') . '</a></li>' . cr();
			$sx .= '            <li><a class="dropdown-item" href="' . (PATH . MODULE . '/social/?cmd=logout') . '">' . lang('social.logout') . '</a></li>' . cr();
			$sx .= '          </ul>' . cr();
			$sx .= '        </li>' . cr();
			$sx .= '</ul>' . cr();
		} else {
			$sx .= '<li class="nav-item d-flex align-items-center">';
			$sx .= '
              		<a href="' . (PATH . MODULE . '/social/login') . '" class="nav-link text-body font-weight-bold px-0">
                	<i class="fa fa-user me-sm-1"></i>
                	<span class="d-sm-inline d-none">' . lang('social.social_sign_in') . '</span></a>';
			$sx .= '</li>';
		}
		return $sx;
	}
	function loged()
	{
		if ((isset($_SESSION['id'])) and ($_SESSION['id'] != '')) {
			$id = $_SESSION['id'];
			return ($id);
		} else {
			return (0);
		}
	}

	function login($err = '')
	{
		global $msg;
		if ($this->loged()) {
			$sx = '<div>';
			$sx .= 'LOGADO';
			$sx .= '</div>';
			return $sx;
		}

		$err = get("erro");

		$bk = '#0093DD';
		$bknav = '#FFFFFF';

		$sx = '';
		$sx .= '
		<style>
		* {
		  box-sizing: border-box;
		}

		body {
		  font-family: Tahoma, Verdana, Segoe, sans-serif;
		  font-size: 14px;
		  text-align: center;
		}

		.wrapper {
		  width: 250px;
		  height: 350px;
		  margin: 30px auto;
		  perspective: 600px;
		  text-align: left;
		}

		.rec-prism {
		  width: 100%;
		  height: 100%;
		  position: relative;
		  transform-style: preserve-3d;
		  transform: translateZ(-100px);
		  transition: transform 0.5s ease-in;
		}

		.face {
		  position: absolute;
		  width: 250px;
		  height: 350px;
		  padding: 20px;
		  background: rgba(250, 250, 250, 0.96);
		  border: 3px solid ' . $bk . ';
		  border-radius: 3px;
		}
		.face .content {
		  color: #666;
		}
		.face .content h2 {
		  font-size: 1.2em;
		  color: ' . $bk . ';
		}
		.face .content .field-wrapper {
		  margin-top: 30px;
		  position: relative;
		}
		.face .content .field-wrapper label {
		  position: absolute;
		  pointer-events: none;
		  font-size: 0.85em;
		  top: 40%;
		  left: 0;
		  transform: translateY(-50%);
		  transition: all ease-in 0.25s;
		  color: #999999;
		}
		.face .content .field-wrapper input[type=text], .face .content .field-wrapper input[type=password], .face .content .field-wrapper input[type=submit], .face .content .field-wrapper textarea {
		  -webkit-appearance: none;
		  appearance: none;
		}
		.face .content .field-wrapper input[type=text]:focus, .face .content .field-wrapper input[type=password]:focus, .face .content .field-wrapper input[type=submit]:focus, .face .content .field-wrapper textarea:focus {
		  outline: none;
		}
		.face .content .field-wrapper input[type=text], .face .content .field-wrapper input[type=password], .face .content .field-wrapper textarea {
		  width: 100%;
		  border: none;
		  background: transparent;
		  line-height: 2em;
		  border-bottom: 1px solid ' . $bk . ';
		  color: #666;
		}
		.face .content .field-wrapper input[type=text]::-webkit-input-placeholder, .face .content .field-wrapper input[type=password]::-webkit-input-placeholder, .face .content .field-wrapper textarea::-webkit-input-placeholder {
		  opacity: 0;
		}
		.face .content .field-wrapper input[type=text]::-moz-placeholder, .face .content .field-wrapper input[type=password]::-moz-placeholder, .face .content .field-wrapper textarea::-moz-placeholder {
		  opacity: 0;
		}
		.face .content .field-wrapper input[type=text]:-ms-input-placeholder, .face .content .field-wrapper input[type=password]:-ms-input-placeholder, .face .content .field-wrapper textarea:-ms-input-placeholder {
		  opacity: 0;
		}
		.face .content .field-wrapper input[type=text]:-moz-placeholder, .face .content .field-wrapper input[type=password]:-moz-placeholder, .face .content .field-wrapper textarea:-moz-placeholder {
		  opacity: 0;
		}
		.face .content .field-wrapper input[type=text]:focus + label, .face .content .field-wrapper input[type=text]:not(:placeholder-shown) + label, .face .content .field-wrapper input[type=password]:focus + label, .face .content .field-wrapper input[type=password]:not(:placeholder-shown) + label, .face .content .field-wrapper textarea:focus + label, .face .content .field-wrapper textarea:not(:placeholder-shown) + label {
		  top: -35%;
		  color: #42509e;
		}
		.face .content .field-wrapper input[type=submit] {
		  -webkit-appearance: none;
		  appearance: none;
		  cursor: pointer;
		  width: 100%;
		  background: ' . $bk . ';
		  line-height: 2em;
		  color: #fff;
		  border: 1px solid ' . $bk . ';
		  border-radius: 3px;
		  padding: 5px;
		}
		.face .content .field-wrapper input[type=submit]:hover {
		  opacity: 0.9;
		}
		.face .content .field-wrapper input[type=submit]:active {
		  transform: scale(0.96);
		}
		.face .content .field-wrapper textarea {
		  resize: none;
		  line-height: 1em;
		}
		.face .content .field-wrapper textarea:focus + label, .face .content .field-wrapper textarea:not(:placeholder-shown) + label {
		  top: -25%;
		}
		.face .thank-you-msg {
		  position: absolute;
		  width: 200px;
		  height: 130px;
		  text-align: center;
		  font-size: 2em;
		  color: ' . $bk . ';
		  left: 50%;
		  top: 50%;
		  -webkit-transform: translate(-50%, -50%);
		}
		.face .thank-you-msg:after {
		  position: absolute;
		  content: "";
		  width: 50px;
		  height: 25px;
		  border: 10px solid ' . $bk . ';
		  border-right: 0;
		  border-top: 0;
		  left: 50%;
		  top: 50%;
		  -webkit-transform: translate(-50%, -50%) rotate(0deg) scale(0);
		  transform: translate(-50%, -50%) rotate(0deg) scale(0);
		  -webkit-animation: success ease-in 0.15s forwards;
		  animation: success ease-in 0.15s forwards;
		  animation-delay: 2.5s;
		}
		.face-front {
		  transform: rotateY(0deg) translateZ(125px);
		}
		.face-top {
		  height: 250px;
		  transform: rotateX(90deg) translateZ(125px);
		}
		.face-back {
		  transform: rotateY(180deg) translateZ(125px);
		}
		.face-right {
		  transform: rotateY(90deg) translateZ(125px);
		}
		.face-left {
		  transform: rotateY(-90deg) translateZ(125px);
		}
		.face-bottom {
		  height: 250px;
		  transform: rotateX(-90deg) translateZ(225px);
		}

		.nav {
		  padding: 0;
		  text-align: center;
		}

		.nav li {
		  display: inline-block;
		  list-style-type: none;
		  font-size: 1em;
		  margin: 0 10px;
		  color: ' . $bknav . ';
		  position: relative;
		  cursor: pointer;
		}
		.nav li:after {
		  content: "";
		  position: absolute;
		  bottom: 0;
		  left: 0;
		  width: 20px;
		  border-bottom: 1px solid ' . $bknav . ';
		  transition: all ease-in 0.25s;
		}
		.nav li:hover:after {
		  width: 100%;
		}

		.psw, .signup, .singin {
		  display: block;
		  margin: 10px 0;
		  font-size: 0.75em;
		  text-align: center;
		  color: #42509e;
		  cursor: pointer;
		}

		small {
		  font-size: 0.7em;
		}

		@-webkit-keyframes success {
		  from {
			-webkit-transform: translate(-50%, -50%) rotate(0) scale(0);
		  }
		  to {
			-webkit-transform: translate(-50%, -50%) rotate(-45deg) scale(1);
		  }
		}
		</style>

		<script>
		  window.console = window.console || function(t) {};
		  if (document.location.search.match(/type=embed/gi)) {
			window.parent.postMessage("resize", "*");
		  }
		</script>
		<ul class="nav center" style="margin: 0% 20%; display: none;">
		<li onclick="showLogin()">' . lang('social.social_login') . '</li>
		<li onclick="showSignup()">' . lang('social.social_sign_up') . '</li>
		<li onclick="showForgotPassword()">' . lang('social.social_forgot_password') . '</li>
		<li onclick="showSubscribe()">' . lang('social.social_subscrime') . '</li>
		<li onclick="showContactUs()">' . lang('social.social_contact_us') . '</li>
		</ul>

		<div class="wrapper">
		  <div class="rec-prism">
		    <!--- BOARD ----------------------------------------------->
			<div class="face face-top">
			  <div class="content">
				<h2>' . lang('social.social_message') . '</h2>
				<small>' . lang('social.social_message_inf') . '</small>
				<h3 style="color: red;">' . $err . '</h3>

				<span class="singin" onclick="showLogin()">' . lang('social.return') . '</span>
			  </div>
			</div>
			<!---- SIGN IN-------------------------------------------->
			<div class="face face-front">
			  <div class="content">
				<h2>' . lang('social.social_sign_in') . '</h2>
				  <div class="field-wrapper">
					<input type="text" id="user_login" placeholder="' . lang('social.user_login') . '" value="' . get("user_login") . '">
					<label>' . lang('social.social_type_login') . '</label>
				  </div>
				  <div class="field-wrapper">
					<input type="password" id="user_password" placeholder="' . lang('social.user_password') . '" autocomplete="new-password">
					<label>' . lang('social.social_type_password') . '</label>
				  </div>
				  <div class="field-wrapper">
				    <button class="btn btn-primary" style="width: 100%;" onclick="action_ajax(\'signin\');">' . lang('social.enter') . '</button>
				  </div>
				  <span class="psw" onclick="showForgotPassword()">' . lang('social.social_forgot_password') . '</span>
				  <span class="signup" onclick="showSignup()">' . lang('social.social_not_user') . '  ' . lang('social.social_sign_up') . '</span>
				  <span class="signup" onclick="showContactUs()">' . lang('social.social_questions') . '</span>
			  </div>
			</div>
			<!-- FORGOT --------------------------------------------->
			<div class="face face-back">
			  <div class="content">
				<h2>' . lang('social.social_forgot_password') . '</h2>
				<small>' . lang('social.social_forgot_password_info') . '</small>
				<form onsubmit="event.preventDefault()">
				  <div class="field-wrapper">
					<input type="text" name="email" id="email" placeholder="email">
					<label>e-mail</label>
				  </div>
				  <div class="field-wrapper">
					<button class="btn btn-primary" style="width: 100%;" onclick="action_ajax(\'forgout\');">' . lang('social.enter') . '</button>
				  </div>
				  <span class="singin" onclick="showLogin()">' . lang('social.social_alread_user') . '  ' . lang('social.social_sign_in') . '</span>
				</form>
			  </div>
			</div>
			<!-- SIGN UP -------------------------------------------->
			<div class="face face-right">
			  <div class="content">
				<h2>' . lang('social.social_sign_up') . '</h2>
				  <div class="field-wrapper">
					<input type="text" id="signup_name" placeholder="name">
					<label>' . lang('social.user_name') . '</label>
				  </div>
				  <div class="field-wrapper">
					<input type="text" id="signup_email" placeholder="name">
					<label>' . lang('social.signup_email') . '</label>
				  </div>
				 <div class="field-wrapper">
					<input type="text" id="signup_institution" placeholder="name">
					<label>' . lang('social.signup_institution') . '</label>
				  </div>
				  <div class="field-wrapper">
					<button class="btn btn-primary" style="width: 100%;" onclick="action_ajax(\'signup\');">' . lang('social.signup') . '</button>
				  </div>
				  <span class="singin" onclick="showLogin()">' . lang('social.social_alread_user') . '  ' . lang('social.social_sign_in') . '</span>
			  </div>
			</div>
			<!-- Contact US ------------------------------------------>
			<div class="face face-left">
			  <div class="content">
				<h2>' . lang('social.social_contact_us') . '</h2>
				<form onsubmit="event.preventDefault()">
				  <div class="field-wrapper">
					<input type="text" name="name" placeholder="name">
					<label>' . lang('social.social_name') . '</label>
				  </div>
				  <div class="field-wrapper">
					<input type="text" name="email" placeholder="email">
					<label>e-mail</label>
				  </div>
				  <div class="field-wrapper">
					<textarea placeholder="' . lang('social.social_yourmessage') . '" rows=3></textarea>
					<label>' . lang('social.social_yourmessage') . '</label>
				  </div>
				  <div class="field-wrapper">
					<input type="submit" onclick="showThankYou()">
				  </div>
				  <span class="singin" onclick="showLogin()">' . lang('social.social_alread_user') . '  ' . lang('social.social_sign_in') . '</span>
				</form>
			  </div>
			</div>
			<!-- Contact US ------------------------------------------>
			<div class="face face-bottom">
			  <div class="content">
			  		<div id="board">
					<h2>' . lang('social.conecting') . '</h2>
					</div>
				</div>
			  </div>
			</div>
		  </div>
		</div>

		<script id="rendered-js" >
		let prism = document.querySelector(".rec-prism");

		function await(ms){
			var start = new Date().getTime();
			var end = start;
			while(end < start + ms) {
				end = new Date().getTime();
			}
		}

		function ajax(cmd)
			{
			var data = new FormData();
			data.append("cmd", cmd);
			if (cmd == "forgout")
				{
					data.append("email", document.getElementById("email").value);
				}
			if (cmd == "signin")
				{
					data.append("user", document.getElementById("user_login").value);
					data.append("pwd", document.getElementById("user_password").value);
				}
			if (cmd == "signup")
				{
					data.append("signup_email", document.getElementById("signup_email").value);
					data.append("signup_name", document.getElementById("signup_name").value);
					data.append("signup_institution", document.getElementById("signup_institution").value);
				}

            var url = "' . PATH . MODULE . '/social/ajax/"+cmd;
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", url, false);
            xhttp.send(data);

            	document.getElementById("board").innerHTML = xhttp.responseText;
			 	console.log(this.responseText);
			}

		function showSignup() {
		  prism.style.transform = "translateZ(-100px) rotateY( -90deg)";
		}
		function showLogin() {
		  prism.style.transform = "translateZ(-100px)";
		}
		function showForgotPassword() {
		  prism.style.transform = "translateZ(-100px) rotateY( -180deg)";
		}

		function showSubscribe() {
		  prism.style.transform = "translateZ(-100px) rotateX( -90deg)";
		}

		function showContactUs() {
		  prism.style.transform = "translateZ(-100px) rotateY( 90deg)";
		}

		function showThankYou() {
		  prism.style.transform = "translateZ(-100px) rotateX( 90deg)";
		}

		function action_ajax($cmd)
			{
				prism.style.transform = "translateZ(-100px) rotateX( 90deg)";
				ajax($cmd);
			}
		//# sourceURL=pen.js
		';
		if (strlen($err) > 0) {
			$sx .= '
				(function() {
					await(500); showSubscribe();
				})();
				';
		}
		$sx .= '</script>';

		return ($sx);
	}
}