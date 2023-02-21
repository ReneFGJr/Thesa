<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Midias extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_midias';
    protected $primaryKey       = 'id_mid';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_mid', 'mid_th', 'mid_concept', 'mid_directory',
        'mid_name', 'mid_content_type', 'mid_file', 'mid_file_size',
        'mid_status', 'updated_at'
    ];

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

    function upload($id, $d2 = '')
    {
        $sx = '';
        $url = PATH . '/admin/ajax/upload/' . $id;
        $sx .= h('Upload de arquivos', 2);

        $sx .= form_open_multipart();
        $sx .= form_upload('userfile');
        $sx .= form_submit('submit', msg('upload'));
        $sx .= form_close();

        if (isset($_FILES['userfile']['name'])) {
            $ThConcept = new \App\Models\RDF\ThConcept();
            $dc = $ThConcept->le($d2);
            if (count($dc) > 0) {
                $th = $dc[0]['c_th'];
                $dir = $this->directory($th);
                $fileT = $_FILES['userfile']['tmp_name'];
                $fileN = $_FILES['userfile']['name'];

                if (file_exists($fileT)) {
                    $ext = explode('.', $fileN);
                    $fileZ = md5($fileN) . '.' . $ext[count($ext) - 1];
                    $fileN = $dir . $fileZ;

                    echo h($fileT, 4);
                    echo h($fileZ, 4);

                    move_uploaded_file($fileT, $fileN);

                    $this->register($d2, $th, $fileZ);

                    $sx = '<script>wclose();</script>';
                }
            } else {
                $sx .= '<br>' . msg('ID not found to uploaded');
            }
        }
        return $sx;
    }

    function show_content($dt)
    {
        $content = $dt['mid_content_type'];
        $sx = '';
        switch ($content) {
            case 'image/jpeg':
                $sx = '<img src="' . URL . '/' . $dt['mid_directory'] . $dt['mid_file'] . '" class="img-fluid img-thumbnail ms-2">';
                break;

            case 'image/webp':
                $sx = '<img src="' . URL . '/' . $dt['mid_directory'] . $dt['mid_file'] . '" class="img-fluid img-thumbnail ms-2">';
                break;

            case 'image/png':
                $sx = '<img src="' . URL . '/' . $dt['mid_directory'] . $dt['mid_file'] . '" class="img-fluid img-thumbnail ms-2">';
                break;

            case 'video/mp4':
                $url = URL . '/' . $dt['mid_directory'] . $dt['mid_file'];
                $sx = '<video height="320" controls autoplay loop="true" class="img-fluid img-thumbnail ms-2">';
                $sx .= '<source src="' . $url . '" type="video/mp4">';
                $sx .= 'Your browser does not support the video tag.';
                $sx .= '</video>';
                break;

            default:
                $sx .= $content;
                break;
        }
        return $sx;
    }

    function show($idc)
    {
        $midia = '';
        $dt = $this->where('mid_concept', $idc)->findAll();
        if (count($dt) == 1)
            {
                $line = $dt[0];
                $midia .= $this->show_content($line);
            } else {
                $midia .= '<div id="carouselExampleSlidesOnly"
                            class="carousel slide"
                            data-bs-ride="carousel"
                            data-bs-interval="15000">'.cr();
                $midia .= '<div class="carousel-inner">';

                for ($r = 0; $r < count($dt); $r++) {
                    $sel = '';
                    if ($r==0) { $sel = ' active'; }
                    $line = $dt[$r];
                    $midia .= '<div class="carousel-item '.$sel.'">'.cr();
                    $midia .= $this->show_content($line);
                    $midia .= '</div>'.cr();
                }
                $midia .= '</div>';
                $midia .= '</div>';
            }
        return $midia;
    }

    function load_link($th, $url, $idc)
    {
        $dir = $this->directory($th);
        $ext = explode('.', $url);
        $ext = $ext[count($ext) - 1];
        $file = $dir . md5($url) . '.' . $ext;

        $this->register($idc, $th, md5($url) . '.' . $ext);

        $load = true;
        if (file_exists($file)) {
            $load = false;
        }

        if ($load == true) {
            $txt = read_link($url);
            file_put_contents($file, $txt);
        } else {
            $txt = file_get_contents($file);
        }
        return 'Image imported';
    }

    function register($idc, $th, $file)
    {
        $dir = $this->directory($th);
        $dfile = $dir . $file;
        if (file_exists($dfile)) {
            $content = mime_content_type($dfile);
        } else {
            echo "Erro ao acessar o arquivo";
            echo $dfile;
            exit;
        }

        $data['mid_th'] = $th;
        $data['mid_concept'] = $idc;
        $data['mid_name'] = $th;
        $data['mid_content_type'] = $content;
        $data['mid_file'] = $file;
        $data['mid_status'] = 1;
        $data['mid_directory'] = $dir;
        $data['mid_file_size'] = filesize($dfile);
        $data['updated_at'] = date("Y-m-d H:i:s");

        $dt = $this
            ->where('mid_concept', $idc)
            ->where('mid_file', $file)
            ->where('mid_th', $th)
            ->findAll();
        if (count($dt) == 0) {
            $this->insert($data);
        }
        return true;
    }

    function directory($th)
    {
        $dir = '_acervo';
        dircheck($dir);
        $dir = '_acervo/_images';
        dircheck($dir);
        $dir = '_acervo/_images/thesa';
        dircheck($dir);
        $dir = '_acervo/_images/thesa/th_' . strzero($th, 5) . '/';
        dircheck($dir);
        return $dir;
    }

    function image_save_url($id, $th, $url)
    {
        $img = $this->load_link($th, $url, $id);
        return $img;
    }
}
