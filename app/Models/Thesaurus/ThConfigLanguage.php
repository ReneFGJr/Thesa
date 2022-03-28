<?php

namespace App\Models\Thesaurus;

use CodeIgniter\Model;

class ThConfigLanguage extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'language_th';
    protected $primaryKey       = 'id_lgt';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_lgt','lgt_th','lgt_language',
        'lgt_order'
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

    function edit($id)
        {
            if (get("action")!='')
                {
                    $action = get("action");
                    $lang1 = get("s1");
                    $lang2 = get("s2");

                    if ($action==lang("thesa.add"))
                        {
                            $lang = $lang2;
                            $this->language_add($id,$lang);
                        } else {
                            $lang = $lang1;
                            $this->language_del($id,$lang);
                        }
                }
        /*****************************************************************/
           $sql = "select * from language 
                        LEFT JOIN language_th ON (language.id_lg = lgt_language) AND (lgt_th = $id)
                    ORDER BY lg_order, lg_language"; 
                    
            $rlt = $this->db->query($sql);
            $rlt = $rlt->getResultArray();
            $s1 = '';
            $s2 = '';
            $s3 = '';
            for ($r = 0;$r < count($rlt);$r++)
                {
                    $line = $rlt[$r];
                    if ($line['lgt_th'] == 0)
                        {
                            $s1 .= '<option value="'.$line['id_lg'].'">'.$line['lg_language'].'</option>';                            
                        } else {
                            $s2 .= '<option value="'.$line['id_lg'].'">'.$line['lg_language'].'</option>';
                        }
                    
                }
            $s1t = h(lang('thesa.languages_to_add'),6);
            $s2t = h(lang('thesa.languages_to_del'),6);
            $s1 = $s1t.'<select name="s2" size=10 class="form-control">'.$s1.'</select>';
            $s2 = $s2t.'<select name="s1" size=10 class="form-control">'.$s2.'</select>';
            $s3 = '<input name="action" type="submit" class="btn btn-outline-primary" style="width: 100%;" value="'.lang('thesa.add').'">';
            $s3 .= '<br/>';
            $s3 .= '<br/>';
            $s3 .= '<input name="action" type="submit" class="btn btn-outline-primary" style="width: 100%;" value="'.lang('thesa.remove').'">';

            $sx = h(lang('thesa.languages'),3);
            $sx .= bsc(lang('thesa.languages_info'),12);
            $sx .= bsc('<form method="post" action="'.(PATH.MODULE.'th_config/'.$id.'/language').'">',12);
            $sx .= bsc($s1,5);
            $sx .= bsc($s3,2);
            $sx .= bsc($s2,5);
            $sx .= bsc('</form>',12);
            $sx = bs($sx);
            return $sx;
        }
    function language_add($th,$lang)
        {
            $this->where('lgt_th',$th);
            $this->where('lgt_language',$lang);
            $rlt = $this->findAll();

            if (count($rlt) == 0)
                {
                    $dt['lgt_th'] = $th;
                    $dt['lgt_language'] = $lang;
                    $dt['lgt_order'] = 0;
                    $this->save($dt);
                }
            return "";
        }
    function language_del($th,$lang)
        {
            $this->where('lgt_th',$th);
            $this->where('lgt_language',$lang);
            $rlt = $this->findAll();
            if (count($rlt) > 0)
                {
                    $id = $rlt[0]['id_lgt'];
                    $this->delete($id);
                }
            return "";
        }        
}
