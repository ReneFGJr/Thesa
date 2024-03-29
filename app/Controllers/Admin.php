<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap', 'url', 'sisdoc_forms', 'form', 'nbr', 'sessions', 'cookie', 'UploadedFile']);
$session = \Config\Services::session();
$language = \Config\Services::language();

define("URL", getenv("app.baseURL"));
define("PATH", getenv("app.baseURL") . '/');
define("MODULE", '');
define("PREFIX", '');
define("COLLECTION", 'admin');

class Admin extends BaseController
{
    public function cab($data = array())
    {
        $data['title'] = 'Thesa - #ADMIN';
        $data['bg_color'] = '#4B0082;';
        $data['css'] = '';
        return view('header/header', $data);
    }

    public function foot($data = array())
    {
        $data['title'] = 'Thesa - #ADMIN';
        $data['bg_color'] = '#4B0082;';
        $data['css'] = '';
        return view('Theme/Standard/Foot', $data);
    }

    public function index($d1 = '', $d2 = '', $d3 = '', $d4 = '', $d5 = '')
    {
        $Thesa = new \App\Models\Thesa\Thesa();

        $sx = $this->cab();
        $sx .= view('header/navbar');
        $footer = view('header/foot');

        switch ($d1) {
            case 'collaborators':
                $Collaborators = new \App\Models\Thesa\Collaborators();
                $sx = $this->cab();
                $sx .= $Collaborators->index($d2, $d3, $d4, $d5);
                return $sx;
                break;
            case 'notes':
                $ThNotes = new \App\Models\RDF\ThNotes();
                $sx = $this->cab();
                $sx .= $ThNotes->form($d2, $d3, $d4, $d5);
                $footer = '';
                break;
            case 'icone':
                $Icone = new \App\Models\Thesa\Icone();
                $sx = $this->cab();
                $sx .= $Icone->change($d2, $d3, $d4, $d5);
                return $sx;
                break;
            case 'term_add':
                $Terms = new \App\Models\RDF\ThTerm();
                $sx = $this->cab();
                $sx .= $Terms->form($d2);
                return $sx;
                break;
            case 'ajax_exclude':
                $sx = $this->cab();
                $Terms = new \App\Models\Thesa\Terms\Index();
                $sx .= $Terms->exclude($d2, $d3, $d4, $d5);
                break;
            case 'popup_prefLabel':
                $sx = $this->cab();
                $PrefLabel = new \App\Models\Thesa\Terms\PrefLabel();
                $sx .= $PrefLabel->form($d2);
                break;
            case 'popup_altLabel':
                $sx = $this->cab();
                $AltLabel = new \App\Models\Thesa\Terms\AltLabel();
                $sx .= $AltLabel->form($d2);
                break;
            case 'popup_hiddenLabel':
                $sx = $this->cab();
                $HiddenLabel = new \App\Models\Thesa\Terms\HiddenLabel();
                $sx .= $HiddenLabel->form($d2);
                $footer = '';
                break;
            case 'popup_midia_exclude':
                $Midia = new \App\Models\Thesa\Midias();
                $Socials = new \App\Models\Socials();
                $user = $Socials->getUser();
                if ($user > 0) {
                    $sx = $this->cab();
                    $sx .= $Midia->item_delete($d1, $d2);
                } else {
                    echo "Acesso negado";
                    exit;
                }
                echo $sx;
                exit;
                break;
            case 'popup_midia':
                $Midia = new \App\Models\Thesa\Midias();
                $Socials = new \App\Models\Socials();
                $user = $Socials->getUser();
                if ($user > 0) {
                    $sx = $this->cab();
                    $sx .= $Midia->upload($d1, $d2);
                } else {
                    echo "Acesso negado";
                    exit;
                }
                echo $sx;
                exit;
                break;
            case 'ajax_concept_reference':
                $Reference = new \App\Models\Thesa\Reference();
                $ReferenceConcept = new \App\Models\Thesa\ReferenceConcept();
                $concept = get("id");
                $ref = get("ref");
                $set = get("set");
                $ReferenceConcept->register($concept, $ref, $set);
                echo $Reference->list_reference($concept);
                exit;
            case 'ajax_reference_list':
                $Reference = new \App\Models\Thesa\Reference();
                $id = get("term");
                echo $Reference->list_reference($id);
                exit;
                break;
            case 'ajax_form_reference_save':
                $Reference = new \App\Models\Thesa\Reference();
                $Reference->register($d2, $d3, $d4);
                $id = get("term");
                echo $Reference->list_reference($id);
                exit;
                break;
            case 'ajax_text_list':
                $ThNotes = new \App\Models\RDF\ThNotes();
                $id = get("id");
                $prop = get("prop");
                $sx = $ThNotes->list($id, $prop);
                echo $sx;
                exit;
                break;
            case 'ajax_text_edit':
                $ThNotes = new \App\Models\RDF\ThNotes();
                echo $ThNotes->ajax_text_edit();
                exit;
                break;
            case 'ajax_text_delete':
                $ThNotes = new \App\Models\RDF\ThNotes();
                $sx = $this->cab();
                $sx .= $ThNotes->delete_note($d2, $d3, $d4, $d5);
                return bs(bsc($sx, 12, 'p-4'));
                exit;
            case 'ajax_form_text_save':
                $ThNotes = new \App\Models\RDF\ThNotes();
                echo $ThNotes->text_save();
                exit;
            case 'popup_broader':
                $Broader = new \App\Models\Thesa\Relations\Broader();
                $sx = $this->cab();
                $sx .= $Broader->form($d2, $d3, $d4, $d5);
                echo $sx;
                exit;
            case 'ajax_broader_save':
                $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
                $ThConceptPropriety->broader_save();
                pre($_GET);
                exit;
            case 'ajax_form_field_save':
                $Description = new \App\Models\Thesa\Descriptions();
                $Description->ajax_field_save($d2, $d3, $d4);
                exit;
            case 'ajax_term_delete':
                $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
                $sx = $ThConceptPropriety->ajax_term_delete();
                break;
            case 'ajax_form_save':
                $id = get("id");
                $prop = get("prop");
                $vlr = get("vlr");
                $qualy = round('0' . get("qualy"));

                $ThConcept = new \App\Models\RDF\ThConcept();
                if ($vlr != '') {
                    echo $ThConcept->ajax_save($id, $prop, $vlr, $qualy);
                }
                echo $ThConcept->list_concepts_terms($id, $prop);
                exit;
                break;

            case 'ajax_lang':
                $Config = new \App\Models\Thesa\Config();
                $th = get("th");
                $idioma = get("lang");
                $act = get("act");
                if ($idioma != '') {
                    $lang = new \App\Models\Language\Index();
                    $language = new \App\Models\Thesa\Language();

                    $dl = $lang->where('lg_code', $idioma)->first();
                    if ($dl != '') {
                        $id_lg = $dl['id_lg'];

                        if ($act == '') {
                            $language->register($th, $id_lg);
                        } else {
                            $language->remove($th, $id_lg);
                        }
                    }
                }

                echo $Config->form_language($th);
                exit;
                break;

            case 'ajax_form':
                $id = get("id");
                $prop = get("prop");

                $ThConcept = new \App\Models\RDF\ThConcept();
                echo $ThConcept->ajax_edit($id, $prop);
                exit;
            case 'ajax_docs':
                $txt = get("txt");
                $th = get("th");
                $class = get("class");

                $Config = new \App\Models\Thesa\Config();
                $sx = $Config->ajax_save($th, $class, $txt);
                echo "SAVED AT " . date("H:i:s");
                echo $sx;
                exit;
                break;
            case 'config':
                $sx .= $this->config($d2, $d3, $d4, $d5);
                break;
            case 'tools':
                $sx .= $this->tools($d2, $d3, $d4, $d5);
                break;
            case 'ontology':
                $Ontology = new \App\Models\RDF\Ontology\Index();
                $sx .= $Ontology->index($d2, $d3, $d4, $d5);
                return $sx;
            case 'thesaurus':
                $Thesa = new \App\Models\Thesa\Thesa();
                $sx .= $Thesa->index($d2, $d3, $d4, $d5);
                return $sx;
            case 'terms':
                $ThTerm = new \App\Models\RDF\ThTerm();
                $sx .= $ThTerm->index($d2, $d3, $d4, $d5);
                return $sx;
            case 'proprity':
                $ThProprity = new \App\Models\RDF\ThProprity();
                $sx .= $ThProprity->index($d2, $d3, $d4, $d5);
                return $sx;
                break;
            case 'import':
                $sx .= $this->import($d2, $d3, $d4, $d5);
                break;
            default:
                $Thesa = new \App\Models\Thesa\Index();
                $th = round('0' . $Thesa->getThesa());
                if ($th == 0)
                    {
                        $sx = redirect('Thesa::index');
                        return $sx;
                    }

                $sx .= bs(bsc("$d1 / $d2 / $d3 / $d4 / $d5", 12));
                $sx .= bs(bsc('Admin'), 12);
                $sx .= bs($this->painel($th));
                break;
        }
        $sx .= $footer;
        return $sx;
    }

