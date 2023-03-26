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
                $Licenses = new \App\Models\Thesa\Licenses();
                $sx .= $Licenses->radiobox($th);
                break;
            case 'Title':
                $sx .= $this->field($class, $th);
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
            case 'ISBN':
                $sx .= $this->form_string($class, $th);
                break;
            case 'Icons':
                $sx .= $this->form_icon($class, $th);
                break;
            case 'Image':
                $sx .= $this->form_string($class, $th);
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
                        var url =  "' . (PATH . COLLECTION . '/ajax_docs/save') . '";
                        var $txt = $("#"+$class).val();

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

    function form_icon($class,$th)
        {
            $sx = '';
            $Thesa = new \App\Models\Thesa\Index();
            $Icons = new \App\Models\Thesa\Icone();

            $dt = $Thesa->le($th);
            $img = $Icons->icone($dt);

            $img = '<img src="'.$img.'" class="img-fluid">';

            $sa = '<span onclick="newwin(\''.PATH.'admin/icone/th/'.$th.'\',800,600);" class="btn btn-outline-secondary link">';
            $sa .= 'Alterar icone';
            $sa .= '</span>';

            $sx .= bsc($img,2).bsc($sa,10);
            return $sx;
        }

    function form_field($class, $th, $value)
        {
            $sx = '';

            /* FIELD */
            $sx .= '<style> div { border: 1px solid #00000;"} </style>';
            $sx .= '<span class="small mt-3">' . lang('thesa.'.$class) . '</span>';

            /* Link */
            $lk = '<span class="ms-2" onclick="togglet(\''.$class.'\');">' . bsicone('edit', 16) . '</span>';

            /* SHOW */
            $sx .= '<div id="status_' . $class . '">';
            $sx .= h($value.$lk,5);
            $sx .= '</div>';

            $sx .= '<div class="input-group mb-3" id="form_' . $class . '" style="display: none;">';
            $sx .= form_input($class, $value, 'id="'. $class.'" class="form-control"');
            $sx .= '    <div class="input-group-append  ms-2">';
            $sx .= '    <button class="btn btn-outline-primary" type="button" onclick="form_field_save(\''.$class.'\','.$th.');">' . lang('thesa.save') . '</button>';
            $sx .= '    <button class="btn btn-outline-danger" type="button" onclick="togglet(\'' . $class . '\');">' . lang('thesa.cancel') . '</button>';
            $sx .= '    </div>';
            $sx .= '</div>';
            return $sx;

        }

    function field($cp,$th)
        {
            $sx = '';
            switch($cp)
                {
                    case 'Title':
                        $Thesa = new \App\Models\Thesa\Thesa();
                        $dt = $Thesa->find($th);

                        $title = $dt['th_name'];
                        $achronic = $dt['th_achronic'];
                        $sx .= '<div class="row">';

                        /* Title */
                        $sx .= $this->form_field('title',$th,$title);

                        /* Achronic */
                        $sx .= $this->form_field('achronic', $th, $achronic);
                }
            $sx .= "$cp - $th";
            return $sx;
        }

    function form_language($th)
        {
            $sx = '';
            $sx .= (lang('thesa.Language_help'));

            $Language = new \App\Models\Thesa\Language();
            $dt = $Language
                ->select('id_lgt, lg_code,lg_language,count(*) as total, lgt_th')
                ->join('language', 'id_lg = lgt_language and lgt_th = '.$th, 'right')
                ->groupBy('id_lgt, lg_code,lg_language, lgt_th')
                ->orderBy('id_lgt, lg_language', 'ASC')
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
            $s2 = '';
            foreach($dt as $id=>$line)
            {
                $code = $line['lg_code'];
                $lang_name = $line['lg_language'];
                if ($line['lgt_th'] != '')
                    {
                        $s1 .= '<option value="' . $code . '">' . $lang_name . '</option>';
                    } else {
                        $s2 .= '<option value="' . $code . '">' . $lang_name . '</option>';
                    }
            }
            $s1 .= '</select>';
            /****************** IDIOMAS PARA ATIVAR */
            $dl = $Language
                ->join('language', 'id_lg = lgt_language', 'left')
                ->orderby('lgt_order')
                ->findAll();

            $s0 = '<span class="small">'.lang('thesa.language_to_select') . '</span>';
            $s0 .= '<select id="lang_out" name="lang_out" class="form-control" size=10>';
            $s0 .= $s2;
            $s0 .= '</select>';

            $sx .= '<div class="row">';
            $sx .= '<div class="col-md-5">'.$s0.'</div>';
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

    function form_string($class, $th)
    {
        $Description = new \App\Models\Thesa\Descriptions();
        $dt = $Description->where('ds_th', $th)->where('ds_prop', $class)->findAll();
        $vlr = '';
        for ($r = 0; $r < count($dt); $r++) {
            $vlr .= $dt[$r]['ds_descrition'] . cr();
        }

        $sx = form_open($class);
        $sx .= lang('thesa.' . $class . '_help');
        $sx .= '<input type="text" name="' . $class . '" id="' . $class . '" class="form-control"';
        $sx .= ' value="' . $vlr . '">';
        $sx .= '<input type="hidden" name="class" value="' . $class . '">';

        $sx .= '<span id="btn_save_' . $class . '" class="btn btn-outline-primary" onclick="form_save(\'' . $class . '\',\'' . $th . '\');">';
        $sx .= lang('thesa.save');
        $sx .= '</span>';

        $sx .= '<span id="btn_cancel" class="ms-2 btn btn-outline-danger" onclick="form_cancel("' . $class . '","' . $th . '");">';
        $sx .= lang('thesa.cancel');
        $sx .= '</span>';

        $sx .= form_close();

        $sx .= '<div id="status_' . $class . '">...</div>';

        $sx .= '<br/>';
        $sx .= '<br/>';

        return $sx;
    }
}
