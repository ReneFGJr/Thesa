<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThLiteral extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'th_literal';
    protected $primaryKey           = 'id_n';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        'id_n','n_name','n_lang'
    ];

    protected $typeFields        = [
        'hidden','string:100','string:10'
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

    function le($id)
        {
            $ThConceptTerms = new \App\Models\Thesaurus\ThConceptTerms();
            $dt = $ThConceptTerms->le($id);
            return $dt;
        }

    function resume($id)
        {
            $this->select("count(*) as total, n_lang");
            $this->join('th_concept_term','ct_term = id_n');
            $this->groupBy('n_lang');
            $dt = $this->where('ct_th',$id)->findAll();
            $rst = count($dt);
            return $rst;
        }

    function label_update($id,$tp)
        {
            pre($_SESSION);
            $ThLiteralTh = new \App\Models\Thesaurus\ThLiteralTh();
            $ThLiteralTh->terms($th);
        }


    function add_term($term,$lang,$th)
        {
            $term = mb_strtolower($term);
            $term = ucfirst($term);
            $dt = $this
                    ->where('n_name',$term)
                    ->where('n_lang',$lang)
                    ->findAll();                

            if (count($dt) == 0)
                {
                    $data['n_name'] = $term;
                    $data['n_lang'] = $lang;
                    $data['rl_type'] = 24;
                    $this->set($data)->insert();
                    $idt = $this->insertID();

                    if ($th > 0)
                        {
                            $ThLiteralTh = new \App\Models\Thesaurus\ThLiteralTh();
                            $ThLiteralTh->term_insert($idt,$th);
                        }
                    $this->new = 1;
                } else {
                    $idt = $dt[0]['id_n'];
                    $this->new = 0;
                }
            return($idt);
        }        

    function term_concept($id,$id2)
        {
            $dt = $this
                ->join('th_literal_th','lt_term = id_n')
                ->join('th_concept_term','ct_term = id_n and ct_th = lt_th','left')
                ->where('id_n',$id)
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
                        
                        $th = $dt['lt_th'];
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

                $idr = $dt['id_n'];

                $sa = h($dt['n_name'],2);
                $sa .= h($dt['n_lang'],8);

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
            $ThLiteralTh = new \App\Models\Thesaurus\ThLiteralTh();
            $dt = $ThLiteralTh->term_list($th);            
            
            $sx = '<ul>';
            $xlang = '';
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = (array)$dt[$r];
                    $lang = $line['n_lang'];
                    if ($xlang != $lang)
                        {
                            $sx .= h($lang,4);
                            $xlang = $lang;
                        }
                    $sx .= '<li>';
                    $sx .= onclick(PATH.MODULE.'popup/associate/'.$line['id_n'],800,400).$line['n_name'].'</span>';
                    $sx .= '</li>';
                }
            $sx .= '</ul>';
            return $sx;
        }

    function term_insert($term,$language,$th)
        {
            $dt = $this->where('n_name',$term)->where('n_lang',$language)->findAll();
            if (count($dt) > 0)
                {
                    /******* OLD */
                    $dt = $dt[0];
                    $id = $dt['id_n'];
                    $dt['new'] = false;
                    $idt = $dt['id_n'];
                } else {
                    /******* NOVO */
                    $dt['n_name'] = $term;
                    $dt['n_lang'] = $language;
                    $dt['n_type'] = 0;
                    $id = $this->insert($dt);
                    $dt['id_n'] = $id;
                    $dt['new'] = true;
                    $idt = $id;
                }    

            $ThLiteralTh = new \App\Models\Thesaurus\ThLiteralTh();
            return $ThLiteralTh->term_insert($idt,$th);
        }
    function form($th)
        {
            $Language = new \App\Models\Language\Index();
            $ThLiteral = new \App\Models\Thesaurus\ThLiteral();
            
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

                                            $new = $this->term_insert($term,$language,$th);
                                            if ($new == 1)
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

            $url = PATH.MODULE;
            $form .= form_open($url.'term/'.$th.'/add');
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
