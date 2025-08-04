<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

helper(['boostrap', 'url', 'sisdoc_forms', 'form', 'nbr', 'sessions', 'cookie']);
$session = \Config\Services::session();
$language = \Config\Services::language();

define("URL", getenv("app.baseURL"));
define("PATH", getenv("app.baseURL"));
define("MODULE", '');
define("PREFIX", '');
define("COLLECTION", 'thesa');

define('BG_COLOR', 'bg-primary');

class Api extends BaseController
{
    public function apiError()
    {
        $RSP['status'] = '500';
        $RSP['message'] = 'APIKEY Error';
        $RSP['situation'] = 'PRE';
        $RSP['apikey'] = get("APIKEY") . get("apikey");
        return $RSP;
    }

    public function user()
    {
        $user = 0;
        $apikey = get("APIKEY") . get("apikey");
        $apikey = troca($apikey, '"', '');
        if ($apikey != '') {
            $Socials = new \App\Models\Socials();
            $user = $Socials->validaAPIKEY($apikey);
        }
        return $user;
    }
    public function index($arg1 = '', $arg2 = '', $arg3 = '')
    {
        /* NAO USADO PARA AS APIS */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Origin', '*');
        header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

        if ((get("test") == '') and (get("code") == '')) {
            header("Content-Type: application/json");
        }

        $RSP = [];

        $user = $this->user();
        /*
        $RSP['user'] = $user;
        $RSP['data'] = $_POST;
        $RSP['arg1'] = $arg1;
        $RSP['arg2'] = $arg2;
        */

        if ($arg1 == 'conecpt') {
            $arg1 = 'c';
        }
        if ($arg1 == 'term') {
            $arg1 = 't';
        }

        switch ($arg1) {
            case 'deleteExactMatch':
                $Exactmatch = new \App\Models\Skos\Exactmatch();
                $RSP = $Exactmatch->deleteExactMatch($arg2);
                break;
            case 'exactmatch':
                $Exactmatch = new \App\Models\Skos\Exactmatch();
                $RSP = $Exactmatch->index($arg2, $arg3);
                break;
            case 'deleteLinkedData':
                $Linkeddata = new \App\Models\Linkeddata\Index();
                $RSP = $Linkeddata->deleteLinkedData($arg2);
                break;
            case 'linkedata':
                $Linkeddata = new \App\Models\Linkeddata\Index();
                $RSP = $Linkeddata->index($arg2, $arg3);
                break;
            case 'uploadSchema':
                $Icone = new \App\Models\Thesa\Icone();
                $RSP = $Icone->uploadSchema();
                break;

            case 'updateTerm':
                $Term = new \App\Models\Term\Index();
                $RSP = $Term->updateTerm();
                break;
            case 'setTopConcept':
                $TopConcept = new \App\Models\Thesa\Schema\TopConcept();
                $RSP = $TopConcept->setTopConcept();
                break;
            case 'concept_delete':
                $Concept = new \App\Models\Thesa\Concepts\Index();
                $RSP = $Concept->deleteConcept($arg2);
                break;
            case 'changeStatus':
                $Thesa = new \App\Models\Thesa\Index();
                $RSP['thesaStatus'] = $Thesa->chageStatus($arg2, get("type"));
                break;
            case 'typeLicence':
                $Thesa = new \App\Models\Thesa\Index();
                $RSP['thesaLicence'] = $Thesa->chageLicence($arg2, get("type"));
                break;
            case 'thesaLicences':
                $Thesa = new \App\Models\Thesa\Index();
                $RSP['Licences'] = $Thesa->thesaLicences();
                break;
            case 'typeChange':
                $Thesa = new \App\Models\Thesa\Index();
                $RSP['thesaTypes'] = $Thesa->chageTypes($arg2, get("type"));
                break;
            case 'thesaTypes':
                $Thesa = new \App\Models\Thesa\Index();
                $RSP['thesaTypes'] = $Thesa->thesaTypes();
                break;
            case 'canCreateNewThesa':
                $Thesa = new \App\Models\Thesa\Index();
                $RSP = $Thesa->canCreateNewThesa($user);
                break;
            case 'email_test':
                $Email = new \App\Models\Email\Index();
                $RSP['response'] = $Email->email_test(get('email'));
                $RSP['status'] = '200';
                break;
            case 'index_alphabetic':
                $Concepts = new \App\Models\Thesa\Concepts\Index();
                $RSP = $Concepts->index_alphabetic($arg2);
                $RSP['status'] = '200';
                $RSP['message'] = 'Index';
                break;
            case 'getNote':
                $Notes = new \App\Models\Property\Notes();
                $noteID = get('noteID');
                $RSP = $Notes->getNote($noteID);
                break;
            case 'deleteNote':
                $Notes = new \App\Models\Property\Notes();
                $RSP = $Notes->deleteNote();
                break;
            case 'saveNote':
                $Notes = new \App\Models\Property\Notes();
                $RSP = $Notes->saveNote();
                break;
            case 'getNotesType':
                $Prop = new \App\Models\RDF\ThProprity();
                $RSP = $Prop->getNotesType();
                break;
            case 'members_remove':
                $Collaborators = new \App\Models\Thesa\Collaborators();
                $RSP = $Collaborators->members_remove();
                break;

            case 'members_register':
                $Collaborators = new \App\Models\Thesa\Collaborators();
                $RSP['names'] = $Collaborators->members_register();
                break;
            case 'members':
                $Collaborators = new \App\Models\Thesa\Collaborators();
                $RSP = $Collaborators->authors($arg2, $arg3);
                break;
            case 'languages_set':
                $Language = new \App\Models\Language\Index();
                $RSP = $Language->languages_set($arg2);
                break;
            case 'getLanguages':
                $Language = new \App\Models\Language\Index();
                $RSP = [];
                $RSP['languages'] = $Language->languages($arg2, '');
                break;
            case 'languages':
                $Language = new \App\Models\Language\Index();
                $RSP = [];
                $RSP['languages'] = $Language->languages($arg2);
                break;
            case 'relationsCustom':
                $ThProprityCustom = new \App\Models\RDF\ThProprityCustom();
                $RSP = $ThProprityCustom->le($arg2, $arg3);
                break;
            case 'removeRelation':
                $Relations = new \App\Models\Thesa\Relations\Relations();
                $RSP = $this->removeRelation($arg2, $arg3);
                break;
            case 'relateConcept':
                $Broader = new \App\Models\Thesa\Relations\Broader();
                $RSP = $Broader->relateConcept();
                break;
            case 'related_candidate':
                $Related = new \App\Models\Thesa\Relations\Related();
                $RSP = $Related->related_candidate($arg2, $arg3);
                break;
            case 'broader_candidate':
                $Broader = new \App\Models\Thesa\Relations\Broader();
                $RSP = $Broader->broader_candidate($arg2, $arg3);
                break;
            case 'relateTerms':
                $Term = new \App\Models\Term\Index();
                $RSP = $Term->registerTerm($user);
                break;
            case 'createThesa':
                $userID = $user;
                $Thesa = new \App\Models\Thesa\Index();
                $Thesa->where('th_achronic', get('acronic'))->first();
                if ($Thesa->th_achronic != '') {
                    $RSP['status'] = '400';
                    $RSP['message'] = 'Thesaurus already exists';
                    echo json_encode($RSP);
                    exit;
                }
                /*
                    if (!isset($_POST['acronic'])) {
                        $RSP['status'] = '400';
                        $RSP['message'] = 'Thesaurus title not informed';
                        echo json_encode($RSP);
                        exit;
                    }
                    */

                $dt = [];
                $dt['th_name'] = get('title');
                $dt['th_achronic'] = get('acronic');
                $dt['th_description'] = '';
                $dt['th_status '] = get('visibility');
                $dt['th_terms'] = 0;
                $dt['th_version'] = '0';
                $dt['th_icone'] = 1;
                $dt['th_icone_custom'] = '';
                $dt['th_type'] = get('type');
                $dt['th_finality'] = get('finality');
                $dt['th_own '] = $userID;

                $dt = $Thesa->set($dt)->insert();
                $RSP['status'] = '200';
                $RSP['message'] = 'Thesaurus created';
                break;
            case 'saveDescription':
                $Thesa = new \App\Models\Thesa\Index();
                $dt = $_POST;
                $RSP = $Thesa->saveDescription($dt);
                break;
            case 'getDescription':
                $Thesa = new \App\Models\Thesa\Index();
                $dt = $_POST;
                $RSP = $Thesa->getDescription($arg2, $arg3);
                break;
            case 'getRelations':
                $Thesa = new \App\Models\Thesa\Relations\Related();
                $dt = $_POST;
                $RSP = $Thesa->getRelations($arg2, $arg3);
                break;
            case 'getRelationsTh':
                $Thesa = new \App\Models\Thesa\Relations\RelationsGroupTh();
                $dt = $_POST;
                $RSP = $Thesa->getRelationsTh($arg2, $arg3);
                $RSP['post'] = $_POST;
                break;
            case 'setRelationsType':
                $Thesa = new \App\Models\Thesa\Relations\RelationsGroupTh();
                $RSP = $Thesa->setRelationsType();
                break;
            case 'tools':
                $Tools = new \App\Models\Tools\Index();
                $RSP = $Tools->index($arg2, $arg3);
                break;
            case 'concept_create_term':
                $Term = new \App\Models\Concept\Index();
                $DT = $_GET;
                $DT = array_merge($DT, $_POST);
                $RSP = $Term->createConceptAPI($DT);
                break;
            case 'term_list':
                $Term = new \App\Models\Term\Index();
                $RSP = $Term->listTerm($arg2);
                break;
            case 'term_pref_list':
                $Term = new \App\Models\Term\Index();
                $RSP = $Term->listTermPref($arg2, $arg3);
                break;

            case 'listPrefTerm':
                $Term = new \App\Models\Term\Index();
                $RSP = $Term->listPrefTerm($arg2);
                break;
            case 'term_add':
                if ($user == 0) {
                    $RSP = $this->apiError();
                } else {
                    $Term = new \App\Models\Term\Index();
                    $RSP = $Term->appendTerm($user);
                }
                break;
            case 'import':
                $tools = new \App\Models\Tools\Import();
                $RSP = $tools->import();
                break;
            case 'status':
                $RSP['status'] = '200';
                $RSP['messagem'] = 'The system is healthy';
                $RSP['situation'] = 'GREEN';
                break;
            case 'terms':
                $Thesa = new \App\Models\Thesa\Index();
                $RSP['terms'] = $Thesa->terms($arg2, $arg3);
                break;
            case 'thopen': /* Thesaurus aberto */
                $Thesa = new \App\Models\Thesa\Index();
                $RSP['th'] = $Thesa->thopen();
                break;
            case 'thmy': /* Thesaurus aberto */
                $Thesa = new \App\Models\Thesa\Index();
                $userID = $this->user();
                $userID = 2;

                if ($userID == 0) {
                    $RSP['status'] = '400';
                    $RSP['message'] = 'User not informed';
                    echo json_encode($RSP);
                    exit;
                }
                $RSP['th'] = $Thesa->thopen($userID);
                break;
            case 'resume':
                $Thesa = new \App\Models\Thesa\Index();
                $RSP = $Thesa->summary();
                break;
            case 't':
                $RSP = $this->t($arg2, $RSP);
                break;
            case 'c':
                $RSP = $this->c($arg2, $RSP);
                break;
            case 'th': /* Dados do Thesaurus */
                $RSP = $this->th($arg2, $RSP);
                break;
            case 'social':
                $Socials = new \App\Models\Socials();
                $RSP = $Socials->index($arg2, $arg3);
                break;
            default:
                $RSP['status'] = '400';
                $RSP['message'] = 'Verb not informed';
                $RSP['args'] = [$arg1, $arg2, $arg3];
        }
        $RSP['time'] = date("Y-m-dTH:i:s");

        echo json_encode($RSP);
        exit;
    }

