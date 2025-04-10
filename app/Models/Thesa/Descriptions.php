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

            /* Ordem o items a serem mostrados */
            $classes = $this->classes();
            $RSP = [];
            foreach($classes as $id=>$class)
                {
                    $RSP[$class] = [];
                }

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
                                        array_push($RSP[$class], $line['lg_language']. '('.$line['lg_code'].')');
                                    }
                                break;
                            case 'Title':
                                $Thesa = new \App\Models\Thesa\Index();
                                $dth = $Thesa->le($th);
                                array_push($RSP[$class], $dth['th_name']);
                                break;
                            case 'Authors':
                                $Collaborators = new \App\Models\Thesa\Collaborators();
                                $text = $Collaborators->authors($th);
                                array_push($RSP[$class], $text);
                                break;
                            default:
                                foreach($data as $id=>$line)
                                    {
                                        $prop = $line['ds_prop'];
                                        if ($prop == 'methodology')
                                            {
                                                $prop = 'Methodology';
                                            }
                                        if ($prop == $class)
                                            {
                                                $text = $line['ds_descrition'];
                                                array_push($RSP[$class], $text);
                                            }
                                    }
                        }

                }
            $dt = [];
            foreach($RSP as $class=>$line)
                {
                    $dd = [];
                    $dd['class'] = $class;
                    if (isset($line[0]))
                        {
                            $dd['description'] = $line[0];
                            array_push($dt, $dd);
            }
                }
            $RSP = $dt;
            return $RSP;
        }
}