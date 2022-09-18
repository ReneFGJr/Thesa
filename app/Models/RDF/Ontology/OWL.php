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

    function ajax_import($id)
    {
        echo "OK $id";
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

    function indetify_class_proprities($xml,$id)
    {

        $VocabularyVC = new \App\Models\RDF\Ontology\VocabularyVC();
        $data = (array)$xml['Description'];
        for ($r = 0; $r < count($data); $r++) {
            $line = (array)$data[$r];
            $VC = $VocabularyVC->import($line,$id);
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
