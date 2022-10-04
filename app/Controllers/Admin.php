<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap', 'url', 'sisdoc_forms', 'form', 'nbr', 'sessions', 'cookie']);
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
        return view('Theme/Standard/footer', $data);
    }

    public function index($d1 = '',$d2 = '',$d3 = '',$d4 = '',$d5='')
    {
        $Thesa = new \App\Models\Thesa\Thesa();

        $sx = $this->cab();
        $sx .= view('header/navbar_admin');

        switch ($d1) {
            case 'ajax_form_field_save':
                pre($_GET);
                exit;
            case 'ajax_term_delete':
                $ThConceptPropriety = new \App\Models\RDF\ThConceptPropriety();
                $sx = $ThConceptPropriety->ajax_term_delete();
                break;
            case 'ajax_form_save':
                $id = get("id");
                $prop = get("prop");
                $vlr = get("vlr");

                $ThConcept = new \App\Models\RDF\ThConcept();
                if ($vlr != '')
                {
                    echo $ThConcept->ajax_save($id, $prop, $vlr);
                }
                echo $ThConcept->list_concepts_terms($id, $prop);
                exit;
                break;

            case 'ajax_lang':
                $Config = new \App\Models\Thesa\Config();
                $th = get("th");
                $idioma = get("lang");
                $act = get("act");
                if ($idioma != '')
                    {
                        $lang = new \App\Models\Language\Index();
                        $language = new \App\Models\Thesa\Language();

                        $dl = $lang->where('lg_code',$idioma)->first();
                        if ($dl != '')
                        {
                        $id_lg = $dl['id_lg'];

                        if ($act == '')
                            {
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
                echo $ThConcept->ajax_edit($id,$prop);
                exit;
            case 'ajax_docs':
                $txt = get("txt");
                $th = get("th");
                $class = get("class");

                $Config = new \App\Models\Thesa\Config();
                $sx .= $Config->ajax_save($th,$class,$txt);
                echo $sx;
                exit;
                break;
            case 'config':
                $sx .= $this->config($d2, $d3, $d4, $d5);
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
                $sx .= $ThTerm->index($d2,$d3,$d4,$d5);
                return $sx;
            case 'proprity':
                $ThProprity = new \App\Models\RDF\ThProprity();
                $sx .= $ThProprity->index($d2, $d3, $d4, $d5);
                return $sx;
            default:
                $sx .= bs(bsc("$d1 / $d2 / $d3 / $d4 / $d5",12));
                $sx .= bs(bsc('Admin'),12);
            break;
        }
        return $sx;
    }

    function config($d1,$d2,$d3)
        {
            $Thesa = new \App\Models\Thesa\Thesa();
            $th = $Thesa->setThesa();

            if (($th == '') or ($th == 0))
                {
                    echo "OPS";
                    exit;
                }

            $Config = new \App\Models\Thesa\Config();
            $Description = new \App\Models\Thesa\Descriptions();
            $Collaborators = new \App\Models\Thesa\Collaborators();

            $sx = '';
            $sx .= bs(bsc('Configurações'),12);

            $data = array();
            $data['link'] = array();

            $sa = '';

            /******************************************* Collarations */
            array_push($data['link'], '<a href="#_" class="nav-link">Colaboradores</a>');
            $sa .= '<h1>'.lang("thesa.Collaborators").'</h1>';
            $sa .= $Collaborators->list($th);

            $class = $Description->classes();
            foreach($class as $id=>$name)
                {
                    array_push($data['link'], '<a href="#'.$name.'" class="nav-link">'.lang("thesa.$name").'</a>');
                    $sa .= $Config->control($name);
                }


            $data['body'] = $sa;

            $sx .= view('Admin/body',$data);

            $sx .= $this->foot();
            return $sx;
        }
}
