<?php

namespace App\Models\RDF\Ontology;

use CodeIgniter\Model;

class OWL extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = '*';
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

    function xml_prepara($txt)
    {
        $prefix = array('owl:', 'rdf:', 'dct:', 'rdfs:', 'skos:', 'dc:', 'dcterms:', 'foaf:', 'xsd:', 'vann:', 'vcard:', 'void:', 'wdrs:', 'xml:', 'xsd:', 'xhv:', 'xhtml:', 'xlink:', 'xml:', 'xmlns:', 'xsi:', 'xsl:', 'xslt:', 'xv:', 'xsd:', 'xhv:', 'xhtml:', 'xlink:', 'xml:', 'xmlns:', 'xsi:', 'xsl:', 'xslt:', 'xv:');
        foreach ($prefix as $pr) {
            $txt = troca($txt, $pr, '');
        }
        return $txt;
    }

    function ajax_import_triple($id, $txt)
    {
        $Prefix = new \App\Models\RDF\Ontology\Prefix();
        $VocabularyVC = new \App\Models\RDF\Ontology\VocabularyVC();
        $txt = troca($txt, '<', '[');
        $txt = troca($txt, '>', ']');

        $prefix = array();

        $txt = troca($txt, chr(13), chr(10));
        $ln = explode(' .' . chr(10), $txt);
        for ($r = 0; $r < count($ln); $r++) {
            $l = trim($ln[$r]);
            if (substr($l, 0, 7) == '@prefix') {
                $l = trim(troca($l, '@prefix', ''));
                $xml = array();
                $onto = substr($l, 0, strpos($l, ':'));
                $url = substr($l, strpos($l, '[') + 1, strlen($l));
                $url = substr($url, 0, strpos($url, ']'));

                $xml['@attributes'][$onto] = $url;

                $this->identify_prefix($xml);
                $prefix[$onto] = $url;
            } else {
                $part = trim(substr($l, 0, 5));
                if ($part == '[http') {
                    echo "<hr>";
                    pre($l, false);
                    echo "<hr>";
                } else {
                    $name = substr($l,0,strpos($l,' '));
                    $resouce = substr($name,0,strpos($name,':'));
                    $nameSpace = substr($name,strpos($name,':')+1,strlen($name));

                    /************* Instance */
                    $data = array();
                    $data['vc_prefix'] = $Prefix->identify($resouce);
                    $data['vc_label'] = $nameSpace;
                    $data['vc_resource'] = $id;

                    /************* Check */
                    $da = $VocabularyVC
                        ->where('vc_prefix', $data['vc_prefix'])
                        ->where('vc_label', $data['vc_label'])
                        ->where('vc_resource', $data['vc_resource'])
                        ->findAll();

                    if (count($da) == 0) {
                        $VocabularyVC->set($data)->insert();
                    }
                }
            }
        }
    }

    function ajax_import($id)
    {
        $Prefix = new \App\Models\RDF\Ontology\Prefix();
        $OWL = new \App\Models\RDF\Ontology\OWL();
        $Vocabulary = new \App\Models\RDF\Ontology\Vocabulary();

        $dt = $Vocabulary->find($id);
        echo "Importing..[$id].<br>";

        /******************************** Fases da Importação */

        /*********** Open URL ***/
        /* Reading file *********/
        $url = $dt['owl_url'];
        echo h($url, 4) . '<br>';

        $txt = read_link($url);

        /*************************************** XML Prepare */
        if (substr($txt, 0, 1) == '@') {
            $this->ajax_import_triple($id, $txt);
            exit;
        }

        $txt = $this->xml_prepara($txt);
        $xml = (array)simplexml_load_string($txt);

        /******************************* Identifica Prefixos */
        $prefix = $this->identify_prefix($xml);

        /*************************** Identifica dados do OWL */
        $this->identify_ontology($xml, $id);

        /**************************** Classes e Propriedades */
        $prefix = $this->indetify_class_proprities($xml, $id);

        echo "<hr>Fim da importação";
        exit;
    }

    function indetify_class_proprities($xml, $id)
    {

        $VocabularyVC = new \App\Models\RDF\Ontology\VocabularyVC();
        $data = (array)$xml['Description'];
        for ($r = 0; $r < count($data); $r++) {
            $line = (array)$data[$r];
            $VC = $VocabularyVC->import($line, $id);
        }
    }

    function identify_ontology($xml, $id)
    {
        $Vocabulary = new \App\Models\RDF\Ontology\Vocabulary();
        $data = (array)$xml['Ontology'];
        if (isset($data['title'])) {
            $dt['owl_title'] = $data['title'];
            $dt['spaceName'] = '';
            $Vocabulary->set($dt)->where('id_owl', $id)->update();
            echo '<h1>' . $dt['owl_title'] . '</h1>';
        }
    }

    function identify_prefix($xml)

    {
        $Prefix = new \App\Models\RDF\Ontology\Prefix();

        /***************************************** PreFix */
        $prefix = array();
        if (isset($xml['@attributes'])) {
            $prefix = $xml['@attributes'];
            foreach ($prefix as $key => $value) {
                $Prefix->prefix($key, $value);
                $prefix[$value] = $key;
            }
        }

        return $prefix;
    }
}
