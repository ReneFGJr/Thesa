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
        'id_term', 'term_name', 'term_lang', 'term_th_concept'
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
                echo $d2;
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
                echo lang("thesa.updated_at") . ' ' . date("Y-m-d H:i:s");
                echo '</small>';
                exit;
                break;

            case 'add':
                $sx .= $this->form();
                break;

            default:
                $menu['#TERM'] = 'Terms';
                $menu[PATH . '/admin/terms/add'] = lang('thesa.add_term');

                $ThTermTh = new \App\Models\RDF\ThTermTh();
                $menu[PATH . '/admin/terms/'] = $ThTermTh->total($th) . ' ' . lang('thesa.total_terms');
                $total = $ThTermTh->totalNoUse($th);
                if ($total > 0) {
                    $menu[PATH . '/admin/terms/create_concept'] = $total . ' ' . msg('thesa.total_not_attribuit');
                }


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

    function caditate_prefLabel($id,$langs=array(),$th=0)
        {
            $this
            ->join('thesa_terms_th', 'term_th_term = id_term')
            ->join('language', 'term_lang = id_lg')
            ->where('term_th_thesa', $th)
            ->where('term_th_concept', 0);
            foreach($langs as $lang=>$temp)
                {
                    $this->where('lg_code <> "'.$lang.'"');
                }
            $this->orderBy('term_name', 'ASC');
            $dt = $this->findAll();
            return($dt);
        }

    function form($th = 0)
    {
        $Thesa = new \App\Models\Thesa\Index();
        $th = $Thesa->setThesa();
        $Language = new \App\Models\Thesa\Language();
        $sx = '';
        /*************************************** Serie A */
        $sa = '';
        $sa .= '<small>' . lang('thesa.terms_add') . '</small>';
        $sa .= '<textarea class="form-control" name="terms" id="terms" rows=10 cols=80></textarea>';
        $sa .= $this->btn_append($th);
        $sa .= '&nbsp;|&nbsp;';
        $sa .= $this->btn_close($th);
        $sa .= '<hr>';
        //$sa .= '<a href="'.PATH.COLLECTION. '/terms/create_concept'.'" class="btn btn-outline-secondary">'.msg('thesa.see_term_candidatos').'</a>';

        /**************************************** Serie B */
        $sb = '';
        $sb .= '<small>' . lang('thesa.terms_lang') . '</small>';
        $sb .= $Language->lang_form($th);
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

    function create_concept($d1, $d2, $d3)
    {
        $sa = h(lang('thesa.concepts_add'), 3);
        $sa .= '<div id="term_list_div">';
        $sa .= $this->term_list();
        $sa .= '</div>';
        $sb = $this->term_functions();
        $sc = $this->term_drashboard();

        $sx = bs(
            bsc($sa, 4) .
                bsc($sb, 4) .
                bsc($sc, 4)
        );
        return $sx;
    }

    function show_term_selected($id)
    {

        $idr = explode('|', $id);
        $sa = '';
        for ($r = 0; $r < count($idr); $r++) {
            $dt = $this->le($idr[$r]);

            if (count($idr) == 1) {
                $sa .= $this->term_header($dt);
            } else {
                $dt = (array)$dt[0];
                $sa .= $dt['term_name'] . '. ';
            }
        }
        if (count($idr) > 0) {
            echo h(lang('thesa.terms_selected_to_create'), 3);
            echo h(lang('thesa.terms') . ': ' . $sa, 6);
        } else {
            echo $sa;
        }
    }

    function ajax_term_concept($act, $id = '')
    {
        $TtTermTh = new \App\Models\RDF\ThTermTh();
        $id = get('id');

        echo $this->show_term_selected($id);


        /************************************************************* CONFIRM */

        $confirm = get("confirm");
        if ($confirm == 'yes') {

            $ids = explode('|', trim($id));

            if (count($ids) > 0)
                {
                    $thd = $TtTermTh->le($ids[0]);
                    $th = $thd[0]['term_th_thesa'];
                }


            /*********************************** CREATE CONCEPT */
            $ThConcept = new \App\Models\Thesa\Concepts\Index();
            for ($r = 0; $r < count($ids); $r++) {
                 echo $ThConcept->register($ids[$r], $th);
            }
            echo "OK2";

            /********************************** Messages *******/

            echo bsmessage(lang('thesa.term_concept_creadted'), 1);
            echo '
                    <script>
                        var url = "' . PATH . '/admin/terms/ajax_term_update/' . $id . '";
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

                            var url = "' . PATH . '/admin/terms/ajax_term_concept' . '";
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: { id: "' . $id . '","confirm":"yes" },
                                success: function(data)
                                {
                                    $("#drashboard").html(data);
                                }
                            });
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
        if (count($dt) > 0) {
            $lang = '<sup>(' . $dt[0]['lg_code'] . ')</sup>';
            $sx .= h($dt[0]['term_name'] . $lang, 3);
            $sx .= '<small>' . lang('thesa.language') . ': ' . $dt[0]['lg_language'] . '</small>';
            $sx .= '<hr class="mb-3">';
        } else {
        }
        return $sx;
    }

    function term_block($id, $concept, $th)
    {
        $ThTermTh = new \App\Models\RDF\ThTermTh();
        $data['term_th_concept'] = $concept;

        $ThTermTh->set($data)
            ->where('term_th_term', $id)
            ->where('term_th_thesa', $th)
            ->update();
    }

    function ajax_selected()
    {
        /**************************************************************** SELECTED */
        $id = get('id');
        $id_original = $id;

        if ($id != '') {
            $idr = $id;
            $idr = troca($idr, '[', '');
            $idr = troca($idr, ']', '');
            $idr = troca($idr, '"', '');
            //$idr = troca($idr, ',', ';');
            $id_js = troca($idr, ',', '|');
            $id_js = (string)$id_js;

            echo $this->show_term_selected($id_js);

            /***** CREATE */
            echo '<a href="#" id="btn_create_concept" class="btn btn-outline-primary ms-3 me-3 mb-3 btn-fluid" >' . lang('thesa.term_create_concept') . '</a>';

            /********************************************* UNICO TERMO */
            if (!strpos($id_js, '|')) {
                /***** EDIT */
                echo '<a href="#" id="btn_edit_term" class="btn btn-outline-success ms-3 me-3 mb-3 btn-fluid">' . lang('thesa.term_edit') . '</a>';

                /***** REMOVE */
                echo '<a href="#" id="btn_remove" class="btn btn-outline-danger ms-3 me-3 mb-3 btn-fluid">' . lang('thesa.term_remove') . '</a>';
            } else {
                echo '<span class="hidden" id="btn_edit_term"></span>';
                echo '<span class="hidden" id="btn_remove"></span>';
            }

            /***** CANCEL */
            echo '<a href="#" id="btn_cancel" class="btn btn-outline-warning ms-3 me-3 mb-3 btn-fluid">' . lang('thesa.cancel') . '</a>';

            /***** CREATE ON CLICK*/
            echo '<a href="#" id="btn_create_concept_oneclick" class="btn btn-outline-primary ms-3 me-3 mb-3 btn-fluid" >' . lang('thesa.term_create_concept_onclick') . '</a>';
            echo '
                    <script>
                        $("#btn_cancel").click(function() {
                            $("#drashboard").html("");
                            $("#result").html("");
                        });

                        /**************************************************** CREATE **/
                        $("#btn_create_concept").click(function() {

                            var url = "' . PATH . '/admin/terms/ajax_term_concept' . '";
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: { id: "' . $id_js . '" },
                                success: function(data)
                                {
                                    $("#drashboard").html(data);
                                }
                            });
                        });

                        /****************************************** ONE CLICK CREATE **/
                        $("#btn_create_concept_oneclick").click(function() {

                            var url = "' . PATH . '/admin/terms/ajax_term_concept' . '";
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: { id: "' . $id_js . '", "confirm":"yes" },
                                success: function(data)
                                {
                                    $("#drashboard").html(data);
                                }
                            });
                        });
                    </script>';
            exit;
        }
        echo "OPS - TERM NOT FOUND";
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
        $sx .= '<div id="result" style="width: 100%;">' . lang('thesa.select_a_term') . '</div>';
        $sx .= $this->term_list_script();
        return $sx;
    }

    function term_list_script()
    {
        /***************************************** CHANGE */
        $sx = '
            <script>
                $("#term_list").change(function() {
                    $("#drashboard").html("");
                    var id = JSON.stringify($(this).val());
                    var url = "' . (PATH . '/admin/terms/selected') . '";
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

        $sx .= '<select class="scrollbar" style="width: 100%;" id="term_list" name="term_list" size="15" multiple>';
        for ($r = 0; $r < count($rlt); $r++) {
            $sx .= '<option value="' . $rlt[$r]['id_term'] . '">' . $rlt[$r]['term_name'] . '</option>';
        }
        $sx .= '</select>';
        return $sx;
    }

    /***************************************** AJAX ou API */
    function term_register($d1, $d2, $d3)
    {
        $Thesa = new \App\Models\Thesa\Index();
        $th = $Thesa->setThesa();
        $term = get("terms");
        $term = troca($term, chr(13), ';');
        $term = troca($term, chr(10), ';');
        $terms = explode(';', $term);

        $lang = get("lang");

        for ($r = 0; $r < count($terms); $r++) {
            $trms = $terms[$r];
            if (trim($trms) != '')
            {
                if ($this->register($trms, $lang, $th)) {
                    echo '<div>';
                    echo $trms . ' - ' . lang('thesa.inserted') . '<br/>';
                    echo '</div>';
                } else {
                    echo '<div>';
                    echo $trms . ' - ' . lang('thesa.already') . '<br/>';
                    echo '</div>';
                }
            }
        }
        exit;
    }

    /*********************************************************** REGISTER */
    function register($term, $lang, $th)
    {
        $ThTermTh = new \App\Models\RDF\ThTermTh();
        $dt = $this->where('term_name', $term)->where('term_lang', $lang)->findAll();
        if (count($dt) == 0) {
            $data = array();
            $data['term_name'] = $term;
            $data['term_lang'] = $lang;
            $id = $this->insert($data);
            $ThTermTh->link_th($id, $th);
            return $id;
        } else {
            $id = $dt[0]['id_term'];

            /************************ Vinculo com o tesauro */
            $ThTermTh->link_th($id, $th);
            return $id;
        }
    }

    function btn_append($th)
    {
        $sx = '';
        $sx .= '<span class="btn btn-primary" onclick="new_term(' . $th . ');">';
        $sx .= msg('thesa.add_term');
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
                            let userHtml = "apikey=' . md5('THESA') . '&lang="+lang+"&terms="+items;

                            document.querySelector(\'#result\').innerHTML = "Registrando ...<br>";

                            xhr.open("POST", "' . PATH . '/admin/terms/add_ajax", true);
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
        $sx .= '<a href="' . PATH . '/admin/terms' . '" class="btn btn-outline-warning">' . lang("thesa.return") . '</a>';
        return $sx;
    }

    function btn_close($th)
    {
        $sx = '';
        $sx .= '<a href="#" onclick="wclose();" class="btn btn-outline-warning">' . lang("thesa.close") . '</a>';
        return $sx;
    }
}
