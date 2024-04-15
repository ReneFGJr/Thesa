<?php

namespace App\Models\Thesa\Medias;

use CodeIgniter\Model;

class Index extends Model
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
        'id_mid', 'mid_th', 'mid_concept',
        'mid_file_size', 'mid_name', 'mid_directory',
        'mid_content_type', 'mid_file', 'mid_status',
        'updated_at'
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

    function le($id)
        {
            $dt = $this->where('mid_concept',$id)->findAll();
            $RSP = [];
            $RSP['video'] = [];
            $RSP['other'] = [];
            foreach($dt as $idx=>$line)
                {
                    $type = trim($line['mid_content_type']);
                    switch($type)
                        {
                            case 'video/mp4':
                                array_push($RSP['video'],$line);
                                break;
                            default:
                                array_push($RSP['other'], $line);
                                break;
                        }
                }
            return $RSP;
        }
}
