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

    function create_conecpt($term=0,$th=0)
        {
            $ThConceptTerms = new \App\Models\Thesaurus\ThConceptTerms();
            $dt = $this  
                ->select('max(id_c) as id')
                ->findAll();            

            $dd['c_concept'] = 'c'.($dt[0]['id']+1);
            $dd['c_th'] = $th;
            $dd['c_ativo'] = 1;
            $dd['c_agency'] = 1;

            $ID = $this->set($dd)->insert();

            $ThConceptTerms->create_concept_term($term,$ID,$th);
            return($ID);
        }

    function le($id)
        {
            $this->select("id_c, c_concept, n_name, n_lang, ct_propriety, ct_concept, c_th");
            $this->join('th_concept_term','id_c = ct_concept');
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

    function concepts_associates($th,$id)
        {
            $ThRelations = new \App\Models\Thesaurus\ThRelations();
            $cp = array($id);
            
            
        }

    function boarder($id)
        {
            
        }

    function show($id)
        {
            return $this->header($id);
            $ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();
        }

    function list($id,$prop='')
        {
        /*****************************************************************/
        $th = 64;
        /*****************************************************************/
        $th = round(sonumero($th));
        $sql = "select * from th_concept_term as t1
        INNER JOIN th_literal ON id_rl = t1.ct_term
        INNER JOIN rdf_resource ON id_rs = t1.ct_propriety
        INNER JOIN rdf_prefix on id_prefix = rs_prefix
        WHERE t1.ct_concept = $id and t1.ct_th = $th and rs_group = '$prop'
        order by rs_group ";
        
        $rlt = (array)$this ->query($sql)->getResult();
        if (count($rlt) > 0) {
            /* Read BT */
            return "OK";
            exit;
            return ($rlt);
        } else {
            return "NONE";
            exit;
            return ( array());
        }
    }          

    function edit($id)
        {
            $sa = '';
            $sb = '';
            $sx = $this->header_edit($id);

            $sa .= $this->prefLabel_Add($id);
            $sa .= $this->broader_Add($id);
            $sa .= $this->narrower_Add($id);

            $sb .= $this->notes_Add($id);
            $sb .= $this->altLabel_Add($id);
            $sb .= $this->hiddenLabel_Add($id);
            $sx = $sx . bs($sa,6).bs($sb,6);
            $sx = bs($sx);
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
            
            return $sx;
        }         

    function altLabel_Add($id)
        {
            $sx = '';
            $txt = lang('thesa.altLabel');
            $txt .= '<img src="'.URL.'img/icone/plus.png" width="32">';
            $sx .= onclick(PATH.MODULE.'edit/'.$id.'/altLabel',800,600);
            $sx .= h($txt,4);
            
            return $sx;
        }        

    function hiddenLabel_Add($id)
        {
            $sx = '';
            $txt = lang('thesa.hiddenLabel');
            $txt .= '<img src="'.URL.'img/icone/plus.png" width="32">';
            $sx .= onclick(PATH.MODULE.'edit/'.$id.'/hiddenLabel',800,600);
            $sx .= h($txt,4);
            
            return $sx;
        }        

    function prefLabel_Add($id)
        {
            $sx = '';
            $txt = lang('thesa.prefLabel');
            $txt .= '<img src="'.URL.'img/icone/plus.png" width="32">';
            $sx .= onclick(PATH.MODULE.'edit/'.$id.'/prefLabel',800,600);
            $sx .= h($txt,4);
            
            return $sx;
        }

    function broader_Add($id)
        {
            $sx = '';
            $txt = lang('thesa.broader');
            $txt .= '<img src="'.URL.'img/icone/plus.png" width="32">';
            $sx .= onclick(PATH.MODULE.'edit/'.$id.'/broader',800,600);
            $sx .= h($txt,4);
            
            return $sx;
        }

    function narrower_Add($id)
        {
            $sx = '';
            $txt = lang('thesa.narrower');
            $txt .= '<img src="'.URL.'img/icone/plus.png" width="32">';
            $sx .= onclick(PATH.MODULE.'edit/'.$id.'/narrower',800,600);
            $sx .= h($txt,4);
            
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

    function header($id)
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
            ,4,'text-end');
            $sx = bs($sx);
            return $sx;
        }

    function bt_prefLabel($id)
        {
            $url = PATH.MODULE.'a/'.$id.'/change_preflabel';
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
                        class="btn btn-outline-secondary">thesa:c20679</a>';
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
            $this->select("id_c as id_c, c_concept as c_concept, rl1.n_name, rl2.n_name as name2, rl1.n_lang as n_lang, rl2.n_lang as n_lang2, lt1.ct_propriety, lt1.ct_concept, lt1.ct_use ");
            $this->join('th_concept_term as lt1','id_c = ct_concept');
            $this->join('th_literal as rl1','lt1.ct_term = rl1.id_rl');
            $this->join('th_concept_term as lt2','(lt1.ct_concept = lt2.ct_concept) and (lt2.ct_propriety = 25)');
            $this->join('th_literal as rl2','lt2.ct_term = rl2.id_rl');
            $this->where('c_th',$id);
            $this->where('c_ativo',1);
            $this->like('rl1.n_name',$lt, 'after');
            $this->orderBy('rl1.n_name');
            $dt = $this->findAll();
            //echo $this->db->getLastQuery();
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
            $Proprieties = new \App\Models\RDF\Proprieties();
            $prop = $Proprieties->getPropriety('prefLabel');

            $this->select("id_c as id_c, c_concept as c_concept, rl1.n_name, rl2.n_name as name2, rl1.n_lang as n_lang, rl2.n_lang as n_lang2, lt1.ct_propriety, lt1.ct_concept, lt1.ct_use ");
            $this->join('th_concept_term as lt1','id_c = ct_concept');
            $this->join('th_literal as rl1','lt1.ct_term = rl1.id_rl');
            $this->join('th_concept_term as lt2','(lt1.ct_concept = lt2.ct_concept)'); //and (lt2.ct_propriety = '.$prop.')');
            $this->join('th_literal as rl2','lt2.ct_term = rl2.id_rl');
            $this->where('c_th',$id);
            $this->where('c_ativo',1);
            $this->like('rl1.n_name',$lt, 'after');
            $this->orderBy('rl1.n_name');
            $dt = $this->findAll();
            //echo $this->db->getLastQuery();
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
            $this->join('th_literal','ct_term = id_rl');            
            $this->where('c_th',$id);
            $this->where('c_ativo',1);
            $this->groupBy('ltr');
            $this->orderBy('ltr');
            $dt = $this->findAll();
            $sx = '';
            //$sx .= '<div aria-label="Page navigation example2">';
            $sx .= '<ul class="pagination">';
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $sx .= '<li class="page-item me-1"><a class="page-link" href="'.(PATH.MODULE.'th/'.$id.'/'.$line['ltr']).'">'.$line['ltr'].'</a></li>'.cr();
                }
            $sx .= '</ul>';
            //$sx .= '</div>';
            $sx = bsc($sx,10);
            $sx .= bsc($this->search($id),2);
            return $sx;
        }
}
