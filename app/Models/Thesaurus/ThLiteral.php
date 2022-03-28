<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThLiteral extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'rdf_literal';
    protected $primaryKey           = 'id_rl';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        'id_rl','rl_value','rl_lang','rl_type'
    ];

    protected $typeFields        = [
        'hidden','string:100','string:10','string:100'
    ];    

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];

    var $new = 0;

    function resume($id)
        {
            $this->select("count(*) as total, rl_lang");
            $this->join('th_concept_term','ct_term = id_rl');
            $this->groupBy('rl_lang');
            $dt = $this->where('ct_th',$id)->findAll();
            $rst = count($dt);
            return $rst;
        }

    function term_insert($term,$lang,$th)
        {
            $term = mb_strtolower($term);
            $term = ucfirst($term);
            $dt = $this
                    ->where('rl_value',$term)
                    ->where('rl_lang',$lang)
                    ->findAll();                

            if (count($dt) == 0)
                {
                    $data['rl_value'] = $term;
                    $data['rl_lang'] = $lang;
                    $data['rl_type'] = 24;
                    $this->set($data)->insert();
                    $idt = $this->insertID();
                    $this->new = 1;
                } else {
                    $idt = $dt[0]['id_rl'];
                    $this->new = 0;
                }

            /***************************** Related */
            $sql = "select * from rdf_literal_th where lt_term = $idt and lt_thesauros = $th";
            $dt = $this->db->query($sql)->getResult();
            if (count($dt) == 0)
                {
                    $sql = "insert into rdf_literal_th
                                (lt_term, lt_thesauros, lt_status)
                                values
                                ($idt, $th, 1)";
                    $this->db->query($sql);
                }
            return($idt);
        }

    function term_concept($id,$id2)
        {
            $dt = $this->select('*')
                ->join('rdf_literal_th','lt_term = id_rl')
                ->where('lt_term',$id)
                ->findAll();

            switch($id2)
                {
                    case 'del':
                        $ThLiteralTh = new \App\Models\Thesaurus\ThLiteralTh();
                        $ThLiteralTh->where('lt_term',$id)->delete();
                        $sx = wclose();
                        return $sx;
                        break;
                    break;
                    case 'edit':
                        $this->id = $id;
                        $this->path = PATH.MODULE.'popup/associate/'.$id;
                        $this->path_back = PATH.MODULE.'popup/associate/'.$id;
                        $sx = form($this);
                        $sx = bs(bsc($sx,12));
                        return $sx;
                        break;
                    case 'concept':
                        $dt = $dt[0];
                        $th = $dt['lt_thesauros'];
                        $idc = $dt['lt_term'];
                        $ThConcept = new \App\Models\Thesaurus\ThConcept();
                        $ThConcept->create_conecpt($idc,$th);
                        $sx = wclose();
                        return $sx;
                    break;
                }

            /************************************************************************************/
            $sx = '';


            if (count($dt) > 0)
            {
                $dt = $dt[0];

                $idr = $dt['id_rl'];

                $sa = h($dt['rl_value'],2);
                $sa .= h($dt['rl_lang'],8);

                $sb = '';
                $sb .= '<a href="'.PATH.MODULE.'popup/associate/'.$idr.'/concept" class="btn btn-primary mt-4" style="width: 100%;">';
                $sb .= lang('thesa.concept_create');
                $sb .= '</a>';

                $sb .= '<a href="'.PATH.MODULE.'popup/associate/'.$idr.'/edit" class="btn btn-warning mt-4" style="width: 100%;">';
                $sb .= lang('thesa.term_edit');
                $sb .= '</a>';

                $sb .= '<a href="'.PATH.MODULE.'popup/associate/'.$idr.'/del" class="btn btn-danger mt-4" style="width: 100%;">';
                $sb .= lang('thesa.term_exclude');
                $sb .= '</a>';

                $sx = bsc($sa,8);
                $sx .= bsc($sb,4);
                $sx = bs($sx);
            }
            return $sx;
        }


    function associate($th)
        {
            $sql = "SELECT * FROM `rdf_literal_th`
                    left join th_concept_term ON ct_term = lt_term
                    inner join rdf_literal ON lt_term = id_rl
                    where id_ct is null
                    and lt_thesauros = $th
                    order by rl_lang, rl_value";
            $dt = $this->db->query($sql)->getResult();

            $sx = '<ul>';
            $xlang = '';
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = (array)$dt[$r];
                    $lang = $line['rl_lang'];
                    if ($xlang != $lang)
                        {
                            $sx .= h($lang,4);
                            $xlang = $lang;
                        }
                    $sx .= '<li>';
                    $sx .= onclick(PATH.MODULE.'popup/associate/'.$line['id_rl'],800,400).$line['rl_value'].'</span>';
                    $sx .= '</li>';
                }
            $sx .= '</ul>';
            return $sx;
        }
    function form($th)
        {
            $Language = new \App\Models\Language\Index();
            $sa = '';
            $sb = '';
            $sx = '';
            $form = '';
            $info = h(lang('thesa.term_add_title_help'),4);
            $info .= '<p>'.lang('thesa.term_add_help').'</p>';
            $form .= h(lang('thesa.term_add'),4);

            /************************ CHECK */
            $terms = get("terms");
            $language = get("language");
            $action = get("action");

            if ($action != '')
                {
                    if ($terms != '')
                        {
                            if ($language != '')
                                {
                                    $terms = troca($terms,chr(13),';');
                                    $terms = troca($terms,chr(10),';');
                                    $terms = explode(';',$terms);
                                    $sx .= '<ul>';
                                    for ($r=0;$r < count($terms);$r++)
                                        {
                                            $term = mb_strtolower($terms[$r]);
                                            $term = ucfirst($term);
                                            if (strlen($term) > 0)
                                            {
                                            $this->term_insert($term,$language,$th);

                                            if ($this->new == 1)
                                                {
                                                    $sx .= '<li><b>'.$term.'</b> <span class="text-primary">'.lang('thesa.inserted').'</span></li>';
                                                } else {
                                                    $sx .= '<li><b>'.$term.'</b> <span class="text-danger">'.lang('thesa.already').'</span>'.'</li>';
                                                }
                                            }
                                        }
                                    $sx .= '</ul>';
                                    return $sx;
                                } else {
                                    $form .= bsmessage(lang('thesa.select_language'),3);
                                }
                        }
                }

            $form .= form_open();
            $form .= '<table width="100%">';

            /* Header */
            $form .= '<tr><th width="15%"></th><th width="85%"></th></tr>';

            /* Submit */
            $form .= '<tr><td></td>';
            $form .= '<td>'.form_submit(array('name'=>'action','class'=>'btn btn-outline-primary mb-4','value'=>lang('thesa.term_add_submit'))).'</td></tr>';

            /* TextArea */
            $form .= '<tr><td class="text-end" valign="top">'.lang('thesa.terms'). '<span class="text-danger">*</span>'.'</td>';
            $form .= '<td>';
            $form .= form_textarea(array('name'=>'terms','value'=>get("terms"),'type'=>'textarea','class'=>'form-control','rows'=>'10'));
            $form .= '</td></tr>';

            /* Language */
            $form .= '<tr><td class="text-end" valign="top">'.lang('thesa.language'). '<span class="text-danger">*</span>'.'</td>';
            $form .= '<td>';
            $form .= $Language->radio($th,'language');
            $form .= '</td></tr>';
            $form .= '</table>';
            $form .= form_close();

            $sa .= h(lang('thesa.term_insertion'),4);
            $sx .= bsc($form,9);
            $sx .= bsc($info,3);
            
            return $sx;
        }
}
