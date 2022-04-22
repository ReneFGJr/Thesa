<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThFunctions extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thfunctions';
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

    function menu($th)
        {
            $Socials = new \App\Models\Socials();
            $ThUsers = new \App\Models\Thesaurus\ThUsers();
            $autorized = $ThUsers->autorized($th);
            $sx = '
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Submenu</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSubMenu" aria-controls="navbarSubMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSubMenu">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="'.PATH.MODULE.'sistematic/'.$th.'">'.lang('thesa.sistematic').'</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="'.PATH.MODULE.'tree/'.$th.'">'.lang('thesa.tree').'</a>
                        </li>';
            if ($autorized)
            {
                $sx .= '
                        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="'.PATH.MODULE.'th_config/'.$th.'">'.lang('thesa.th_config').'</a>
                        </li>
                        ';
            }

            

            if ($autorized) { 
            $sx .= '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarSubDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            '.lang('thesa.tools').'
                        </a>                        
                ';

                $sx .= '<ul class="dropdown-menu" aria-labelledby="navbarSubDropdown">';
                $sx .= '<li><a class="dropdown-item" href="'.PATH.MODULE.'tools/inport">'.msg('thesa.inport_vc').'</a></li>'; 
                $sx .= '</ul>';
            $sx .= '</li>';
            }
            /*
            $sx .= '<li><a class="dropdown-item" href="#">Another action</a></li>';
            $sx .= '<li><hr class="dropdown-divider"></li>';
            $sx .= '<li><a class="dropdown-item" href="#">Something else here</a></li>';
            */
            

            /* Sample */
            /*        
                        <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                        </li>
                    </ul>';
            */
            $sx .= '</ul>';
            if ($autorized) 
            {
                $sx .= '
                    <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-info me-2" href="'.PATH.MODULE.'term/'.$th.'/concept">'.lang('thesa.term_concept').'</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-info" href="'.PATH.MODULE.'term/'.$th.'/add">'.lang('thesa.term_add').'</a>
                        </li>
                    </ul>';
            }
            $sx .= '</div></div></nav>';
            return $sx;
        }
}
