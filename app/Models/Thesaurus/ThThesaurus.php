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
    protected $allowedFields        = [];

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
                        $sx = $this->welcome_create_th();
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
            return $sx;
        }

    function about()
        {
            $sx = '';
            $sx .= h(lang('thesa.welcome_create_th'),1);
            $sx .= bsc(lang('thesa.welcome_create_th_info'),12);
            $sx .= bsc($this->btn_create_th());

            $sx .= bsc(help('about'),9);

            $sx = bs($sx);
            return $sx;
        }

   function btn_create_th()
        {
            $sx = '<a href="#" class="btn btn-outline-primary mt-5 mb-5">';
            $sx .= lang('thesa.create_th');
            $sx .= '</a>';
            return $sx;
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
                /*************************************** mostra um thesauros ************/
                $dt = $this->Find($id);
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

                $this->Socials = new \App\Models\Socials();
	    	    if ($this->Socials->getAccess('#ADM'))
			    {
    				$ThFunctions = new \App\Models\Thesaurus\ThFunctions();
				    $sb = $ThFunctions->menu($id);
				    $sx .= $sb;
			    }

                
                $sx .=  bsc($sm1,4,'p-3 mb-1').
                        bsc($sm2,8,'shadow p-3 mb-1 bg-white rounded');                
            }            
            $sx = bs($sx);

            return $sx;
        }  

    function card($line,$tp='')
        {
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
                    $card .= '<img src="'.$this->show_icone($line).'" class="img-fluid">';
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

    function rdf($id)
        {
            $ThConcept = new \App\Models\Thesaurus\ThConcept();
            $ThConceptData = new \App\Models\Thesaurus\ThConceptData();
            $dt = $ThConcept->le($id);
            pre($dt);
        }

    function access($th=0,$us='')
        {
            $ThAccess = new \App\Models\Thesaurus\ThAccess();
            return $ThAccess->access($th,$us);
        }

    function show_icone($line) {  
        $id = $line['id_pa'];
        $idc = $line['pa_icone'];
        
        if ($idc > 0)
            {
                $idc = str_pad($idc, 3,STR_PAD_LEFT,'0');
                //echo '<br>===>'.$idc;
                $img = 'img/icone/thema/' .$idc. '.png';
            } else {
                $img = 'img/icone/custon/background_icone_' . $id . '.png';        
            }
        $sa = (URL.$img);
        return $sa;
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

    function th_header($th,$ltr='')
        {
            $sx = '';
            $ThUsers = new \App\Models\Thesaurus\ThUsers();
            $ThConcept = new \App\Models\Thesaurus\ThConcept();
            $dtt = $this->find($th);
      
            /********************************** Authors *************/
            $authors = $ThUsers->authors($th);
            /********************************** Titulo do Thesaurus */
            $sx .= $this->title($dtt,$authors);  

            
            /********************************** Sumários das letras */ 
            if ($ltr != FALSE)
                {
                    /********************************** Description *********/
                    $sx .= $this->description($dtt);
                    $sx .= $ThConcept->paginations($th,$ltr);
                }
            //$sx .= '<style> div { border: 1px solid #ccc; padding: 10px; } </style>';
            /********************************** Lista de Termos *****/

            $sx = bs($sx);
            return $sx;
        }

    function a($id,$act='')
        {
            $sx = '';
            $ThConcept = new \App\Models\Thesaurus\ThConcept();

            /******************************************* Conceito */
            $dt = $ThConcept->find($id);

            $th = $dt['c_th'];

            switch($act)
                {
                    case 'change_preflabel':
                        $sx .= h('change_preflabel');
                        break;
                    default:
                        $sx .= $this->th_header($th,'');    
                        $sx .=   $ThConcept->edit($id);
                    break;
                }
            return $sx;
        }         

    function v($id='',$ltr='A')
        {
            $sx = '';
            
            $ThConcept = new \App\Models\Thesaurus\ThConcept();
            //$ThConceptTerms = new \App\Models\Thesaurus\ThConceptTerms();
            $ThConceptData = new \App\Models\Thesaurus\ThConceptData();
            $VIZ = new \App\Models\Graph\Viz();

            $dt = $ThConcept->le($id);

            $th = $dt['c_th'];
            $sx .= $this->th_header($th,$ltr);

            $sx .=   $ThConcept->show($id);
            $dt = $ThConcept->getConcept($id);
            
            $sa = $VIZ->net($dt);
            //$sx .= '<style> div { border: 1px solid #ccc; padding: 10px; } </style>';

            /****************************************************** DADOS */
            $sb = '';
            $sb .= '<hr>'.$ThConceptData->data_TG($id);
            $sb .= '<hr>'.$ThConceptData->data_TE($id);

            $sx .= bs(bsc($sa,8).bsc($sb,4));

            return $sx;
        }  

    function header($dt)
        {
            $ThUsers = new \App\Models\Thesaurus\ThUsers();
            $sx = '';

            $id = $dt['id_pa'];
            $this->setTh($id);
            $dt = $this->Find($id);

            /********************************** Authors *************/
            $authors = $ThUsers->authors($id);                
            /********************************** Titulo do Thesaurus */
            $sx .= $this->title($dt,$authors);               
            /********************************** Description *********/
            $sx .= $this->description($dt);

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
    function description($dt)
        {
            $sx = '';
            $description = $dt['pa_description'];
            if (strlen($description) > 0)
                {
                    $description = '<b>'.lang('thesa.description').'</b>: '.$description.'';
                } else {
                    $description = '<b>'.lang('thesa.description').'</b>: '.lang('thesa.no_description').'';
                }
            $sx = bsc($description,10);
            return $sx;            
        }
    function title($dt,$authors='')
        {
            $sx = '';
            $sx .= bsc(
                '<h1>'.$dt['pa_name'].'</h1>'    
                . $authors            
                , 10
                );
            $sx .= bsc(
                $img = '<img src="'.$this->show_icone($dt).'" class="img-fluid">', 2
            );
            return $sx;
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
            $id = $this->th($id);
            $dtt = $this->find($id);      
            /********************************** Authors *************/
            $authors = $ThUsers->authors($id);
            /********************************** Titulo do Thesaurus */
            $sx .= bs($this->title($dtt,$authors));  

			$t1 = bsc(bsicone('gear',64).h(lang('thesa.config')),12);

			$sx .= bs($t1);
            //
			$t1 = $ThConfig->config_menu($id,$d1);
			$t2 = $ThConfig->config_itens($id,$d1,$d2,$d3);
            $sx .= bs(bsc($t1,2).bsc($t2,10));
            return $sx;
        }

}