    function th($id, $RSP)
    {
        $Thesa = new \App\Models\Thesa\Thesa();
        $RSP = $Thesa->le($id);

        /* Privilegios */
        $apikey = get('apikey');
        $apikey = troca($apikey, '"', '');
        $RSP['editMode'] = false;
        if ($apikey != '') {
            $Collaborators = new \App\Models\Thesa\Collaborators();
            $editMode = $Collaborators->isMember($apikey, $id);
            if ($editMode > 0) {
                $RSP['editMode'] = 'allow';
            } else {
                $RSP['editMode'] = 'deny';
            }
        }
        return $RSP;
    }

    function removeRelation()
    {
        $verb = get("type");
        if ($verb == 'narrow') {
            $verb = 'broader';
        }
        if ($verb == 'hiddenLabel') {
            $verb = 'altLabel';
        }
        if ($verb == 'prefLabel') {
            $verb = 'altLabel';
        }
        switch ($verb) {
            case 'altLabel':
                $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
                $dr = $ThConceptPropriety->where('id_ct', get('idr'))->first();

                $TermsTh = new \App\Models\Term\TermsTh();
                $dx = [];
                $dx['term_th_concept'] = 0;
                /* Libera termo */
                $TermsTh->set($dx)
                    ->where('term_th_term', $dr['ct_literal'])
                    ->where('term_th_thesa', $dr['ct_th'])
                    ->update();

                /* Excluir conceito e relação */
                $ThConceptPropriety->where('id_ct', get('idr'))->delete();

                $RSP['status'] = '200';
                $RSP['message'] = 'Relation removed';
                break;
            case 'broader':
                $Broader = new \App\Models\Thesa\Relations\Broader();
                $dt = $Broader->where('id_b', get('idr'))->delete();
                $RSP['status'] = '200';
                $RSP['message'] = 'Relation removed';
                $RSP['situation'] = 'GREEN';
                break;
            default:
                $RSP['status'] = '400';
                $RSP['message'] = 'Verb not informed';
                $RSP['verb'] = $verb;
                $RSP['post'] = $_POST;
                break;
        }
        return $RSP;
    }