    function painel($th)
        {
            $sx = '';
            $sx .= bsc(h('Painel Admin', 2));



            pre($_SESSION);

            if ($th > 0)
                {
                    $sx .= 'OL';
                }

            $menu = [];
            $menu[PATH.'/admin/import/'] = lang('thesa.import');
            $sx .= bsc(menu($menu));


            return $sx;
        }

    function import($d2, $d3, $d4, $d5)
    {
        $sx = '';

        $tp = ['thesa', 'thematres', 'skosmos'];
        $sa = '';
        foreach ($tp as $tps) {
            $link = anchor(PATH. '/admin/import/'.$tps, lang('thesa.'.$tps),['class'=>'btn btn-outline-primary full']);
            $sa .= bsc($link,2);
        }

        $form = false;

        switch ($d2) {
            case 'thematres':
                $value = get("url");

                if (($value != '') and (substr($value, 0, 4) == 'http')) {
                    $ThemaTres = new \App\Models\Thesa\Tools\ImportThemaTres();
                    $sx .= $ThemaTres->import($value);
                }
                $form = true;

                $sx .= $ThemaTres->reload();
                break;

            case 'thesa':
                $value = get("url");

                if (($value != '') and (substr($value, 0, 4) == 'http')) {
                    $ThesaImport = new \App\Models\Thesa\Tools\ImportThesa();
                    $sx .= $ThesaImport->import($value);
                }
                $form = true;

                break;
        }

        if ($form == true)
            {
                $sx .= form_open();
                $sx .= form_label(lang('thesa.url'));
                $sx .= form_input(array('name' => 'url', 'value' => $value, 'class' => 'form-control full'));
                $sx .= form_submit('action', lang('thesa.save'));
                $sx .= form_close();
            }

        $sx = bs($sa.bsc($sx));
        return $sx;
    }

