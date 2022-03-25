<?php

namespace App\Models\Io;

use CodeIgniter\Model;

class Files extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'files';
	protected $primaryKey           = 'id';
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

	function load($url, $type = 'curl')
	{
		$type = mb_strtolower($type);
		switch($type)
			{
				case 'curl':
					$rst = $this->load_curl($url);
					return $rst;
					break;
				default:
					echo 'TYPE not valid '.$type;
					exit;
			}
	}
	function load_curl($url)
	{
		if (strlen($url) == 0)
			{
				echo "erro URL: $url";
				exit;
			}
		$options = array(
			CURLOPT_RETURNTRANSFER => true, // return web page
			CURLOPT_HEADER => false, // don't return headers
			CURLOPT_FOLLOWLOCATION => true, // follow redirects
			CURLOPT_ENCODING => "", // handle all encodings
			CURLOPT_USERAGENT => "spider", // who am i
			CURLOPT_AUTOREFERER => true, // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
			CURLOPT_TIMEOUT => 120, // timeout on response
			CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
		);

		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($ch);
		$err = curl_errno($ch);
		$errmsg = curl_error($ch);
		$header = curl_getinfo($ch);
		curl_close($ch);

		$header['errno'] = $err;
		$header['errmsg'] = $errmsg;
		$header['content'] = $content;
		return $header;
	}


	function upload($id = '', $tpy = '')
	{
		$data = $this->frbr_core->le_data($id);
		for ($r = 0; $r < count($data); $r++) {
			$attr = trim($data[$r]['c_class']);
			$vlr = trim($data[$r]['n_name']);

			if ($attr == 'prefLabel') {
				$file = trim($vlr);
				$file = troca($file, '/', '_');
				$file = troca($file, '.', '_');
				$file = troca($file, ':', '_');
			}
		}

		$sx = '
			<h1>' . msg('upload_file') . '</h1>
			<form method="post" enctype="multipart/form-data">
			Select image to upload:
			<input type="file" name="fileToUpload" id="fileToUpload">
			<input type="submit" value="Upload Image" name="submit">
			</form>
			';

		// Check if image file is a actual image or fake image
		if (isset($_POST["submit"])) {
			$target_dir = "uploads/";
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
			$check = (lowercase(substr($imageFileType, strlen($imageFileType) - 3, 3)) == 'pdf');
			if ($check !== false) {
				$namef = $_FILES["fileToUpload"]["tmp_name"];
				$txt = '';

				$myfile = fopen($namef, "r") or die("Unable to open file!");
				$txt .= fread($myfile, filesize($namef));
				fclose($myfile);

				$this->file_pdf($file, $txt, $id, 0);
				$uploadOk = 1;
				$sx .= '<script> wclose(); </script>';
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
		}
		return ($sx);
	}

	function directory($id)
	{
		$file = str_pad($id, 9, '0', STR_PAD_LEFT);
		$dir[0] = '../.c';
		$dir[1] = substr($file, 0, 3);
		$dir[2] = substr($file, 3, 2);
		$dir[3] = substr($file, 5, 2);
		$dir[4] = substr($file, 7, 2);
		$dr = '';
		for ($r = 0; $r < count($dir); $r++) {
			$dr .= $dir[$r] . '/';
			dircheck($dr);
		}
		return $dr;
	}
}
