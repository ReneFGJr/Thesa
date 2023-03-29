<?php

namespace App\Controllers;

use CodeIgniter\Controller;

helper(['boostrap', 'url', 'sisdoc_forms', 'form', 'nbr', 'sessions', 'cookie']);
$session = \Config\Services::session();
$language = \Config\Services::language();

define("URL", getenv("app.baseURL"));
define("PATH", getenv("app.baseURL") . '/');
define("MODULE", '');
define("PREFIX", '');
define("COLLECTION", 'pdf');

class Export extends Controller
{

    public function index($id = 0, $type = '')
    {
        $sx = '';
        switch ($type) {
            case 'html':
                $sx = $this->exporHTML($id);
                break;
            case 'pdf':
                $sx = $this->exportPDF($id);
                break;
            default:
                $sx .= anchor(PATH . '/export/' . $id . '/pdf', 'PDF');
                $sx .= anchor(PATH . '/export/' . $id . '/html', 'Html');
        }
        return $sx;
    }

    function metadata($th)
    {
        $Thesa = new \App\Models\Thesa\Index();
        $Description = new \App\Models\Thesa\Descriptions();
        $dt = $Thesa->le($th);

        $file = trim($dt['th_cover']);

        if ((trim($dt['th_cover']) == '') or (!file_exists($file)))
            {
                $dt['th_cover'] = '/_covers/cover_thesa.jpg';
            }

        $dt['Description'] = $Description->show($th);

        $ConceptList = new \App\Models\Thesa\Concepts\Lists();
        $dt['alphabetic'] = $ConceptList->terms_alphabetic_array($th);
        $dt['systematic'] = $ConceptList->terms_systematic_index($th);
        return $dt;
    }

    function exporHTML($th)
    {
        $data = $this->metadata($th);
        $pg = '';
        $pg .= view('PDF/_00_Style', $data);
        $pg .= bs(bsc(view('PDF/_01_Cover', $data),12));
        $pg .= bs(bsc(view('PDF/_02_FirstPage', $data), 12));
        $pg .= bs(bsc(view('PDF/_03_FaceSheet', $data), 12));
        $pg .= bs(bsc(view('PDF/_04_Presentation', $data), 12));
        $pg .= bs(bsc(view('PDF/_10_Alfabetico', $data), 12));
        $pg .= bs(bsc(view('PDF/_11_Sistematico', $data),12));
        $pg .= view('PDF/_99_end');
        return $pg;
    }

    function exportPDF($th)
    {
        $opt = array('enable_remote' => true);
        $dompdf = new \Dompdf\Dompdf($opt);

        $dompdf->set_option("isPhpEnabled", true);

        //$dompdf->loadHtml(view('PDF/pdf-view'));
        $data = $this->metadata($th);


        $pg = view('PDF/_00_Style', $data);
        $pg .= view('PDF/_01_Cover', $data);
        $pg .= view('PDF/_02_FirstPage', $data);
        $pg .= view('PDF/_03_FaceSheet', $data);
        $pg .= view('PDF/_04_Presentation', $data);
        $pg .= view('PDF/_10_Alfabetico', $data);
        $pg .= view('PDF/_11_Sistematico', $data);
        $pg .= view('PDF/_99_end');
        $dompdf->loadHtml($pg, 'UTF-8');
        //$dompdf->setPaper('A4', 'landscape');

        header('Content-type: application/pdf');
        //
        $dompdf->render();
        $dompdf->add_info('Subject', 'Thesauros');
        $dompdf->add_info('Subject', 'Thesa');
        $dompdf->stream('thesa.pdf', ['Attachment' => false]);

        exit;
    }
}
