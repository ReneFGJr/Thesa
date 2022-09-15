<?php

namespace App\Models\Schema;

use CodeIgniter\Model;

class SchemaExternal extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'schema_external';
    protected $primaryKey       = 'id_se';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_se','se_th','se_name','se_url_xml',
        'se_update','se_format','se_active','se_terms'
    ];

    var $typeFields    = [
        'hidden','hidden','string:100*','string:100*',
        'update','sql:id_sef:sef_name: schema_external_format*','sn','hidden'
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

    function showID($id,$act)
        {
            $Socials = new \App\Models\Socials();
            $dt = $this->find($id);

            $sx = '';
            $sx .= bsc(h($dt['se_name'],3),12);
            $sx .= bsc(h(anchor($dt['se_url']),7),12);
            $sx .= bsc(h(anchor($dt['se_url_xml']),7),12);
            $sx .= bsc(lang('thesa.se_format') . ': ' . '<b>'.($dt['se_format']).'</b>',12);
            $sx .= bsc(lang('thesa.se_update') . ': ' . '<b>'.(stodbr(sonumero($dt['se_update']))).'</b>',10);

            $btn = '';
            if ($Socials->getAccess('#ADM#GER'))
            {
                $btn = '<a href="'.PATH.MODULE.'schema/skos/'.$id.'/update" class="btn btn-outline-primary btn-sm">'.lang('thesa.update').'</a>';
            }

            $sx .= bsc($btn,2);

            $sx = $this->resume($id);

            /************************************************* UPDATE */
            if ($act == 'update')
            {
                $sx .= bsc('UPDATE',12);
                $sx .= $this->updateURL($dt);
            }

            $sx = bs($sx);
            return $sx;
        }
    function resume($id)
        {
            $SchemaExternalTerms = new \App\Models\Schema\SchemaExternalTerms();
            $dt = $SchemaExternalTerms->resume($id);
            $sx = bsc('thesa.total:'.$dt[0]['total'],12);
            return $sx;
        }

    /****************************************************************** UPDATE */
    function updateURL($dt)
        {
            $url = $dt['se_url_xml'];
            $id = $dt['id_se'];

            $dir = '../.tmp/';
            dircheck($dir);
            $dir = '../.tmp/skos/';
            dircheck($dir);

            $file = 'skos_'.strzero($dt['id_se'],6).'.xml';

            if (!file_exists($dir.$file))
                {
                    $txt = read_link($url,'file');
                    file_put_contents($dir.$file, $txt);
                    $sx = bsmessage('thesa.create_file');
                } else {
                    $txt = file_get_contents($dir.$file);
                    $sx = bsmessage('thesa.already_file');
                }
            $sx = $this->import_content($txt,$id);
            return $sx;
        }

    function show($th)
        {
            $dt = $this
                    ->join('schema_external_format','se_format = id_sef','left')
                    ->where('se_th',$th)
                    ->findAll();

            $sx = '<table class="table table-sm table-striped">';
            $sx .= '<tr><th width="5%" class="text-center">'.msg('thesa.set_th').'</th>
                        <th width="50%">'.msg('thesa.set_se').'</th>
                        <th width="10%">'.msg('thesa.se_format').'</th>
                        <th width="10%">'.msg('thesa.se_terms').'</th>
                        <th width="10%">'.msg('thesa.se_update').'</th>
                        <th width="5%" colspan=3 class="text-center">'.msg('thesa.set_tr').'</th>
                        </tr>';

            for($r=0;$r < count($dt);$r++)
                {
                    $line = $dt[$r];

                    $class = '';

                    if ($line['se_active'] == 0)
                        {
                            $class = 'text-decoration-line-through';
                        }

                    $sx .= '<tr>';
                    $sx .= '<td class="text-center">'.$line['id_se'].'</td>';
                    $sx .= '<td class="'.$class.'">';
                    $sx .= '<a href="'.PATH.MODULE.'schema/skos/'.$line['id_se'].'">';
                    $sx .= $line['se_name'];
                    $sx .= '</a>';
                    $sx .= '</td>';

                    /********************************* TERMS */
                    $sx .= '<td class="text-center '.$class.'">'.$line['sef_name'].'</td>';

                    /********************************* TERMS */
                    $sx .= '<td class="text-center '.$class.'">'.number_format($line['se_terms'],0,',','.').'</td>';

                    /********************************* UPDATE LAST */
                    $sx .= '<td class="text-center">';
                    $sx .= stodbr(sonumero($line['se_update']));
                    $sx .= '</td>';

                    /*********************** POPUP EXCLUDE */
                    $url_del = PATH.MODULE.'popup/relation_skos/'.$line['se_th'].'/'.$line['id_se'].'/del/yes';
                    $link = '<a href="#" onclick="if (confirm(\''.lang('thesa.confirm_exclusion?').'\')) { '.newwin($url_del,800,400).' }">';
                    $link .= bsicone('trash');
                    $link .= ' </a>';
                    if ($class != '') { $link = ''; }

                    /*********************** POPUP UPDATE */
                    $url_harvesting = PATH.MODULE.'popup/harvesting/'.$line['id_se'];
                    $link2 = '<a href="#" onclick="if (confirm(\''.lang('thesa.confirm_harvesting?').'\')) { '.newwin($url_harvesting,800,400).' }">';
                    $link2 .= bsicone('reload');
                    $link2 .= ' </a>';

                    $url_edit = PATH.MODULE.'popup/relation_skos/'.$line['se_th'].'/'.$line['id_se'];
                    $link3 = onclick($url_edit,'800','600');
                    $link3 .= bsicone('edit');
                    $link3 .= ' </span>';

                    if ($class != '') { $link2 = ''; }

                    $sx .= '<td class="text-center"><nobr>';
                    $sx .= $link;
                    $sx .= $link2;
                    $sx .= $link3;
                    $sx .= '</nobr></td>';


                    $sx .= '</tr>';
                }
            if (count($dt)==0)
                {
                    $sx .= '<tr><td colspan="3" class="text-center">'.msg('thesa.no_relations').'</td></tr>';
                }
            $sx .= '</table>';
            return $sx;
        }

    function form_edit_skos($th,$id=0,$act='',$conf='')
        {
            if ($act == 'del')
                {
                    $dd['se_active'] = 0;
                    $this   ->set($dd)
                            ->where('id_se',$id)
                            ->update($id);
                    $sx = wclose();
                    return $sx;
                    exit;
                }
            $sx = h(lang('thesa.relations_skos'),4);
            $this->id = $id;
            if ($id == 0)
                {
                   $this->typeFields[1] = 'set:'.$th;
                }

            $this->path = PATH.MODULE.'popup/relation_skos';
            $this->path_back = 'wclose';
            $sx .= form($this);
            return($sx);
        }

    function import_content($txt,$id)
        {
        $txt = troca($txt,'rdf:','');
        $txt = troca($txt,'dc:','dc_');
        $txt = troca($txt,'dct:','dct_');
        $txt = troca($txt,'skos:','skos_');
        $txt = troca($txt,'xml:','');
        $txt = troca($txt,'skosxl:','skosxl_');

         $xml = simplexml_load_string($txt);
         $xml = (array)$xml;
         $this->xml_skosmos($xml,$id);
         return wclose();

        }

    function xml_skosmos($xml,$id)
        {
            $idd = $id;
            $SchemaExternalTerms = new \App\Models\Schema\SchemaExternalTerms();
            $data = (array)$xml['skos_ConceptScheme'];
            $att = (array)$data['@attributes'];
            $tit = (array)$data['dct_title'][0];
            echo h($tit[0],1);
            $url = $att['about'];
            echo h($url,4);


            $dd['se_name'] = $tit[0];
            $dd['se_url'] = $url;
            $dd['se_update'] = date("Y-m-d");

            $this->set($dd)->where('id_se',$id)->update();

            $dr = $this->where('se_name',$tit[0])->orderBy('id_se')->findAll();
            $idr = $dr[0]['id_se'];

            if ($idr != $id)
                {
                    $dd['se_use'] = $idr;
                    $this->set($dd)->where('id_se',$id)->update();
                    $id = $idr;
                }

            $id_skos = $idr;
            $cpt = $xml['skos_Concept'];
            $tot = 0;

            foreach($cpt as $id=>$cpt)
                {
                    $line = (array)$cpt;

                    $att = (array)$line['@attributes'];
                    $urli = $att['about'];
                    $urli = troca($urli,$url,':');
                    $urli = troca($urli,'/','');

                    $term = $cpt->skos_prefLabel;
                    $terms = array();

                    foreach($term as $id=>$pref)
                        {
                            $te = (array)$pref;
                            $lang = $te['@attributes']['lang'];
                            $ter = $te[0];
                            $terms[$lang] = $ter;

                            $da['see_term'] = $ter;
                            $da['see_lang'] = $lang;
                            $da['see_resource'] = $urli;
                            $da['see_se'] = $id_skos;
                            $tot++;

                            if (($lang == 'pt') or ($lang == 'en') or ($lang == 'fr') or ($lang == 'es'))
                            {
                                $dx = $SchemaExternalTerms
                                    ->where('see_resource',$urli)
                                    ->where('see_term',$ter)
                                    ->findAll();
                                if (count($dx) == 0)
                                    {
                                        $SchemaExternalTerms->insert($da);
                                    }
                            } else {
                                //echo '+==>'.$lang.'<br>';
                            }
                        }

                        $term = $cpt->skos_altLabel;
                        $terms_alt = array();
                        foreach($term as $id=>$pref)
                            {
                                $te = (array)$pref;
                                $lang = $te['@attributes']['lang'];
                                $ter = $te[0];
                                $terms_alt[$lang] = $ter;
                            }
                }

                /* Update */
                $dd['se_terms'] = $tot;
                $dd['se_update'] = date("Y-m-d");
                $this->set($dd)->where('id_se',$idd)->update();
                $this->set($dd)->where('id_se',$idr)->update();

        }


    function import_external_schema($dt,$id)
        {
            pre($dt);
        }
}
