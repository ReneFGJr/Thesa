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
            $class = array('Title','Introduction', 'Methodology', 'Audience','ISBN', 'License','Language');
            return $class;
        }

    function resume($id)
        {
            $Thesa = new \App\Models\Thesa\index();
            $dt = $Thesa->summary($id);
            $sx = view('Thesa/Summary_line', $dt);
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

   function show($th)
        {
            $sx = '';
            $this->select('id_ds, ds_prop, ds_descrition, ds_th');
            $this->where('ds_th', $th);
            $this->orderBy('ds_prop', 'ASC');
            $data = $this->findAll();

            $class = $this->classes();
            for ($r=0;$r < count($class);$r++)
                {
                    $text = '';
                    for ($y=0;$y < count($data);$y++)
                        {
                            $line = $data[$y];
                            if ($line['ds_prop'] == $class[$r])
                                {
                                    $text = $line['ds_descrition'];
                                    $text = troca($text,chr(10),'<br/>');
                                }
                        }
                    $sx .= '<h3>'.lang('thesa.'.$class[$r]).'</h3>';
                    $sx .= '<div id="sp_'.$class[$r].'" class="m-2 p-2">'.$text.'</div>';
                }
            return $sx;
        }
}
