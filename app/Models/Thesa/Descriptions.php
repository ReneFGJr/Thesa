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
            $class = array('Introduction', 'Methodology', 'Audience', 'License');
            return $class;
        }

    function resume($id)
        {
            $Thesa = new \App\Models\Thesa\index();
            $dt = $Thesa->summary($id);
            $sx = view('Thesa/Summary_line', $dt);
            return $sx;
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
                                }
                        }
                    $sx .= '<h3>'.lang('thesa.'.$class[$r]).'</h3>';
                    $sx .= '<div id="sp_'.$class[$r].'" class="m-2 p-2">'.$text.'</div>';
                }
            return $sx;
        }
}
