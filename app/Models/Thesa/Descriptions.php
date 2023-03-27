<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Descriptions extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_descriptions';
    protected $primaryKey       = 'id_ds';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_ds', 'ds_prop', 'ds_descrition','ds_th',
        'created_at', 'updated_at'
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

    function classes()
        {
            $class = array('Title','Authors','Introduction', 'Language', 'Methodology', 'Audience','ISBN', 'License','Icons','Image');
            return $class;
        }

    function resume($id)
        {
            $Collaborators = new \App\Models\Thesa\Collaborators();
            $access = $Collaborators->own($id);

            $Thesa = new \App\Models\Thesa\Index();
            $dt = $Thesa->summary($id);
            $dt['th'] = $id;
            $dt['access'] = $access;
            $sx = view('Theme/Standard/Summary', $dt);
            return $sx;
        }

    function ajax_field_save()
        {
            $th = get("th");
            $prop = get("form");
            $vlr = get("vlr");

            if($prop == 'achronic')
                {
                    $vlr = trim(ascii($vlr));
                    $vlr = troca($vlr,' ','_');
                }

            $da = array();
            $da['ds_th'] = $th;
            $da['ds_prop'] = $prop;
            $da['ds_descrition'] = $vlr;
            $da['updated_at'] = date("Y-m-d H:i:s");

            $dt = $this
                ->where('ds_th',$th)
                ->where('ds_prop', $prop)
                ->findAll();

            if (count($dt) == 0)
                {
                    $da['ds_th'] = $th;
                    $da['ds_prop'] = $prop;
                    $da['ds_descrition'] = $vlr;
                    $da['updated_at'] = date("Y-m-d H:i:s");
                    $this->insert($da);
                } else {
                    $this->set($da)
                    ->where('ds_th', $th)
                    ->where('ds_prop', $prop)
                    ->update();
                }

            /* Title */
            if ($prop == 'title')
                {
                    $Thesa = new \App\Models\Thesa\Thesa();
                    $db['th_name'] = $vlr;
                    $Thesa->set($db)->where('id_th',$th)->update();
                }
            /* Achronic */
            if ($prop == 'achronic') {
                $Thesa = new \App\Models\Thesa\Thesa();
                $db['th_achronic'] = $vlr;
                $Thesa->set($db)->where('id_th', $th)->update();
            }

            if ($prop == 'License') {
                exit;
            }

            /* Link */
            $lk = '<span class="ms-2" onclick="togglet(\'' . $prop . '\');">' . bsicone('edit', 16) . '</span>';

            /* Label */
            $label = $vlr;
            $label .= $lk;
            echo h($label,5);
        }

    function register($th, $class, $txt)
        {

        $data['ds_descrition'] = $txt;
        $data['ds_th'] = $th;
        $data['ds_prop'] = $class;
        $data['updated_at'] = date("Y-m-d H:i:s");

            $dt = $this
                ->where('ds_th', $th)
                ->where('ds_prop', $class)
                ->FindAll();

            if (count($dt) == 0)
            {
                $this->insert($data);
            } else {
                $idr = $dt[0]['id_ds'];
                $this->set($data)->where('id_ds', $idr)->update();
            }
        }

   function show($th,$edit=true)
        {
            $sx = '';
            $this->select('id_ds, ds_prop, ds_descrition, ds_th');
            $this->where('ds_th', $th);
            $this->orderBy('ds_prop', 'ASC');
            $data = $this->findAll();

            $classes = $this->classes();

            foreach($classes as $id=>$class)
                {
                    $text = '';
                    switch($class)
                        {
                            case 'Language':
                                $Language = new \App\Models\Thesa\Language();
                                $dt = $Language
                                    ->join('language','lgt_language = id_lg')
                                    ->where('lgt_th',$th)
                                    ->orderBy('id_lgt')
                                    ->findAll();
                                $text = '';
                                foreach($dt as $id=>$line)
                                    {
                                        $text .= $line['lg_language'].'. ';
                                    }
                                    $sx .= '<h3 class="lora">' . lang('thesa.' . $class) . '</h3>';
                                    $ln = explode(chr(13),$text);
                                    foreach($ln as $id=>$lns)
                                        {
                                            $sx .= '<div id="sp_' . $class . '" class="m-2 mb-3 p-2 paragrafo">' . $lns . '</div>';
                                        }

                                break;
                            case 'Title':
                                $Thesa = new \App\Models\Thesa\Index();
                                $dth = $Thesa->le($th);
                                $text = $dth['th_name'];
                                $sx .= '<h3 class="lora">' . lang('thesa.' . $class) . '</h3>';
                                $sx .= '<div id="sp_' . $class . '" class="m-2 mb-3 p-2 paragrafo">' . $text . '</div>';
                                break;
                            case 'Authors':
                                $Collaborators = new \App\Models\Thesa\Collaborators();
                                $text = $Collaborators->authors($th);
                                $sx .= '<h3 class="lora">' . lang('thesa.' . $class) . '</h3>';
                                $sx .= '<div id="sp_' . $class . '" class="m-2 mb-3 p-2 paragrafo">' . $text . '</div>';
                                break;
                            default:
                                foreach($data as $idx=>$line)
                                {
                                    if ($line['ds_prop'] == $class) {
                                        $text = $line['ds_descrition'];
                                        $text = troca($text, chr(10), '<br/>');
                                    }
                                }
                                //if ((strlen($text) > 0) or ($edit == true))

                                if ((strlen($text) > 0))
                                    {
                                        $sx .= '<h3 class="lora">' . lang('thesa.' . $class) . '</h3>';
                                        $ln = explode('<br/>', $text);
                                        foreach ($ln as $id => $lns) {
                                            $sx .= '<div id="sp_' . $class . '" class="m-2 mb-3 p-2 paragrafo">' . $lns . '</div>';
                                        }
                                    }
                                break;
                        }

                }
            return $sx;
        }
}