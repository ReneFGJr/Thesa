<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThHeader extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thheaders';
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

    function show($th,$id=0,$ltr='')
        {
            $sx = '';
            $ThUsers = new \App\Models\Thesaurus\ThUsers();
            $ThConcept = new \App\Models\Thesaurus\ThConcept();
            $ThThesaurus = new \App\Models\Thesaurus\ThThesaurus();

            /********************************** Dados do Thesauro */
            $dtt = $ThThesaurus->find($th);
      
            /********************************** Authors *************/
            $authors = $ThUsers->authors($th);
            $resume = $ThThesaurus->show_resume($dtt);
            $description = $this->description($dtt);
            $pagination = $ThConcept->paginations($th,$ltr);
            $search = $ThConcept->search($th);
            /********************************** Titulo do Thesaurus */
            $sx .= $this->title($dtt,
                        bsc($authors,12,'mb-3') . 
                        bsc($description,12,'mb-3') .
                        $resume
                        );

        	$ThFunctions = new \App\Models\Thesaurus\ThFunctions();
    		$sx .= bs(bsc($ThFunctions->menu($th),12));

            $sx .= bsc($pagination,9,'mt-3') .
                   bsc($search,3,'mt-3');

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

    function show_icone($line) {  
        $id = $line['id_pa'];
        $idc = $line['pa_icone'];        
        if ($idc >= 0)
            {
                $idc = str_pad($idc, 3,STR_PAD_LEFT,'0');
                $img = 'img/icone/thema/' .$idc. '.png';
            } else {
                $img = 'img/icone/custon/background_icone_' . $id . '.png';
            }

        $filename = $img;
        if (!file_exists($filename))
            {
                $img = 'img/icone/custon/background_icone_000.png';
            }
        $sa = (URL.$img);
        return $sa;
    }
}