    function t($id, $RSP)
    {
        $Terms = new \App\Models\Thesa\Terms\Index();
        $dt = $Terms->le($id);
        $RSP = array_merge($RSP, $dt);
        return $RSP;
    }

    function c($id, $RSP)
    {
        $Term = new \App\Models\Term\Index();
        $Concept = new \App\Models\Thesa\Concepts\Index();
        $Broader = new \App\Models\Thesa\Relations\Broader();
        $Relations = new \App\Models\Thesa\Relations\Relations();
        $Linkeddata = new \App\Models\Linkeddata\Index();
        $Exactmatch = new \App\Models\Skos\Exactmatch();
        $ConceptTopSchema = new \App\Models\Thesa\Schema\TopConcept();

        $Notes = new \App\Models\Property\Notes();
        $RSP = $Concept->le($id);

        $RSP['uri'] = URL . '/c/' . $id;

        $RSP['prefLabel'] = $Term->le($id, 'prefLabel');
        $RSP['altLabel'] = $Term->le($id, 'altLabel');
        $RSP['hiddenLabel'] = $Term->le($id, 'hiddenLabel');

        $RSP['broader'] = $Broader->le_broader($id);
        $RSP['narrow'] = $Broader->le_narrow($id);

        $RSP['relations'] = $Relations->le_relations($id);

        $RSP['notes'] = $Notes->le($id);

        $RSP['linkeddata'] = $Linkeddata->le($id);
        $RSP['exactmatch'] = $Exactmatch->le($id);

        //$RSP['Collections'] = $ConceptTopSchema->getTopConceptsByThesa($id);
        $RSP['topConcept'] = $ConceptTopSchema->getTopConcept($id);
        return $RSP;
    }

    function image($type = '', $id = '')
    {
        switch ($type) {
            case 'icone':
                $Icone = new \App\Models\Thesa\Icone();
                $RSP = $Icone->ShowImage(['id_th' => $id]);
                break;
            default:
                $RSP['status'] = '400';
                $RSP['message'] = 'Type not informed';
        }
        return $RSP;
    }
}
