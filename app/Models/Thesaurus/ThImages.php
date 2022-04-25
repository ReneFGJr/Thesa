<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThImages extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'th_concept_image';
    protected $primaryKey       = 'id_tci';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_tci','tci_concept','tci_file','tci_type','tci_th','tci_order','tci_checksum'
    ];

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

    var $dir = '.uploads/images';

    function index($d1,$d2,$d3,$d4,$d5)
        {
            $sx = '';
            echo '<br>d1==>'.$d1;
            echo '<br>d2==>'.$d2;
            echo '<br>d3==>'.$d3;
            echo '<br>d4==>'.$d4;
            switch($d1)
                {
                    case 'upload':
                        $sx .= $this->upload($d2);
                        break;
                }
            return $sx;
        }

    function upload($id)
        {   
            $sx = '';         
            if (isset($_FILES['images']))
                {
                    $sx .= $this->save_image($id,$_FILES['images']['tmp_name']);
                }
            
            $sx .= form_open_multipart();
            $sx .= form_upload('images');
            $sx .= form_submit(array('name'=>'submit','value'=>'Upload'));
            $sx .= form_close();
            $sx = bs(bsc($sx,12));
            return $sx;
        }

        function save_image($id,$file)
        {
            $sx = '';
            $dir = '' ;
            $dire = explode('/',$this->dir);
            for ($r=0;$r < count($dire);$r++)
                {
                    $dir .= $dire[$r].'/';
                    dircheck($dir);
                }
            
            $ThImagesType = new \App\Models\Thesaurus\ThImagesType();
            $type = mime_content_type($file);
            $chk = md5_file($file);

            $dt = $this->where('tci_checksum',$chk)->findAll();
            if (count($dt) == 0)
                {
                    $dt = $this->where('tci_concept')->findAll();
                    $order = count($dt);

                    $fileDest = $dir.$chk;
                    move_uploaded_file($file,$fileDest);
                    $dt['tci_concept'] = $id;
                    $dt['tci_file'] = $fileDest;
                    $dt['tci_type'] = $ThImagesType->contentType($type);
                    $dt['tci_checksum'] = $chk;
                    $dt['tci_th'] = 0;
                    $dt['tci_order'] = $order;
                    $IDF = $this->save($dt);
                } else {
                    $line = $dt[0];
                    if ($line['tci_concept'] != $id)
                        {

                        } else {
                            $sx .= bsmessage(lang('thesa.image_already_exists'),3);
                        }
                }
                return $sx;
        }        

    function show($id)
        {
            $sx = '';
            $dt = $this   
                    ->join('th_concept_image_type','tci_type = id_tcit','left')
                    ->where('tci_concept',$id)
                    ->orderBy('tci_order')
                    ->FindAll();
            $sx .= h('thesa.images',5);

            if (count($dt) > 0)
                {
                    if (count($dt) == 1)
                        {
                            $line = $dt[0];
                            $sx = $line['tcit_player'];
                            $sx = troca($sx,'#url',URL.$line['tci_file']);
                        } else {
                            $sx .= '
                            <div id="imagesThesa" class="carousel slide mb-4" data-bs-ride="carousel">
                            <div class="carousel-inner">';

                            $act = 'active';                            
                            for ($z=0;$z < count($dt);$z++)
                            {
                                $line = $dt[$z];
                                $sx .= '<div class="carousel-item '.$act.'">';
                                $act = '';
                                //<img class="d-block w-100" src="..." alt="First slide">
                                $sxp = $line['tcit_player'];
                                $sx .= troca($sxp,'#url"',URL.$line['tci_file'].'" style="height: 250px;"');
    
                                $sx .= '</div>';
                            }
                            $sx .= '</div>';
                            
                            $sx .= '</div>';
                            
                            $sx .= '
                            <script>
                            var myCarousel = document.querySelector(\'#imagesThesa\')
                            var carousel = new bootstrap.Carousel(myCarousel)
                            </script>
                            ';
                        }
                }
            return $sx;
        }
    function btn_upload($id)
        {
            $sx = '';
            $sx .= onclick(PATH.MODULE.'popup/image/upload/'.$id,'Upload');
            $sx .= bsicone('upload');
            $sx .= '</span>';
            return $sx;
        }
}
