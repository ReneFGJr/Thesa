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
