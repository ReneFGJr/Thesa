<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class ThTerm extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_terms';
    protected $primaryKey       = 'id_term';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_term', 'term_name', 'term_lang'
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


    function index($d1, $d2, $d3, $d4)
    {
        $th = 1;
        $sx = '';
        switch ($d1) {
            case 'list':
                $sx = $this->term_list($d1, $d2, $d3);
                break;
            case 'add_ajax':
                $this->term_register($d1, $d2, $d3);
                break;

            case 'add':
                $sx .= $this->form();
                break;
            default:
                $menu['#TERM'] = 'Terms';
                $menu[PATH . '/admin/terms/add'] = msg('add_term');

                $ThTermTh = new \App\Models\RDF\ThTermTh();
                $menu[PATH . '/admin/terms/'] = lang('thesa.total_terms').' '.$ThTermTh->total($th);
                $menu[PATH . '/admin/terms/list'] = msg('thesa.total_not_attribuit') . ' ' . $ThTermTh->totalNoUse($th);

                $sx .= menu($menu);
        }
        $sx = bs(bsc($sx, 12));
        return $sx;
    }

    function form($th = 0)
    {
        $Language = new \App\Models\Language\Index();
        $sx = '';
        /*************************************** Serie A */
        $sa = '';
        $sa .= '<small>' . lang('thesa.terms_add') . '</small>';
        $sa .= '<textarea class="form-control" name="terms" id="terms" rows=10 cols=80></textarea>';
        $sa .= $this->btn_append($th);
        $sa .= '&nbsp;|&nbsp;';
        $sa .= $this->btn_return($th);

        /**************************************** Serie B */
        $sb = '';
        $sb .= '<small>' . lang('thesa.terms_lang') . '</small>';
        $sb .= $Language->form($th);
        /**************************************** Serie C */
        $sc = '';
        $sc .= '<small>' . lang('thesa.process_area') . '</small>';
        $sc .= '<div class="input-group mb-3" id="result">';
        $sc .= 'results';
        $sx .= '</div>';

        $sx = bs(
            bsc($sa, 5) .
                bsc($sb, 3) .
                bsc($sc, 4)
        );
        return $sx;
    }

    function term_list()
        {
            $Thesa = new \App\Models\Thesa\Thesa();
            $ThTermTh = new \App\Models\RDF\ThTermTh();
            $th = $Thesa->setThesa();
            $sx = $ThTermTh->totalNoUse($th);
            return $sx;
        }

    /***************************************** AJAX ou API */
    function term_register($d1, $d2, $d3)
    {
        $Thesa = new \App\Models\Thesa\Thesa();
        $th = $Thesa->setThesa();
        $term = get("terms");
        $term = troca($term,chr(13),';');
        $term = troca($term, chr(10), ';');
        $terms = explode(';',$term);

        $lang = get("lang");

        for ($r=0;$r < count($terms);$r++)
            {
                $trms = $terms[$r];
                if ($this->register($trms,$lang,$th))
                    {
                        echo $trms.' - '.lang('thesa.inserted').'<br>';
                    } else {
                        echo $trms . ' - ' . lang('thesa.already') . '<br>';
                    }
            }
        exit;
    }

    /*********************************************************** REGISTER */
    function register($term,$lang,$th)
        {
            $ThTermTh = new \App\Models\RDF\ThTermTh();
            $dt = $this->where('term_name',$term)->where('term_lang',$lang)->findAll();
            if (count($dt) == 0)
                {
                    $data = array();
                    $data['term_name'] = $term;
                    $data['term_lang'] = $lang;
                    $id = $this->insert($data);
                    return $ThTermTh->link_th($id, $th);

                } else {
                    $id = $dt[0]['id_term'];
                    /************************ Vinculo com o tesauro */
                    return $ThTermTh->link_th($id,$th);
                }
        }

    function btn_append($th)
    {
        $sx = '';
        $sx .= '<span class="btn btn-primary" onclick="new_term(' . $th . ');">';
        $sx .= msg('add_term');
        $sx .= '</span>';

        $sx .= '
        <script>
				function new_term($th)
					{
						let xhr = new XMLHttpRequest();
						var items = document.querySelector("#terms").value
                        var lang = $("input:radio[name =\'lg\']:checked").val();

                        if (items == "") { alert("Inclua os items"); return false; }

                        if ($("input[type=radio][name=lg]:checked").is(":checked"))
                        {
                            let userHtml = "apikey=' . md5('RENE') . '&lang="+lang+"&terms="+items;

                            document.querySelector(\'#result\').innerHTML = "Registrando ...<br>";

                            xhr.open("POST", "' . PATH . 'admin/terms/add_ajax", true);
                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xhr.send(userHtml);

                            xhr.onload = function ()
                            {
                                let botHtml = this.responseText;
                                document.querySelector("#result").innerHTML+= botHtml;
                                document.querySelector("#terms").value = "";
                            }
                        } else {
                            alert("Selecione o idioma");
					    }
                    }
        </script>';
        return $sx;
    }

    function btn_return($th)
    {
        $sx = '';
        $sx .= '<a href="' . PATH . '/admin/terms' . '" class="btn btn-outline-primary">' . lang("thesa.return") . '</a>';
        return $sx;
    }


}
