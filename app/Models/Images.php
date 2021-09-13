<?php

namespace App\Models;

use CodeIgniter\Model;

class Images extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = '';
	protected $primaryKey           = '';
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

	var $maxsize = 500;

	function resize($filename,$maxw=0,$maxh=0)
		{
			list($width, $height) = getimagesize($filename);

			echo '=W=>'.$width.'<br>';
			echo '=H=>'.$height.'<br>';
		}

	function photo($id,$path='_repositorio/users')
		{
			$id = str_pad($id,5,'0',STR_PAD_LEFT);
			$file = $path.$id;
			$file = base_url('img/other/face_no_picture.png');
			return $file;
		}

    function photo_take($name,$path='_repositorio/users')
        {
            $take_picture = 'Tirar foto';
            $take_noimage = 'Sem imagem';

            $p = explode('/',$path);
            $tmp = '';
            for ($r=0;$r < count($p); $r++)
                {
                    $tmp .= $p[$r].'/';
                    dircheck($tmp);
                }
            $tmp .= $name;      
            if (strpos($tmp,'.jpg') > 0)
            {
                /* imagem jpg */
            } else {
                $tmp .= '.jpg';
            }

            if (isset($_POST['base_img']))
                {
                    $img = $_POST['base_img'];
                    $img = str_replace(" ","+",$img);
                    $img = explode(',', $img);
                    $img = base64_decode(trim($img[1]));
                    /* Renomear foto */
                    file_put_contents($tmp,$img);
                }
            $img_mst = bsmessage(msg('no_image'),3);
            if (file_exists($tmp))
                {
                    $img_mst = '
                            <img id="imagemConvertida" src="'.base_url($tmp.'?v='.time()).'" class="img-fluid"/>            
                            <p>'.$tmp.'</p>
                        ';
                }
            
            $sx = '            
            <div class="col-md-6 col-sm-12 col-12">
			<video autoplay="true" id="webCamera" class="webcam_video"></video>
                <form method="post">
    			    <textarea id="base_img" name="base_img" style="display: none;"/></textarea>
			        <button class="btn btn-outline-primary" style="width: 100%;" type="button" onclick="takeSnapShot(); submit();">'.msg('take_picture').'</button>	
                </form>
            </div>
            <div class="col-md-6 col-sm-12 col-12">'.$img_mst.'</div>
            </div>';

        $css = '
        <style>
        .webcam_video{
            width: 100%;
            height: auto;
            background-color: whitesmoke;
        }
        </style>
        ';

        $js = '
        <script>
            function loadCamera(){
                //Captura elemento de vídeo
                var video = document.querySelector("#webCamera");
                    //As opções abaixo são necessárias para o funcionamento correto no iOS
                    video.setAttribute(\'autoplay\', \'\');
                    video.setAttribute(\'muted\', \'\');
                    video.setAttribute(\'playsinline\', \'\');
                    //--
                
                //Verifica se o navegador pode capturar mídia
                if (navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({audio: false, video: {facingMode: \'user\'}})
                    .then( function(stream) {
                        //Definir o elemento vídeo a carregar o capturado pela webcam
                        video.srcObject = stream;
                    })
                    .catch(function(error) {
                        alert("Oooopps... Falhou!");
                    });
                }
            }  

            function takeSnapShot(){
                //Captura elemento de vídeo
                var video = document.querySelector("#webCamera");
                
                //Criando um canvas que vai guardar a imagem temporariamente
                var canvas = document.createElement(\'canvas\');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                var ctx = canvas.getContext(\'2d\');
                
                //Desenhando e convertendo as dimensões
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                //Criando o JPG
                var dataURI = canvas.toDataURL(\'image/jpeg\'); 
                //O resultado é um BASE64 de uma imagem.
                document.querySelector("#base_img").value = dataURI;                
            }
            loadCamera();
        </script>
        ';            
    return($sx . $js . $css);
    }

	function check($dir)
		{
			$tot = 0;
			$toti = 0;
			$max = $this->maxsize;
			$files = scandir($dir);
			$sx = '<h1>Images Check</h1>';
			$sx .= 'Max width: '.$max.'px<br>';
			$sx .= 'Total of files: '.count($files).'<br>';
			$sx .= '<ol>';
			foreach($files as $id => $filename)
				{
					if (($filename == '.') or ($filename == '..'))
						{
							// Ignore
						} else {
							$rst = ' ';
							$file = $dir.$filename;
							$fileo = $dir.'_'.$filename;
							$i = getimagesize($file);
							$width = $i[0];
							$height = $i[1];
							$mime = $i['mime'];
							$rst .= "($width x $height) - ".$mime;
							$rst .= ' '.number_format(filesize($file)/1024,1,',','.').'k Bytes';
							//echo $rst;
							$sit = '<span>';
							if ($width != $max) 
								{
									$sit = '<span style="color: red; weigth: bold;">';
									$sx .= '<li>'.$sit.$filename.$rst.'</span></li>';

									$newwidth = $max;
									if ($width == 0)
										{
											$rst .= 'ERRO NO ARQUIVO ';
											break;
										}
									$newheight = round(($height * $max) / $width);
									switch($mime)
										{
											case 'image/png':
												$source = imagecreatefrompng($file);
												break;
											case 'image/webp':
												$source = imagecreatefromwebp($file);
												break;
											case 'image/gif':
												$source = imagecreatefromgif($file);
												break;																								
											case 'image/bmp':
												$source = imagecreatefrombmp($file);
												break;												
	
											default:
											$source = imagecreatefromjpeg($file);
											break;
										}
									
									$thumb = imagecreatetruecolor($newwidth, $newheight);
									imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
									imagedestroy($source);
									imagejpeg($thumb,$file,100);
									imagedestroy($thumb);
									$tot++;
								}							
						}	
					$toti++;				
					if ($tot >= 25) { $sx .= bsmessage('have more...'.$toti.'/'.count($files)); break; }
				}
			$sx .= '</ol>';
			if ($tot > 0)
				{
					$sx .= metarefresh('',1);
				}
			return $sx;
		}
}
