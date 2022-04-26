<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThIcone extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'th_thesaurus';
    protected $primaryKey           = 'id_pa';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        'id_pa','pa_icone'
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

    function show_icone($line,$class='') {  
        $sx = '<img src="'.$this->icone($line).'" class="'.$class.' img-fluid" style="max-height: 150px;" border=0>';
        return $sx;
    }

    function icones_upload($id)
        {
            $sx = '';
            if (isset($_FILES['icone']['tmp_name']))
                {
                    $tmp = trim($_FILES['icone']['tmp_name']);
                    $type = mime_content_type($tmp);
                    $ok = 1;
                    switch($type)
                        {
                            default:
                                $sx .= bsmessage(lang('thesa.file_error_type - '.$type ),3);
                                $ok = 0;
                                break;
                            case 'image/jpeg':
                                break;
                            case 'image/jpg':
                                break;
                            case 'image/png':
                                break;
                            case 'image/gif':
                                break;
                        }
                    if ($ok == 1)
                        {
                            $dir = 'img';
                            dircheck($dir);
                            $dir = 'img/icone/';
                            dircheck($dir);
                            $dir = 'img/icone/custom/';
                            $idc = round($id)*(-1);
                            $filename = $dir.str_pad($id, 3,STR_PAD_LEFT,'0').'.png';
                            move_uploaded_file($tmp, $filename);
                            $sx .= $this->set_icone($id,$idc);
                            $sx .= wclose();
                            return $sx;
                        }
                }


            $sx .= form_open_multipart();
            $sx .= form_upload('icone');
            $sx .= form_submit(array('name' => 'submit', 'value' => lang('thesa.Upload_icone'), 'class' => 'btn btn-primary'));
            $sx .= form_close();
            return $sx;
        }

    function icones_options($id=0,$d2='')
        {
            $ids = get("id");
            $idc = get("idc");
            if ($idc != '')
                {
                    $icone = (-1)*round($idc);
                    $this->set_icone($id,$icone);
                    return wclose();
                }

            if ($ids != '')
                {
                    $icone = round($ids);
                    $this->set_icone($id,$icone);
                    return wclose();
                }

            $sx = '';
            for ($r=0;$r < 1000;$r++)
                {
                    $idc = str_pad($r, 3,STR_PAD_LEFT,'0');
                    $img = 'img/icone/thema/' .$idc. '.png';
                    if (file_exists($img))
                        {
                            $link = '<a href="'.PATH.MODULE.'popup/icone/'.$id.'/?id='.$idc.'">';
                            $linka = '</a>';
                            $sx .= bsc($link.'<img src="'.URL.$img.'" class="img-fluid mb-4" border=0>'.$linka,3);
                        }
                }
            /*************************** Custom */
            $sxc = '';
            for ($r=0;$r < 1000;$r++)
                {
                    $idc = str_pad($r, 3,STR_PAD_LEFT,'0');
                    $img = 'img/icone/custom/' .$idc. '.png';
                    if (file_exists($img))
                        {
                            $link = '<a href="'.PATH.MODULE.'popup/icone/'.$id.'/?idc='.$r.'">';
                            $linka = '</a>';
                            $sxc .= bsc($link.'<img src="'.URL.$img.'" class="img-fluid mb-4" border=0>'.$linka,3);
                        }
                }  
            if ($sxc != '')
                {
                    $sx .= h(lang('thesa.icon_custom'),4);
                    $sx .= $sxc;
                }      
            $sx = bs($sx);
            return $sx;            
        }

    function set_icone($th,$icone)
        {
            $dd['pa_icone'] = $icone;
            $this->set($dd)->where('id_pa',$th)->update();
        }

    function icone($line) {  
            $id = $line['id_pa'];
            $idc = $line['pa_icone'];    
            if ($idc >= 0)
                {
                    $idc = str_pad($idc, 3,STR_PAD_LEFT,'0');
                    $img = 'img/icone/thema/' .$idc. '.png';
                } else {
                    $idc = str_pad($idc * (-1), 3,STR_PAD_LEFT,'0');
                    $img = 'img/icone/custom/' . $idc . '.png';
                }

            $filename = $img;
            if (!file_exists($filename))
                {
                    $img = 'img/icone/custom/000.png';
                }
            $sa = (URL.$img);
            return $sa;
        }    
}
