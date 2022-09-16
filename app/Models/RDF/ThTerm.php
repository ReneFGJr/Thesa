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
            case 'create_concept':
                $sx = $this->create_concept($d1, $d2, $d3);
                break;
            case 'selected':
                $this->ajax_selected($d1, $d2, $d3);
                break;
            case 'add_ajax':
                $this->term_register($d1, $d2, $d3);
                break;
            case 'ajax_term_concept':
                $this->ajax_term_concept($d1, $d2, $d3);
                break;
            case 'ajax_term_update':
                echo $this->term_list();
                echo $this->term_list_script();
                echo '<small style="font-size: 70%;">';
                echo lang("thesa.updated_at").' '. date("Y-m-d H:i:s");
                echo '</small>';
                exit;
                break;

            case 'add':
                $sx .= $this->form();
                break;
            default:
                $menu['#TERM'] = 'Terms';
                $menu[PATH . '/admin/terms/add'] = msg('add_term');

                $ThTermTh = new \App\Models\RDF\ThTermTh();
                $menu[PATH . '/admin/terms/'] = lang('thesa.total_terms').' '.$ThTermTh->total($th);
                $menu[PATH . '/admin/terms/create_concept'] = msg('thesa.total_not_attribuit') . ' ' . $ThTermTh->totalNoUse($th);

                $sx .= menu($menu);
        }
        $sx = bs(bsc($sx, 12));
        return $sx;
    }

    function le($id)
    {
        $dt = $this
            ->join('thesa_terms_th', 'term_th_term = id_term')
            ->join('language', 'term_lang = id_lg')
            ->where('id_term', $id)
            ->where('term_th_concept', 0)
            ->orderBy('term_name', 'ASC')
            ->findAll();
        return $dt;
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

    function create_concept($d1,$d2,$d3)
        {
            $sa = h(lang('thesa.terms_add'),3);
            $sa .= '<div id="term_list_div">';
            $sa .= $this->term_list();
            $sa .= '</div>';
            $sb = $this->term_functions();
            $sc = $this->term_drashboard();

            $sx = bs(
                bsc($sa, 4).
                bsc($sb, 4).
                bsc($sc, 4)
            );
            return $sx;
        }

    function ajax_term_concept($act, $id)
        {
            $dt = $this->le($id);
            echo $this->term_header($dt);

            $confirm = get("confirm");
            if ($confirm == 'yes')
                {
                    /*********************************** CREATE CONCEPT */
                    $ThConcept = new \App\Models\RDF\ThConcept();
                    $ThConcept->register($id, $dt[0]['term_th_thesa']);

                    echo bsmessage(lang('thesa.term_concept_creadted'), 1);
                    echo '
                    <script>
                        var url = "' . PATH . 'admin/terms/ajax_term_update/' . $id.'";
                        $("#result").html("");
                        $("#term_list_div").load(url);
                    </script>
                    ';
                } else {
                    /***** CONFIRM */
                    echo '<a href="#" id="btn_confirm" class="btn btn-outline-primary ms-3 me-3 mb-3 btn-fluid">' . lang('thesa.term_confirm') . '</a>';
                    echo '<a href="#" id="btn_cancel_drash" class="btn btn-outline-warning ms-3 me-3 mb-3 btn-fluid">' . lang('thesa.cancel') . '</a>';
                    echo '
                        <script>
                            $("#btn_confirm").click(function() {
                                var id = ' . $id . ';
                                var url = "' . PATH . 'admin/terms/ajax_term_concept/' . $id . '?confirm=yes";
                                $("#drashboard").load(url);
                            });
                            $("#btn_cancel_drash").click(function() {
                                $("#drashboard").html("");
                                $("#result").html("");
                            });

                        </script>';
                }
            exit;
        }

    function term_header($dt)
        {
        $sx = '';
        $lang = '<sup>(' . $dt[0]['lg_code'] . ')</sup>';
        $sx .= h($dt[0]['term_name'] . $lang, 3);
        $sx .= '<small>' . lang('thesa.language') . ': ' . $dt[0]['lg_language'] . '</small>';
        $sx .= '<hr class="mb-3">';
        return $sx;
        }

    function ajax_selected()
        {
            $id = get('id');
            if ($id > 0)
                {
                    $dt = $this->le($id);
                    echo $this->term_header($dt);


                    /***** CREATE */
                    echo '<a href="#" id="btn_create_concept" class="btn btn-outline-primary ms-3 me-3 mb-3 btn-fluid" >' . lang('thesa.term_create_concept') . '</a>';

                    /***** EDIT */
                    echo '<a href="#" id="btn_edit_term" class="btn btn-outline-success ms-3 me-3 mb-3 btn-fluid">' . lang('thesa.term_edit') . '</a>';

                    /***** REMOVE */
                    echo '<a href="#" id="btn_remove" class="btn btn-outline-danger ms-3 me-3 mb-3 btn-fluid">'. lang('thesa.term_remove').'</a>';

                    /***** CANCEL */
                    echo '<a href="#" id="btn_cancel" class="btn btn-outline-warning ms-3 me-3 mb-3 btn-fluid">' . lang('thesa.cancel') . '</a>';

                    echo '
                    <script>
                        $("#btn_create_concept").click(function() {
                            var id = '.$id.';
                            var url = "' . PATH . 'admin/terms/ajax_term_concept/' . $id . '";
                            $("#drashboard").load(url);
                        });
                        $("#btn_cancel").click(function() {
                            $("#drashboard").html("");
                            $("#result").html("");
                        });

                    </script>';
                    exit;
                }
            echo "OPS";
            exit;
        }

    function term_drashboard()
        {
        $sx = '';
        $sx .= '<div id="drashboard" style="width: 100%;"></div>';
        return $sx;
        }

    function term_functions()
        {
            $sx = '';
            $sx .= '<div id="result" style="width: 100%;">'.lang('thesa.select_a_term').'</div>';
            $sx .= $this->term_list_script();
            return $sx;
        }

    function term_list_script()
        {
            $sx = '
            <script>
                $("#term_list").change(function() {
                    $("#drashboard").html("");
                    var id = $(this).val();
                    var url = "'.base_url(PATH.'admin/terms/selected').'";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: { id: id },
                        success: function(data)
                        {
                            $("#result").html(data);
                        }
                    });
                });
            </script>';
            return $sx;
        }

    function term_list()
        {
            $sx = '';
            $Thesa = new \App\Models\Thesa\Thesa();
            $ThTermTh = new \App\Models\RDF\ThTermTh();
            $th = $Thesa->setThesa();
            $rlt = $ThTermTh->termNoUse($th);

            $sx .= '<select class="form-control" id="term_list" name="term_list" size="15">';
            for ($r=0;$r < count($rlt);$r++)
                {
                    $sx .= '<option value="'.$rlt[$r]['id_term'].'">'.$rlt[$r]['term_name'].'</option>';
                }
            $sx .= '</select>';
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
