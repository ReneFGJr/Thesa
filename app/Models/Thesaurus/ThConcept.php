<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThConcept extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'th_concept';
    protected $primaryKey           = 'id_c';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        'id_c','c_concept','c_th','c_ativo','c_agency'
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

    function resume($id)
        {
            $dt = $this->where('c_th',$id)->get();
            $rst = $dt->resultID->num_rows;
            return $rst;
        }

    function create_conecpt_literal($term='',$lang='por',$th=0)
        {
            $ThLiteral = new \App\Models\Thesaurus\ThLiteral();
            $sx = '';
            if ($th > 0)
                {
                    $IDL = $ThLiteral->add_term($term,$lang,$th);
                    $IDC = $this->create_conecpt($IDL,$th);
                } else {
                    $sx = bsmessage('Thesaurus não informado');
                    erro($sx);
                    exit;
                }
            return $IDC;
        }

    function create_conecpt($term=0,$th=0)
        {
            $ThConceptTerms = new \App\Models\Thesaurus\ThConceptTerms();
            $ThLiteralTh = new \App\Models\Thesaurus\ThLiteralTh();
            $dt = $ThConceptTerms->where('ct_th',$th)->where('ct_term',$term)->findAll();
            
            if (count($dt) == 0)
            {
                $dt = $this  
                    ->select('max(id_c) as id')
                    ->findAll();            

                $dd['c_concept'] = 'c'.($dt[0]['id']+1);
                $dd['c_th'] = $th;
                $dd['c_ativo'] = 1;
                $dd['c_agency'] = 1;

                $ID = $this->set($dd)->insert();
                $ThConceptTerms->create_concept_term($term,$ID,$th);
                $ThLiteralTh->term_insert($term,$th);
            } else {
                $ID = $dt[0]['ct_concept'];
            }
            return($ID);
        }

    function concepts($th=0)
        {
            $dt = $this
                    ->join('th_concept_term','ct_concept = id_c','inner')
                    ->join('th_literal','ct_term = id_n','inner')
                    ->where('c_th',$th)
                    ->findAll();
            return($dt);
        }

    function le($id)
        {
            $Proprieties = new \App\Models\RDF\Proprieties();
            $prop = $Proprieties->getPropriety('Concept');

            $this->select("id_c, c_concept, n_name, n_lang, ct_propriety, ct_concept, c_th");
            $this->join('th_concept_term','id_c = ct_concept and ct_propriety = '.$prop,'inner');
            $this->join('th_literal','ct_term = id_n');
            $this->where('id_c',$id);
            $this->orderBy('n_name');
            $dt = $this->findAll();
            $dt = $dt[0];
            return($dt);
        }

    function getConcept($id)
        {
            $ThConceptData = new \App\Models\Thesaurus\ThConceptData();
            $dt['concept'] = $this->le($id);
            $dt['data'] = $ThConceptData->getData($id);
            return $dt;
        }

    function show($id)
        {
            return $this->header($id);
            $ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();
        }

    function list($id,$prop='')
        {

        }

    function edit($id)
        {
            $ThAssociate = new \App\Models\Thesaurus\ThAssociate();
            $sa = '';
            $sb = '';
            $sx = $this->header_edit($id);
            $sx .= '<hr>';
            $dt = $this->le($id);
            $dr = $ThAssociate->le($id);

            $sa .= $this->prefLabel_Add($id);
            $sa .= $this->altLabel_Add($id,$dr);
            $sa .= $this->hiddenLabel_Add($id,$dr);

            $sb .= $this->broader_Add($id,$dr);
            $sb .= $this->narrower_Add($id,$dr);
            $sb .= $this->related_Add($id,$dr);
            $sb .= $this->notes_Add($id,$dr);
            
            $img = 'image';

            $sx = $sx . bsc($sa,5).bsc($sb,5);
            $sx .= bsc($img,2);
            $sx = bs($sx);
            return $sx;
        }

    function show_propriety($prop,$dt)
        {
            $sx = '<ul>';
            $t = 0;
            for($r=0;$r<count($dt);$r++)
                {
                    $line = $dt[$r];
                    if ($line['p_group'] == $prop)
                        {
                            $sty = '';
                            $stya = '';
                            $url = PATH.MODULE.'popup/propriety_del/'.$line['id_tg'];
                            $btn = btn_trash_popup($url);

                            if ($line['tg_active'] == 0) 
                                { 
                                    $sty = '<del>'; 
                                    $stya = '</del>'; 
                                    $url = PATH.MODULE.'popup/propriety_undel/'.$line['id_tg'];
                                    $btn = btn_recicle_popup($url);
                                }
                            $link = '<a href="'.PATH.MODULE.'a/'.$line['id_ct'].'" class="thesa '.$sty.'">';
                            $linka = '</a>';
                            $sx .= '<li>';
                            $sx .= $sty.$link.$dt[$r]['n_name'].$linka.$stya;
                            $sx .= ' ';                          
                            $sx .= $btn;
                            
                            //$sx .= '<a href="#" class="text-danger" onclick="if (confirm(\'Excluir\'")) { alert(1); }" >'.bsicone('trash').'</a>';
                            $sx .= '</li>';
                            $t++;
                        }
                }
            $sx .= '</ul>';
            return $sx;
        }

    function notes_Add($id)
        {
            $sx = '';
            $txt = lang('thesa.notes');
            $txt .= '<img src="'.URL.'img/icone/plus.png" width="32">';
            $sx .= onclick(PATH.MODULE.'edit/'.$id.'/notes',800,600);
            $sx .= h($txt,4);        
            $sx .= $this->list($id,'notes');
            $sx .= '</span>';            
            return $sx;
        }         

    function altLabel_Add($id)
        {
            $sx = '';
            $txt = lang('thesa.altLabel');
            $txt .= '<img src="'.URL.'img/icone/plus.png" width="32">';
            $sx .= onclick(PATH.MODULE.'edit/'.$id.'/altLabel',800,600);
            $sx .= h($txt,4);
            $sx .= '</span>';
            
            return $sx;
        }        

    function hiddenLabel_Add($id)
        {
            $sx = '';
            $txt = lang('thesa.hiddenLabel');
            $txt .= '<img src="'.URL.'img/icone/plus.png" width="32">';
            $sx .= onclick(PATH.MODULE.'edit/'.$id.'/hiddenLabel',800,600);
            $sx .= h($txt,4);
            $sx .= '</span>';
            
            return $sx;
        }        

    function prefLabel_Add($id)
        {
            $sx = '';
            $txt = lang('thesa.prefLabel');
            $txt .= '<img src="'.URL.'img/icone/plus.png" width="32">';
            $url = PATH.MODULE.'popup/prefLabel/'.$id;
            $sx .= onclick($url,800,600);
            $sx .= h($txt,4);
            $sx .= 'xx</span>';
            
            return $sx;
        }

    function broader_Add($id,$dt)
        {
            $sx = '';
            $txt = lang('thesa.broader');
            $txt .= '<img src="'.URL.'img/icone/plus.png" width="32">';
            $url = PATH.MODULE.'popup/broader/'.$id;            
            $sx .= onclick($url,800,600);
            $sx .= h($txt,4);
            $sx .= '</span>';
            $sx .= $this->show_propriety('TG',$dt);
            
            return $sx;
        }

    function narrower_Add($id,$dt)
        {
            $sx = '';
            $txt = lang('thesa.narrower');
            $txt .= '<img src="'.URL.'img/icone/plus.png" width="32">';
            $url = PATH.MODULE.'popup/narrower/'.$id;            
            $sx .= onclick($url,800,600);            
            $sx .= h($txt,4);
            $sx .= '</span>';
            $sx .= $this->show_propriety('TE',$dt);
            
            return $sx;
        } 

    function related_Add($id,$dt)
        {
            $sx = '';
            $txt = lang('thesa.related');
            $txt .= '<img src="'.URL.'img/icone/plus.png" width="32">';
            $url = PATH.MODULE.'popup/related/'.$id;            
            $sx .= onclick($url,800,600);            
            $sx .= h($txt,4);
            $sx .= '</span>';
            $sx .= $this->show_propriety('TR',$dt);
            
            return $sx;
        } 

    function header_edit($id)
        {
            $ThConceptTerms = new \App\Models\Thesaurus\ThConceptTerms();
            $sx = '';
            $dt = $ThConceptTerms->prefLabel($id);
            $lab = '<span class="supermall">'.lang('thesa.prefLabel').'</span>';
            $prefs = $lab;
            for ($r=0;$r < count($dt);$r++)
                {
                    $dtt = (array)$dt[$r];
                    $link = '<a href="'.PATH.MODULE.'v/'.$id.'" class="text-primary">';
                    $linka = '</a>';
                    $lang = '';
                    if (count($dt) > 1) 
                        { $lang = ' <sup>('.$dtt['n_lang'].')</sup>'; }
                    $prefs .= '<h3>'.$link.$dtt['n_name'].$lang.$linka.'</h3>';
                }
            /********* Change PrefLabel */
            $prefs .= $this->bt_prefLabel($id);
            /********* Screen ************/                  
            $sx .= bsc($prefs,8);
            $sx .= bsc(
                    $this->bt_remove($id) .' '.
                    $this->bt_concept($id).' '
            ,4,'text-end');
            $sx = bs($sx);
            return $sx;
        }

    function header($dt)
        {
            $id = $dt['concept']['id_c'];           
            $sx = '';

            /**********************************************************************************/
            $link = '<a href="'.PATH.MODULE.'v/'.$id.'" class="text-primary">';
            $linka = '</a>';
            $lang = $dt['concept']['n_lang'];
            if ($lang != '') { $lang = ' <sup>('.$dt['concept']['n_lang'].')</sup>'; }
            $prefs = '<span class="supersmall">'.lang('thesa.prefLabel').'</span>';
            $prefs .= '<br><span class="h3">'.$link.$dt['concept']['n_name'].$lang.$linka.'</span>';

            /********* Copy to ClipBoard */
            $prefs .= '<input type="text" id="cpc" 
                        value="'.PATH.MODULE.'c/'.$id.'" class="small" style="width: 100%; border: 0px;" readonly="">';
            $prefs .= clipboard();      

            /********* Screen ************/                  
            $sx .= bsc($prefs,8);
            $sx .= bsc(
                    $this->bt_editar($id) .' '.
                    $this->bt_concept($id).' '.
                    $this->bt_copy($id) .' '.
                    $this->bt_rdf($id)
            ,4,'text-end mb-4');
            $sx .= bsc('<hr>',12,'mb-4');
            $sx = bs($sx);
            return $sx;
        }

    function bt_prefLabel($id)
        {
            $url = PATH.MODULE.'popup/change_preflabel/'.$id.'/';
            $sx = onclick($url,800,500);
            $sx .= lang('thesa.PrefLabelChange');
            $sx .= '</a>';
            return $sx;
        }

    function bt_editar($id)
        {
            $sx = '<a href="'.PATH.MODULE.'a/'.$id.'" 
                class="btn btn-outline-secondary">editar</a>';
            return($sx);
        }
    function bt_concept($id)
        {
            $sx = '<a href="'.PATH.MODULE.'v/'.$id.'"
                        class="btn btn-outline-secondary">thesa:c'.$id.'</a>';
            return($sx);
        }
    function bt_copy($id)
        {
            $sx = '<button class="btnc btn btn-outline-secondary" data-clipboard-target="#cpc" 
                title="Copiar para clipboard" onclick="copytoclipboard(\'cpc\');">
                <img src="https://www.ufrgs.br/tesauros/img/icone/copy.png" height="18">
            </button>';
            return($sx);
        }

    function bt_rdf($id)
        {
            $sx = ' <a href="'.PATH.MODULE.'c/'.$id.'/rdf" 
                    class="btn btn-outline-secondary" title="Arquivo RDF">
                        <img src="'.URL.'img/icone/rdf_w3c.svg" height="18">
                    </a>';
            return $sx;
        } 
    function bt_remove($id)
        {
            $url = PATH.MODULE.'c/'.$id.'/remove';
            $sx = onclick($url,800,300);
            $sx .= '<img src="'.URL.'img/icone/exclud.png" height="32" title="Remove">
                    </a>';
            return $sx;
        }               
    function image($id)
        {
            $img = URL.'img/no_image.jpg';
            $img = '<img src="'.$img.'" class="img-fluid">';
            return $img;
        }

    function TermLetter($id,$lt)
        {
            $Proprieties = new \App\Models\RDF\Proprieties();
            $prop = $Proprieties->getPropriety('Concept');
            $this->select("id_c as id_c, c_concept as c_concept, rl1.n_name, rl2.n_name as name2, rl1.n_lang as n_lang, rl2.n_lang as n_lang2, lt1.ct_propriety, lt1.ct_concept, lt1.ct_use ");
            $this->join('th_concept_term as lt1','id_c = ct_concept');
            $this->join('th_literal as rl1','lt1.ct_term = rl1.id_n');
            $this->join('th_concept_term as lt2','(lt1.ct_concept = lt2.ct_concept) and (lt2.ct_propriety = '.$prop.')');
            $this->join('th_literal as rl2','lt2.ct_term = rl2.id_n');
            $this->where('c_th',$id);
            $this->where('c_ativo',1);
            $this->like('rl1.n_name',$lt, 'after');
            $this->orderBy('rl1.n_name');
            $dt = $this->findAll();
            
            $sx = '<ul>';
            foreach($dt as $id=>$line)
                {
                    $link = '<a href="'.(PATH.MODULE.'v/'.$line['ct_concept']).'">';
                    $linka = '</a>';
                    $sx .= '<li>'.
                                $link.$line['n_name'].'<sup> ('.$line['n_lang'].')</sup>';
                                if (trim($line['n_name']) != trim($line['name2']))
                                    {
                                        $sx .= ' <i><b>USE</b></i> '.$line['name2'].'<sup> ('.$line['n_lang2'].')</sup>';
                                    }                                
                                $sx .= $linka.'</li>';
                }
            $sx .= '</ul>';
            return $sx;
        }

    function TermQuery($id,$lt)
        {
            $this->select("id_c as id_c, c_concept as c_concept, rl1.n_name, rl2.n_name as name2, rl1.n_lang as n_lang, rl2.n_lang as n_lang2, lt1.ct_propriety, lt1.ct_concept, lt1.ct_use ");
            $this->join('th_concept_term as lt1','id_c = ct_concept');
            $this->join('th_literal as rl1','lt1.ct_term = rl1.id_n');
            $this->join('th_concept_term as lt2','(lt1.ct_concept = lt2.ct_concept) and (lt2.ct_propriety = 25)');
            $this->join('th_literal as rl2','lt2.ct_term = rl2.id_n');
            $this->where('c_th',$id);
            $this->where('c_ativo',1);
            $this->like('rl1.n_name',$lt, 'after');
            $this->orderBy('rl1.n_name');
            $dt = $this->findAll();
            
            $sx = '<ul>';
            foreach($dt as $id=>$line)
                {
                    $link = '<a href="'.(PATH.MODULE.'v/'.$line['ct_concept']).'">';
                    $linka = '</a>';
                    $sx .= '<li>'.
                                $link.$line['n_name'].'<sup> ('.$line['n_lang'].')</sup>';
                                if (trim($line['n_name']) != trim($line['name2']))
                                    {
                                        $sx .= ' <i><b>USE</b></i> '.$line['name2'].'<sup> ('.$line['n_lang2'].')</sup>';
                                    }                                
                                $sx .= $linka.'</li>';
                }
            $sx .= '</ul>';
            return $sx;
        }        

    function search($id)
        {
            $sx = '
                    <form class="form-busca-site" action="'.PATH.MODULE.'th/'.$id.'/">
                        <input class="btn-text-top" type="text" name="q" placeholder="'.lang('thesa.search').'">
                        <div><button class="btn-buscar-top" type="submit"></button></div>
                    </form>
                   ';

            $sx .= "<style>
                    .btn-text-top {
                    background-color: #f5f6fa;
                    border: 1px solid rgba(255, 255, 255, .1);
                    padding: 15px 30px 15px 40px;
                    border-radius: 20px;
                    width: 100%!important;
                    height: 42px;
                    font-weight: 300;
                    color: #8795a2;
                    }
                        
                    .btn-buscar-top {
                    width: 20px!important;
                    height: 22px;
                    background: url(".URL."img/icone/buscar_grey.png) no-repeat;
                    cursor: pointer!important;
                    border: none;
                    transform: translateY(-50%);
                    padding: 0;
                    position: relative;
                    top: -20px;
                    left: 10px;
                    }
                    </style>";
            return $sx;
        }
    function paginations($id)
        {
            $this->select("substr(n_name,1,1) as ltr");
            $this->join('th_concept_term','id_c = ct_concept');
            $this->join('th_literal','ct_term = id_n');            
            $this->where('c_th',$id);
            $this->where('c_ativo',1);
            $this->groupBy('ltr');
            $this->orderBy('ltr');
            $dt = $this->findAll();
            $sx = '';
            //$sx .= '<div aria-label="Page navigation example2">';
            $sx .= '<ul class="pagination">';
            $sx .= '<li>'.lang('thesa.pagination').'</li>';
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $ltd = mb_strtoupper(ascii($line['ltr']));
                    $sx .= '<li class="page-item me-1"><a class="page-link" href="'.(PATH.MODULE.'th/'.$id.'/'.$line['ltr']).'">'.$ltd.'</a></li>'.cr();
                }
            $sx .= '</ul>';
            return $sx;
        }
}
