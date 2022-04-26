<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThConfigIcone extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thconfigicones';
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

    function edit($id,$dt)
        {
            $sx = h(lang('thesa.icone'),3);
            $ThIcone = new \App\Models\Thesaurus\ThIcone();
            $ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();
            $dt = $ThThesaurus->le($id);

            /************************************ Buttons */
            $sb = $this->btn_icone($id);
            $sb .= ' | ';
            $sb .= $this->btn_icone_my($id);

            $sx .= bsc($sb.'<br>'.$ThIcone->show_icone($dt),12);
            $sx = bs($sx);
            return $sx;
        }
    function btn_icone($id)
        {
            $sx = onclick(PATH.MODULE.'popup/icone/'.$id,800,800,'btn btn-outline-primary mb-2');
            $sx .= lang('thesa.icone_change');
            $sx .= '</span>';
            return $sx;
        }

    function btn_icone_my($id)
        {
            $sx = onclick(PATH.MODULE.'popup/icone_custom/'.$id,800,800,'btn btn-outline-primary mb-2');
            $sx .= lang('thesa.icone_send_my_icone');
            $sx .= '</span>';
            return $sx;
        }        
}
