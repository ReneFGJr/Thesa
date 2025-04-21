<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;
use CodeIgniter\HTTP\Files\UploadedFile;

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

    function icone($dt = array())
    {
        if (isset($dt['th_icone_custom']))
            {
                $img = $dt['th_icone_custom'];
                if (!file_exists($img))
                    {
                        $img = strzero(0, 4) . '.svg';
                        $img = PATH . '/img/icons/' . $img;
                    } else {
                        $img = URL.'/'.$img;
                    }
            } else {
                if (!isset($dt['th_icone'])) {
                    $img = strzero(0, 4) . '.svg';
                    $img = PATH . '/img/icons/' . $img;
                } else {
                    $img = strzero($dt['th_icone'], 4) . '.png';
                    $img = PATH . '/img/icons/' . $img;
                    if ($dt['th_icone'] != 0) {
                        $img = strzero($dt['th_icone'], 4) . '.png';
                        $img = getenv("repository") . '/img/icons/' . $img;
                    }
                }
        }
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
