<?php

namespace App\Models;

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
        $sa = base_url($img);
        return $sa;
    }   

    function terms($id,$lt='A')
        {
            $ThConcept = new \App\Models\ThConcept();
            $sx = $ThConcept->TermLetter($id,$lt);
            return $sx;
        } 

    function v($id='',$ltr='A')
        {
            $sx = '';
            $ThUsers = new \App\Models\ThUsers();
            $ThConcept = new \App\Models\ThConcept();
            $ThConceptTerms = new \App\Models\ThConceptTerms();
            $dt = $ThConcept->le($id);

            $th = $dt['c_th'];
            $dtt = $this->find($th);

            /********************************** Authors *************/
            $authors = $ThUsers->authors($th);
            /********************************** Titulo do Thesaurus */
            $sx .= bs($this->title($dtt,$authors));  

            $sx .= bs($ThConcept->show($id));

            return $sx;          

//            $RDF = new \App\Models\RDF();
//            $dt = $RDF->le($id);
            echo '<pre>';
            print_r($dt);
            exit;

            $idth = 1;

                $dt = $this->Find($idth);

                /********************************** Authors *************/
                $authors = $ThUsers->authors($id);                
                /********************************** Titulo do Thesaurus */
                $sx .= $this->title($dt,$authors);               
                /********************************** Sumários das letras */
                $sx .= $ThConcept->paginations($idth,$ltr);
                /********************************** Lista de Termos *****/
                $sm1 = $this->terms($idth,$ltr);
                $sm2 = $ThConcept->show($id);
                $sm2 .= $ThConceptTerms->data($id);
                $sx .=  bsc($sm1,4,'p-3 mb-1').
                        bsc($sm2,8,'shadow p-3 mb-1 bg-white rounded');                
                $sx = bs($sx);

                return($sx);
        }   

    function index($id='',$ltr='A')
        {
            $ThUsers = new \App\Models\ThUsers();
            $ThConcept = new \App\Models\ThConcept();
            $sx = '';

            /*************************************** mostra todos os thesauros ********/
            if ($id=='')
            {
            $dt = $this->where('pa_status',2)
                ->OrderBy('pa_name')
                ->FindAll();
            for ($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];
                    $img = '<img src="'.$this->show_icone($line).'" class="img-fluid">';
                    $card = $img;
                    $desc = '<a href="'.base_url(PATH.'th/'.$line['id_pa']).'" class="h4 text-bold">'.$line['pa_name'].'</a>';
                    $desc .= '<br>';
                    $ds = bsc($img,4);
                    $ds .= bsc($desc,8);
                    $ds = '<div class="row" style="min-height: 200px;">'.$ds.'</div>';
                    $sx .= bsc($ds,4);
                }
            } else {
                /*************************************** mostra um thesauros ************/
                $dt = $this->Find($id);

                /********************************** Authors *************/
                $authors = $ThUsers->authors($id);                
                /********************************** Titulo do Thesaurus */
                $sx .= $this->title($dt,$authors);               
                /********************************** Description *********/
                $sx .= $this->description($dt);
                $sx .= $this->show_resume($dt);
                /********************************** Sumários das letras */
                $sx .= $ThConcept->paginations($id,$ltr);
                /********************************** Lista de Termos *****/
                $sm1 = $this->terms($id,$ltr);
                $sm2 = '';
                $sx .=  bsc($sm1,4,'p-3 mb-1').
                        bsc($sm2,8,'shadow p-3 mb-1 bg-white rounded');                
            }            
            $sx = bs($sx);
            return $sx;
        }
    function show_resume($dt)
        {
            $ThConcept = new \App\Models\ThConcept();
            $ThLiteral = new \App\Models\ThLiteral();
            $ThConceptTerms = new \App\Models\ThConceptTerms();
            
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
}
