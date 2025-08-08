<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use FontLib\Table\Type\head;

class Icone extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'icones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public $directory = '../_repository/icones/';
    public $filePath = '';
    public $filePathExt = '';

    /************************ DIRETORIO DE IMAGENS PERSONALIZADAS */
    /* ../../_repository/icones/
    */

    function __construct()
        {
            parent::__construct();
            if ($_SERVER['SERVER_ADDR'] == '143.54.1.197')
                {
                    $this->directory = 'public/img/';
                }
        }

    function ShowImage($dt)
        {
            $dd = $this->icone($dt);
            if (file_exists($this->filePath))
                {
                    header('Content-Type: image/'.$this->filePathExt);
                    header('Content-Length: ' . filesize($this->filePath));
                    readfile($this->filePath);
                    exit;
                } else {
                    $this->filePath = 'img/icons/0000.svg';
                    $this->filePathExt = '.svg';
                    header('Content-Type: image/' . $this->filePathExt);
                    header('Content-Length: ' . filesize($this->filePath));
                    readfile($this->filePath);
                }
        }

    function uploadSchema()
    {
        $RSP = [];
        $thesaID = sonumero(get("thesaID"));
        $thesaID = round($thesaID, 0);
        $base64Data = get("fileUpload");
        if ($thesaID == 0 || $base64Data == '') {
            $RSP['status'] = '400';
            $RSP['message'] = 'ID do Thesa inválido ou dados de imagem ausentes';
            return $RSP;
        }

        // Remove o prefixo 'data:image/...;base64,'
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            $extension = strtolower($type[1]); // exemplo: png, jpg

            // Segurança básica: permite apenas png, jpg e jpeg
            if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $RSP['status'] = '415';
                $RSP['message'] = 'Tipo de imagem não suportado: ' . $extension;
                return $RSP;
            }
        } else {
            $RSP['status'] = '400';
            $RSP['message'] = 'Formato inválido de imagem base64';
            return $RSP;
        }

        // Decodifica o base64
        $decodedImage = base64_decode($base64Data);

        if ($decodedImage === false) {
            $RSP['status'] = '500';
            $RSP['message'] = 'Erro ao decodificar imagem';
            return $RSP;
        }

        // Cria o diretório se necessário
        $uploadPath = $this->directory;
        dircheck($uploadPath);

        // Gera nome único
        $filename = 'icone_'.strzero($thesaID, 4) . '.' . $extension;
        $filepath = $uploadPath . $filename;

        // Salva a imagem
        if (!file_put_contents($filepath, $decodedImage)) {
            $RSP['status'] = '500';
            $RSP['message'] = 'Erro ao salvar imagem';
        }

        $RSP['status'] = '200';
        $RSP['message'] = 'Imagem salva com sucesso';
        $RSP['filename'] = $filename;
        $RSP['path'] = $filepath;
        return $RSP;
    }


    function icone($dt = array())
    {
        $exts = array('.png', '.jpg', '.jpeg', '.svg');
        foreach ($exts as $ext) {
            $PATH = $_SERVER['SCRIPT_FILENAME'];
            $PATH = str_replace('index.php', '', $PATH);

            $PATH = str_replace('app/Controllers', '', $PATH);
            $img = $PATH.$this->directory . 'icone_' . strzero($dt['id_th'], 4) . $ext;

            if (file_exists($img)) {
                $this->filePath = $img;
                $this->filePathExt = troca($ext,'.','');
                $img = base_url('image/icone/'.$dt['id_th']);
                return $img;
                break;
            }
        }
        $img = base_url('/img/icons/0000.svg');
        return $img;
    }

    function change($d1,$th,$d3='')
        {
        $sx = '';
        $Thesa = new \App\Models\Thesa\Index();
        $dt = $Thesa->le($th);
        $img = $this->icone($dt);

        $data = h($dt['th_name'],2);
        $data .= $img;
        $img = '<img src="' . $img . '" class="img-fluid">';

        $sa = '<table class="table full">';
        $sa .= '<tr>';
        $sa .= '<td width="25%">'.$img.'</td>';
        $sa .= '<td width="75%">' . $data . '</td>';
        $sa .= '</tr>';
        $sa .= '</table>';
        $sa = bsc($sa,12,'mt-5');
        $sb = '';
        $se = '';
        $type = get("type");
        switch($type)
            {
                case 'upload':
                    // Get the file's basename

                    if (isset($_FILES['files']['tmp_name']))
                        {
                            $tmp_name = $_FILES['files']['tmp_name'];
                            $file_type = $_FILES['files']['type'];
                            $error = $_FILES['files']['error'];
                            $size = $_FILES['files']['size'];
                            $ok = false;
                            switch($file_type)
                                {
                                    case 'image/png':
                                        $ext = '.png';
                                        $ok = true;
                                        break;

                                    case 'image/jpeg':
                                        $ext = '.jpg';
                                        $ok = true;
                                    break;
                                    default:
                                        $se = bsmessage(lang('thesa.image_format_invalid - '.$file_type),3);
                                }

                            if ($ok)
                                {
                                    $dir = '_repository/users/'.$th.'/icones';
                                    dircheck($dir);

                                    $nr = 1;
                                    $file = $dir.'/icone_'.strzero($nr,3) . $ext;
                                    while (file_exists($file))
                                        {
                                            $nr++;
                                            $file = $dir . '/icone_' . strzero($nr, 3) . $ext;
                                        }
                                    move_uploaded_file($tmp_name,$file);
                                    $Thesa = new \App\Models\Thesa\Thesa();
                                    $data = [];
                                    $data['th_icone_custom'] = $file;
                                    $data['th_icone'] = 0;
                                    $Thesa->set($data)->where('id_th',$th)->update();
                                    $sx = wclose();
                                    return $sx;
                                }
                        }

                    $sb .= form_open_multipart();
                    $sb .= form_upload('files');
                    $sb .= form_submit('action',lang('thesa.upload'));
                    $sb .= form_close();
                    break;
                default:
                    $url = PATH.'/admin/icone/th/'.$th.'?type=select';
                    $sb .= bsc('<a href="'.$url.'" class="btn btn-secondary m-4 p-4 full">'.lang('thesa.select_icone').'</a>',12);

                    $url = PATH . '/admin/icone/th/' . $th . '?type=upload';
                    $sb .= bsc('<a href="' . $url . '" class="btn btn-secondary m-4 p-4 full">' . lang('thesa.upload_icone') . '</a>',12);
                    break;
            }
        $sb = bsc($sb, 12, 'mt-5');

        if ($se != '')
            {
                $se = bsc($se,12, 'mt-4');
            }
        $sx = bs($sa.$sb.$se);
        return $sx;
        }

}
