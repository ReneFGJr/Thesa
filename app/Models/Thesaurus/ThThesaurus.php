<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThThesaurus extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'th_thesaurus';
    protected $primaryKey           = 'id_pa';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        'id_pa','pa_name','pa_achronic',
        'pa_status','pa_creator','pa_icone',
        'pa_class','pa_type','pa_multi_language'
    ];
    protected $typeFields        = [
        'hidden','string:100*','string:100*',
        'status:thesa*','hidden','set:0',
        'hidden','sql:id_pac:pac_name:th_thesaurus_class*','sn*'
    ];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'pa_created';
    protected $updatedField         = 'pa_update';
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

    function myth()
        {
            $sx = '';
            $vtp = get("type");
            $Socials = new \App\Models\Socials();
            $ID = $Socials->getID();
            if ($ID > 0)
            {
                $dt = $this
                    ->select('id_pa, pa_name, pa_icone')
                    ->join('th_users','ust_th = id_pa')
                    ->where('ust_user_id',$ID)
                    ->groupBy('id_pa, pa_name, pa_icone')
                    ->OrderBy('pa_name')
                    ->FindAll();
                
                $sx .= bsc('Total '.count($dt).' registros');
                if (count($dt) == 0)
                    {
                        $sx = bs(bsc($sx,12));
                        return $sx;
                    }
                for ($r=0;$r < count($dt);$r++)
                    {
                        $line = $dt[$r];
                        $sx .= $this->card($line,$vtp);
                    }
            } else {
               $sx .= metarefresh(PATH.MODULE);
            }            
            $sx = bs($sx);
            $sx .= bs(bsc($this->btn_create_th(),3));
            return $sx;
        }

    function about()
        {
            $sx = bsc('<div class="mt-5"></div>',12);

            $txt = help('apresentation');
            $txt .= '<hr>';
            $txt .= help('about');
            $sx .= bsc($txt,9,'mb-5');

            /* Right Screen */
            $txr = lang(h(lang('thesa.th_my'),2));
            $txr.= $this->btn_open_th();            
            $txr.= $this->btn_my_th();
            $txr .= $this->btn_create_th();
            
            $sx .= bsc($txr,3);
            $sx = bs($sx);
            return $sx;
        }

    function th_limit($id)
        {
            $Socials = new \App\Models\Socials();
            $a1 = $Socials->getAccess('#ADM#TRU');
            if ($a1 == true)
                {
                    return false;
                }

            /************************************** TOTAL */
            return true;
        }

   function btn_create_th()
        {
            $sx = '';
            $Socials = new \App\Models\Socials();
            $ID = $Socials->getID();            

            if ($ID > 0)
            {            
                /**************************************** CHECHA Limite */
                if ($this->th_limit($ID) == false)
                {
                    $sx = '<a href="'.PATH.MODULE.'edit_th/0'.'" class="btn btn-outline-danger mt-2 mb-2" style="width: 100%;">';
                    $sx .= lang('thesa.create_th');
                    $sx .= '</a>';
                } else {
                    $sx = '<span class="btn btn-danger mt-2 mb-2 disable" style="width: 100%;">';
                    $sx .= lang('thesa.create_th_limite');
                    $sx .= '</span>';
                }
            }
            return $sx;
        }

   function btn_open_th()
        {
            $sx = '<a href="'.PATH.MODULE.'thopen'.'" class="btn btn-outline-success mt-2 mb-2" style="width: 100%;">';
            $sx .= lang('thesa.thopen');
            $sx .= '</a>';
            return $sx;
        }

    function btn_my_th()
        {
            $sx = '';
            $Socials = new \App\Models\Socials();
            $ID = $Socials->getID();
            if ($ID > 0)
            {
                $sx .= '<a href="'.PATH.MODULE.'th_my'.'" class="btn btn-outline-primary mt-2 mb-2" style="width: 100%;">';
                $sx .= lang('thesa.th_my');
                $sx .= '</a>';
            }            
            return $sx;
        }        

    function show($th,$lt='')
        {
                $ThHeader = new \App\Models\Thesaurus\ThHeader();
                $sx = $ThHeader->show($th,0,$lt);
                return $sx;
/*************************************** mostra um thesauros ************/
                $dt = $this->Find($th);
                if ($dt == '')
                    {
                        $sx = metarefresh(PATH.MODULE);
                        return $sx;
                    }
                /*************************************************************************** */
                $sx = $this->header($dt);              	              
                $sx .= $this->show_resume($dt);
                /********************************** Sumários das letras */
                $sx .= $ThConcept->paginations($id,$ltr);
                /********************************** Lista de Termos *****/
                $q = get("q");
                if (strlen($q) > 0)
                    {
                        $sm1 = $this->terms_query($id,$q);
                        $sm2 = '';
                    } else {
                        $sm1 = $this->terms($id,$ltr);
                        $sm2 = '';
                    }   

                $Socials = new \App\Models\Socials();
                $ThUsers = new \App\Models\Thesaurus\ThUsers();
	    	    if (($Socials->getAccess('#ADM')) or ($ThUsers->autorized($Socials->getID())))
			    {
    				$ThFunctions = new \App\Models\Thesaurus\ThFunctions();
				    $sb = $ThFunctions->menu($id);
				    $sx .= $sb;
			    }

                
                $sx .=  bsc($sm1,4,'p-3 mb-1').
                        bsc($sm2,8,'shadow p-3 mb-1 bg-white rounded');
        }

   function index($id='',$ltr='A')
        {            
            $ThConcept = new \App\Models\Thesaurus\ThConcept();
            $sx = '';

            /*************************************** mostra todos os thesauros ********/
            if ($id=='')
            {
                $vtp = 'icone';

                $dt = $this->where('pa_status',2)
                    ->OrderBy('pa_name')
                    ->FindAll();
                $sx .= bsc('Total '.count($dt).' registros',12);
                for ($r=0;$r < count($dt);$r++)
                    {
                        $line = $dt[$r];                        
                        $sx .= $this->card($line,$vtp);
                    }
            } else {
                                
            }            
            $sx = bs($sx);

            return $sx;
        }  

    function card($line,$tp='')
        {
            $ThHeader = new \App\Models\Thesaurus\ThHeader();
            $sx = '';
            $link = '<a href="'.(PATH.MODULE.'th/'.$line['id_pa']).'">';
            $linka = '</a>';

            $card = '';
            switch ($tp)
            {
                case 'line':
                    $card = $line['pa_name'];
                    $sx .= bsc($card,12,'p-3 mb-3');  
                    break;

                default:            
                    $card .= $link;
                    $card .= '<div class="card">';
                    $card .= '<div class="card_image">';
                    $card .= '<img src="'.$ThHeader->show_icone($line).'" class="img-fluid">';
                    $card .= '</div>';
                    $card .= '<div class="card_title text-center p-1">';
                    $card .= $line['pa_name'];
                    $card .= '</div>';
                    $card .= '</div>';
                    $card .= $linka;                                    
                    $sx .= bsc($card,2,'text-center p-3 mb-3');  
                break;
            }
            return $sx;          
        }  

    function access($th=0,$us='')
        {
            $ThAccess = new \App\Models\Thesaurus\ThAccess();
            return $ThAccess->access($th,$us);
        }

    function terms($id,$lt='A')
        {
            $ThConcept = new \App\Models\Thesaurus\ThConcept();
            $sx = $ThConcept->TermLetter($id,$lt);
            return $sx;
        } 

    function terms_query($id,$lt='A')
        {
            $ThConcept = new \App\Models\Thesaurus\ThConcept();
            $sx = $ThConcept->TermQuery($id,$lt);
            return $sx;
        }

    function a($id,$act='')
        {
            $sx = '';
            $ThConcept = new \App\Models\Thesaurus\ThConcept();

            /******************************************* Conceito */
            $dt = $ThConcept->find($id);
            $sx .=   $ThConcept->edit($id);

            return $sx;
        }  

    function edit($id='')
        {
            $submenu = new \App\Models\Thesaurus\ThFunctions();
            $sx = '';
            $this->id = $id;
            $this->path = PATH.MODULE.'edit_th/'.$id;
            $this->path_back = 'none';
            $this->pre = 'thesa.';

            /***************************** SubMenu */
            if ($id > 0)
                {
                    $sx .= $submenu->menu($id);
                }
            
            /***************************************************************** margens */
            $sx .= bsc('<div class="mt-5"></div>',12);

            /***************************************************************** Header */
            if ($id == 0)
                {
                    $pa_achronic = get("pa_achronic");
                    if ($pa_achronic != '')
                    {

                        $dt = $this->where('pa_achronic',$pa_achronic)->findAll();
                        if (count($dt) > 0)
                        {
                            $sx .= bsmessage(lang('thesa.error.achronic').' <b>'.$pa_achronic.'</b>'.lang('thesa.error.achronic2'),3);
                            $_POST['pa_achronic'] = '';
                        }
                    }                    
                }
            

            if ($id == 0)
                {
                    $Socials = new \App\Models\Socials();
                    $_POST['pa_created'] = $Socials->getID();
                }
            
            if ($id == 0)
                {
                    $sx .= h('thesa.create_thesaurus',2);
                }
            
            $sx .= form($this);

            if (($this->saved > 0) and ($id == 0))
                {                    
                    $Socials = new \App\Models\Socials();
                    $idth = $this->id;
                    $ThUsers = new \App\Models\Thesaurus\ThUsers();
                    $idu = $Socials->getID();
                    $ThUsers->add_user($idth,$idu,1);

                    /************************** Define um idioma */
                    $ThConfigLanguage = new \App\Models\Thesaurus\ThConfigLanguage();
                    $ThConfigLanguage->language_add($idth,364);

                    /************************** Redireciona */
                    $url = PATH.MODULE.'th_config/'.$idth;
                    $sx = metarefresh($url);

                    return $sx;
                }           
            return $sx;
        }       

    function v($id='',$ltr='A')
        {
            $sx = '';
            
            $ThConcept = new \App\Models\Thesaurus\ThConcept();            
            $ThLiteral = new \App\Models\Thesaurus\ThLiteral();
            $ThAssociate = new \App\Models\Thesaurus\ThAssociate();
            $Proprities = new \App\Models\RDF\Proprieties();
            $VIZ = new \App\Models\Graph\Viz();

            $dt['literal'] = $ThLiteral->le($id);
            $dt['concept'] = $ThConcept->le($id);
            $dt['data'] = $ThAssociate->le($id);
            

            $Class = $Proprities->getPropriety('Concept');

            $nodes = array();
            $edges = array();

            $sx .= $ThConcept->header($dt);

            $c = $dt['concept'];
            $nodes[0] = array('n_name'=>'Thesa:c'.$c['id_c'],'id_ct'=>$c['id_c']);
            $da = array();
            $da['prefLabel'] = array();
            $da['altLabel'] = array();
            $da['hiddenLabel'] = array();

           /***************************************************** Literal */
           $lit = $dt['literal'];
           for ($r=0;$r < count($lit);$r++)
               {
                   $line = $lit[$r];
                   $prop = $line['p_propriey'];
                   switch($prop)
                       {
                           case 'Concept':
                               // none;
                           break;

                           case 'prefLabel':
                               array_push($nodes,array(
                                   'n_name'=>$line['n_name'].'@'.$line['n_lang'],
                                   'id_ct'=>$line['id_ct']));
                               array_push($edges,array(
                                   'source'=>$id,
                                   'target'=>$line['id_ct'],
                                   'propriety'=>$line['prefix_name'].':'.$line['p_propriey']));
                               $da['prefLabel'][] = $line['n_name'].' <sup>'.$line['n_lang'].'</sup>';
                               break;
                           case 'hiddenLabel':
                               $da['hiddenLabel'][] = $line['n_name'].' <sup>'.$line['n_lang'].'</sup>';
                               break;
                               
                           case 'altLabel':
                               $da['altLabel'][] = $line['n_name'].' <sup>'.$line['n_lang'].'</sup>';
                               break;
           
                           case 'notation':
                               if (!isset($da[$line['p_propriey']]))
                                   {
                                       $da[$line['p_propriey']] = array();
                                   }                            
                               array_push($da[$line['p_propriey']],$line['n_name']);
                               array_push($nodes,array(
                                               'n_name'=>$line['n_name'],
                                               'id_ct'=>$line['id_ct']));
                               array_push($edges,array(
                                               'source'=>$id,
                                               'target'=>$line['id_ct'],
                                               'propriety'=>$line['prefix_name'].':'.$line['p_propriey']));                                 
                           break;

                           default:
                           echo h($prop.' not found');
                               //pre($line);
                       }
               }            

            /***************************************************** Associates */
            for ($r=0;$r < count($dt['data']);$r++)
                {
                    $c = $dt['data'][$r];
                    if (($c['id_ct'] != $id) and ($c['tg_active'] == 1) and ($c['ct_propriety'] == $Class))
                        {
                            //array_push($nodes,array('n_name'=>$c['n_name'],'id_ct'=>$c['id_ct']));
                            array_push($nodes,array('n_name'=>$c['n_name'],'id_ct'=>$c['id_ct']));
                            array_push($edges,array(
                                        'source'=>$id,
                                        'target'=>$c['id_ct'],
                                        'propriety'=>$c['p_propriey']
                                        ));  
                            if (!isset($da[$c['p_propriey']]))
                                {
                                    $da[$c['p_propriey']] = array();
                                }
                            $name = $c['n_name'];
                            $iddc = $c['tg_concept_2'];
                            if ($iddc == $id)
                                {
                                    $iddc = $c['tg_concept_1'];
                                }
                            $link = '<a href="'.PATH.MODULE.'v/'.$iddc.'">';
                            $link_a = '</a>';
                            $name = $link.$name.$link_a;
                            array_push($da[$c['p_propriey']],$name);
                        }                    
                }

 

            $graph = array();
            $graph['nodes'] = $nodes;
            $graph['edges'] = $edges;
            $sa = $VIZ->net($graph);

            /****************************************************** DADOS */
            $sb = '<ul>';
            foreach ($da as $key => $value)
                {
                    if (count($value) > 0)
                    {
                        $sb .= h(lang('thesa.'.$key),5);                    
                        foreach ($value as $k => $v)
                            {
                                $sb .= '<li class="ms-5">'.$v.'</li>';
                            }
                    }
                }
            $sb .= '</ul>';
//            $sb .= '<hr>'.$ThConceptData->data_TG($id);
//            $sb .= '<hr>'.$ThConceptData->data_TE($id);
            $sx = bs($sx);
            $sx .= bs(bsc($sa,8).bsc($sb,4));
            return $sx;
        }  

    function setTh($id)
        {
            $_SESSION['th'] = $id;
            return True;
        }
        
    function show_resume($dt)
        {
            $ThConcept = new \App\Models\Thesaurus\ThConcept();
            $ThLiteral = new \App\Models\Thesaurus\ThLiteral();
            $ThConceptTerms = new \App\Models\Thesaurus\ThConceptTerms();
            
            $concpt = $ThConcept->resume($dt['id_pa']);
            $terms = $ThConceptTerms->resume($dt['id_pa']);
            $lang = $ThLiteral->resume($dt['id_pa']);
            $sx = '';
            $CssClass = 'border-start border-secondary border-3';
            $sx .= bsc(
                lang('thesa.Concept').
                '<h1>'.$concpt.'</h1>'
                , 3, $CssClass
            );
            $sx .= bsc(
                lang('thesa.Term').
                '<h1>'.$terms.'</h1>'
                , 3, $CssClass
            );
            $sx .= bsc(
                lang('thesa.Language').
                '<h1>'.$lang.'</h1>'
                , 3, $CssClass
            );
            $sx .= bsc(
                lang('thesa.Update').
                '<h3>'.date("d/m/Y").'</h3>'
                , 3, $CssClass
            );
            $sx = bs($sx);
            return $sx;
        }

    function getTh()
        {
            return $this->th(0);
        }

    function getAchronic($n='')
        {
            $dt = $this->where('pa_achronic',$n)->findAll();
            if (count($dt) > 0)
                {
                    return $dt[0]['id_pa'];
                } else {
                    return 0;
                }
        }

    function th($id)
        {
            if ($id==0)
                {
                    if (isset($_SESSION['th']))
                        {
                            $id = $_SESSION['th'];
                            $_SESSION['th'] = $id;
                        } else {
                            $id = 0;
                            echo metarefresh(PATH.MODULE);
                        }
                }
            return $id;
        }

    function config($id,$d1,$d2,$d3)
        {   
            $sx = '';    
            $ThConfig = new \App\Models\Thesaurus\ThConfig();
            $ThUsers = new \App\Models\Thesaurus\ThUsers();
            $ThHeader = new \App\Models\Thesaurus\ThHeader();
            $id = $this->th($id);
            $dtt = $this->find($id);    
            if ($dtt=='')
                {
                    $sx = metarefresh(PATH.MODULE);
                    return $sx;
                }              
            /********************************** Authors *************/
            $authors = $ThUsers->authors($id);
            /********************************** Titulo do Thesaurus */
            $sx .= bs($ThHeader->title($dtt,$authors));  

			$t1 = bsc(bsicone('gear',64).h(lang('thesa.config')),12);

			$sx .= bs($t1);
            //
			$t1 = $ThConfig->config_menu($id,$d1);
			$t2 = $ThConfig->config_itens($id,$d1,$d2,$d3);
            $sx .= bs(bsc($t1,2).bsc($t2,10));
            return $sx;
        }

}
