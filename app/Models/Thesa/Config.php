<?php

namespace App\Models\Thesa;

use CodeIgniter\Model;

class Config extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'configs';
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

    function ajax_save($th,$class,$txt)
        {
            $Description = new \App\Models\Thesa\Descriptions();
            $Description->register($th,$class,$txt);
        }


    function control($class)
    {
        $Thesa = new \App\Models\Thesa\Thesa();
        $th = $Thesa->setThesa();

        $sx = '';
        $sx .= '<a name="' . $class . '"></a>';
        $sx .= '<h1>' . lang('thesa.' . $class) . '</h1>';
        switch ($class) {
            case 'License':
                $Licences = new \App\Models\Thesa\Licences();
                $sx .= $Licences->radiobox($th);
                break;
            case 'Introduction':
                $sx .= $this->form($class, $th);
                break;
            case 'Methodology':
                $sx .= $this->form($class, $th);
                break;
            case 'Audience':
                $sx .= $this->form($class, $th);
                break;
            case 'Language':
                $sx .= '<div class="row" id="language">'.$this->form_language($th). '</div>';
                break;
            default:
                $sx .= '<p>Information not found - ' . $class . '</p>';
                break;
        }
        $sx .= $this->js();
        return $sx;
    }

    function js()
        {
            $sx = '';
            global $js;
            if (!isset($js)) {
                $sx .= '
                <script>
                function form_save($class,$th)
                    {
                        $("#btn_save_"+$class).html("'.lang('thesa.saving'). '...");
                        var url =  "' . base_url(PATH . COLLECTION . '/ajax_docs/save') . '";
                        var $txt = $("#"+$class).val();
                        alert($txt);
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: { th: $th , class: $class, txt: $txt },
                            success: function(data)
                            {
                                $("#status_"+$class).html(data);
                            }
                        });
                        $("#btn_save_"+$class).html("' . lang('thesa.save'). '");
                    }
                </script>
                ';
                $js = true;
            }
            return $sx;
        }

    function form_language($th)
        {
            $sx = '';
            $sx .= (lang('thesa.Language_help'));

            $Language = new \App\Models\Language\Index();
            $dt = $Language
                ->select('lg_code,lg_language,count(*) as total')
                ->join('thesa_language', 'id_lg = lgt_language', 'left')
                ->where('lgt_th', $th)
                ->groupBy('lg_code,lg_language')
                ->orderBy('lg_language', 'ASC')
                ->findAll();

            $ds = array();
            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $ds[$line['lg_code']] = $line['lg_language'];
                }
            /****************** IDIOMAS EXISTENTES */
            $s1 = '<span class="small">'.lang('thesa.language_to_selected'). '</span>';
            $s1 .= '<select id="lang_in" name="lang_in" class="form-control" size=10>';
            foreach ($ds as $code => $lang_name) {
                $s1 .= '<option value="' . $code . '">' . $lang_name . '</option>';
            }
            $s1 .= '</select>';

            /****************** IDIOMAS PARA ATIVAR */
            $dl = $Language->languages();
            $s2 = '<span class="small">'.lang('thesa.language_to_select') . '</span>';
            $s2 .= '<select id="lang_out" name="lang_out" class="form-control" size=10>';
            foreach($dl as $code=>$lang_name)
                {
                    if (!isset($ds[$code]))
                    {
                        $s2 .= '<option value="' . $code . '">' . $lang_name . '</option>';
                    }

                }
            $s2 .= '</select>';

            $sx .= '<div class="row">';
            $sx .= '<div class="col-md-5">'.$s2.'</div>';
            $sx .= '<div class="col-md-2">';
            $sx .= '<button id="lang_select" type="button" class="btn btn-outline-secondary btn-sm form-control mt-5">>>></button>';
            $sx .= '<button id="lang_unselect" type="button" class="btn btn-outline-secondary btn-sm form-control mt-5"><<<</button>';
            $sx .= '</div>';
            $sx .= '<div class="col-md-5">' . $s1 . '</div>';
            $sx .= '</div>';
            $sx .= '
            <script>
                $("#lang_select").click(function() {
                    $vlr = $("#lang_out option:selected").val()
                    var url = "' . PATH . '/admin/ajax_lang/?th='.$th. '&lang="+$vlr;
                    $("#language").load(url);
                });

                $("#lang_unselect").click(function() {
                    $vlr = $("#lang_in option:selected").val()
                    var url = "' . PATH . '/admin/ajax_lang/?th=' . $th . '&act=del&lang="+$vlr;
                    $("#language").load(url);
                });
            </script>';

            return $sx;
        }

    function form($class, $th)
    {
        $Description = new \App\Models\Thesa\Descriptions();
        $dt = $Description->where('ds_th', $th)->where('ds_prop', $class)->findAll();
        $vlr = '';
        for ($r=0;$r < count($dt);$r++)
            {
                $vlr .= $dt[$r]['ds_descrition'].cr();
            }

        $sx = form_open($class);
        $sx .= lang('thesa.' . $class . '_help');
        $sx .= '<textarea name="' . $class . '" id="' . $class . '" class="form-control" rows="10">';
        $sx .= $vlr;
        $sx .= '</textarea>';
        $sx .= '<input type="hidden" name="class" value="' . $class . '">';

        $sx .= '<span id="btn_save_'.$class.'" class="btn btn-outline-primary" onclick="form_save(\''.$class.'\',\''.$th.'\');">';
        $sx .= lang('thesa.save');
        $sx .= '</span>';

        $sx .= '<span id="btn_cancel" class="ms-2 btn btn-outline-danger" onclick="form_cancel("' . $class . '","' . $th . '");">';
        $sx .= lang('thesa.cancel');
        $sx .= '</span>';

        $sx .= form_close();

        $sx .= '<div id="status_'.$class.'">...</div>';

        $sx .= '<br/>';
        $sx .= '<br/>';

        return $sx;
    }
}