    function tools($d1, $d2, $d3)
    {
        $sx = '';
        $sa = anchor(PATH . '/admin/import/', bsicone('import', 64));
        $sa .= anchor(PATH . '/admin/ontology/', bsicone('import', 64)) . 'ontology';
        $sx .= bs(bsc($sa, 12));
        return $sx;
    }

    function config($d1, $d2, $d3)
    {
        $Thesa = new \App\Models\Thesa\Index();
        $th = $Thesa->setThesa();

        if (($th == '') or ($th == 0)) {
            echo "OPS - ADMIN";
            exit;
        }

        $Config = new \App\Models\Thesa\Config();
        $Description = new \App\Models\Thesa\Descriptions();
        $Collaborators = new \App\Models\Thesa\Collaborators();

        $sx = '';
        $sx .= bs(bsc('Configurações'), 12);

        $data = array();
        $data['link'] = array();

        $sa = '';

        /******************************************* Collarations */
        array_push($data['link'], '<a href="#_" class="nav-link">Colaboradores</a>');
        $sa .= '<h1>' . lang("thesa.Collaborators") . '</h1>';
        $sa .= $Collaborators->list($th);
        $sa .= $Collaborators->management($th);

        $class = $Description->classes();
        foreach ($class as $id => $name) {
            array_push($data['link'], '<a href="#' . $name . '" class="nav-link">' . lang("thesa.$name") . '</a>');
            $sa .= $Config->control($name);
        }


        $data['body'] = $sa;
        $sx .= view('Admin/body', $data);
        $sx .= view('Admin/environment', $data);
        $sx .= $this->foot();

        return $sx;
    }
}
