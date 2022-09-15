<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Thesa extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa';
    protected $primaryKey       = 'id_th';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_th','th_name','th_achronic',
        'th_description','th_status','th_terms',
        'th_version','th_icone','th_type','th_own'
    ];

    protected $typeFields    = [
        'hidden', 'string', 'string',
        'text', 'string', 'hidden',
        'string', 'hidden', 'string', 'user'
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

    function thesa_add($dd)
    {
        $ThesaUser = new \App\Models\Thesa\ThesaUser();
        $dt = array();
        $dt['th_name'] = $dd['name'];
        $dt['th_own'] = $dd['own'];
        $dt['th_achronic'] = $dd['achronic'];
        $dt['th_description'] = $dd['description'];
        $dt['th_status'] = 1;
        $dt['th_type'] = $dd['type'];
        $dt['th_icone'] = date("s");
        $dt['th_version'] = 1;

        if ($dt['th_own'] > 0) {
            $da = $this
                ->where('th_own', $dt['th_own'])
                ->where('th_achronic', $dt['th_achronic'])
                ->findAll();

            if (count($da) == 0) {
                $id = $this->insert($dt);
            } else {
                $id = $da[0]['id_th'];
            }

        $ThesaUser->add_user_th($id,$dt['th_own'],1);
        return $id;
        }
    }

    function btn_create_th()
        {
        $sx = '';
        $Socials = new \App\Models\Socials();
        $ID = $Socials->getID();

        if ($ID > 0) {
            /**************************************** CHECHA Limite */
            if ($this->th_limit($ID) == false) {
                $sx = '<a href="' . PATH . MODULE . 'edit_th/0' . '" class="btn btn-outline-danger mt-2 mb-2" style="width: 100%;">';
                $sx .= lang('thesa.create_th');
                $sx .= '</a>';
            } else {
                $sx = '<span class="btn btn-danger mt-2 mb-2 disable" style="width: 100%;">';
                $sx .= lang('thesa.create_th_limite');
                $sx .= '</span>';
            }
        }
        return $sx;
        }

    function edit($id)
        {
        $submenu = new \App\Models\Thesaurus\ThFunctions();
        $sx = '';
        $this->id = $id;
        $this->path = PATH . MODULE . 'edit_th/' . $id;
        $this->path_back = 'none';
        $this->pre = 'thesa.';

        /***************************** SubMenu */
        if ($id > 0) {
            $sx .= $submenu->menu($id);
        }

        /***************************************************************** margens */
        $sx .= bsc('<div class="mt-5"></div>', 12);

        /***************************************************************** Header */
        if ($id == 0) {
            $pa_achronic = get("pa_achronic");
            if ($pa_achronic != '') {

                $dt = $this->where('pa_achronic', $pa_achronic)->findAll();
                if (count($dt) > 0) {
                    $sx .= bsmessage(lang('thesa.error.achronic') . ' <b>' . $pa_achronic . '</b>' . lang('thesa.error.achronic2'), 3);
                    $_POST['pa_achronic'] = '';
                }
            }
        }


        if ($id == 0) {
            $Socials = new \App\Models\Socials();
            $_POST['pa_created'] = $Socials->getID();
        }

        if ($id == 0) {
            $sx .= h('thesa.create_thesaurus', 2);
        }

        $sx .= form($this);

        if (($this->saved > 0) and ($id == 0)) {
            $Socials = new \App\Models\Socials();
            $idth = $this->id;
            $ThUsers = new \App\Models\Thesa\ThesaUser();
            $idu = $Socials->getID();
            $ThUsers->add_user_th($idth, $idu, 1);

            /************************** Define um idioma */
            $ThesaLanguage = new \App\Models\Thesa\ThesaLanguage();
            $ThesaLanguage->language_add($idth, 364);

            /************************** Redireciona */
            $url = PATH . MODULE . 'th_config/' . $idth;
            $sx = metarefresh($url);

            return $sx;
        }
        return $sx;
        }

    function myThesa()
    {
        $sx = '';
        $vtp = get("type");
        $Socials = new \App\Models\Socials();
        $ID = $Socials->getID();
        if ($ID > 0) {
            $dt = $this
                ->select('id_pa, pa_name, pa_icone')
                ->join('th_users', 'ust_th = id_pa')
                ->where('ust_user_id', $ID)
                ->groupBy('id_pa, pa_name, pa_icone')
                ->OrderBy('pa_name')
                ->FindAll();

            $sx .= bsc('Total ' . count($dt) . ' registros');
            if (count($dt) == 0) {
                $sx = bs(bsc($sx, 12));
                return $sx;
            }
            for ($r = 0; $r < count($dt); $r++) {
                $line = $dt[$r];
                $sx .= $this->card($line, $vtp);
            }
        } else {
            $sx .= metarefresh(PATH . MODULE);
        }
        $sx = bs($sx);
        $sx .= bs(bsc($this->btn_create_th(), 3));
        return $sx;
    }

    function setTh($id)
    {
        $_SESSION['th'] = $id;
        return True;
    }

    function show($th, $lt = '')
    {
        $ThTheme = new \App\Models\Thesa\Theme\Standard\index();
        $sx = $ThTheme->header($th, 0, $lt);

        return $sx;
        /*************************************** mostra um thesauros ************/
        $dt = $this->Find($th);
        if ($dt == '') {
            $sx = metarefresh(PATH . MODULE);
            return $sx;
        }
        /*************************************************************************** */
        $sx = $this->header($dt);
        $sx .= $this->show_resume($dt);
        /********************************** Sumários das letras */
        $sx .= $ThConcept->paginations($id, $ltr);
        /********************************** Lista de Termos *****/
        $q = get("q");
        if (strlen($q) > 0) {
            $sm1 = $this->terms_query($id, $q);
            $sm2 = '';
        } else {
            $sm1 = $this->terms($id, $ltr);
            $sm2 = '';
        }

        $Socials = new \App\Models\Socials();
        $ThUsers = new \App\Models\Thesaurus\ThUsers();
        if (($Socials->getAccess('#ADM')) or ($ThUsers->autorized($Socials->getID()))) {
            $ThFunctions = new \App\Models\Thesaurus\ThFunctions();
            $sb = $ThFunctions->menu($id);
            $sx .= $sb;
        }


        $sx .=  bsc($sm1, 4, 'p-3 mb-1') .
        bsc($sm2, 8, 'shadow p-3 mb-1 bg-white rounded');
    }
}
