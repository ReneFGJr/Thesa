<?php

namespace App\Models\Skos;

use CodeIgniter\Model;

class ExtractSkos extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_exactmatch';
    protected $primaryKey       = 'id_em';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_em',
        'em_concept',
        'em_link',
        'em_type',
        'em_source',
        'em_visible',
        'em_lastupdate'
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

    function registerConcept($URL, $ThID)
    {
        $Term = new \App\Models\Term\Index();
        $Note = new \App\Models\Property\Notes();
        $TermTh = new \App\Models\Term\TermsTh();
        $Concept = new \App\Models\Thesa\Concepts\Index();
        $TermConcept = new \App\Models\Term\TermConcept();

        if ($ThID == '' || $ThID == 0) {
            return [
                'status' => '500',
                'message' => 'Thesa ID não fornecido.'
            ];
        }
        /************** Biblioteca de Idiomas */
        $Languages = new \App\Models\Thesa\Language();
        $lang = $Languages->getLanguage($ThID);

        $Langs = [];
        foreach ($lang as $item) {
            $Langs[$item['id_lg']] = $item['lg_code'];
        }
        /************ Extração de Dados */
        $Data = $this->extract($URL);

        if ($Data['status'] != '200') {
            return $Data;
        }

        if ($Data['status'] == '200') {
            $Terms = $Data['data']['terms'] ?? [];
            $Notes = $Data['data']['notes'] ?? [];
            /********** Registras Termos */
            $First = true;

            foreach ($Terms['prefLabel'] as $item) {
                //echo $Languages->convert($item);
                $lang = $Languages->convert($item['lang']);
                $term = nbr_title($item['value'] ?? '');

                /********* Verifica se o idioma está no tesauro */
                if (isset($Langs[$lang])) {

                    $idt = $Term->register($term, $lang, $ThID);
                    $TermTh->register($idt, $ThID, 0);

                    if ($First) {
                        $id_term = $idt;
                        $First = false;
                    }
                }
            }
            /************* Não foi identificado termo principal */
            if ($id_term == 0) {
                return [
                    'status' => '500',
                    'message' => 'Não foi identificado termo principal.'
                ];
            }
            /************ Criar Concepts */
            $agency = $Data['data']['ID'] ?? '';

            /* Cria o conceito */
            $IDc = $Concept->register($id_term, $ThID, $agency, 'id');

            /* ExactMatch */
            $Exactmatch = new \App\Models\Skos\Exactmatch();
            $source = $Data['data']['Source'] ?? '0';
            $Exactmatch->register($IDc, $URL, 1, $source, 1);

            /* Inserir Outros PrefLabel */
            $tps = ['prefLabel', 'altLabel', 'hiddenLabel'];
            foreach ($tps as $tp) {
                foreach ($Terms[$tp] as $item) {
                    $lang = $Languages->convert($item['lang']);
                    if (isset($Langs[$lang])) {
                        $term = nbr_title($item['value'] ?? '');
                        $idT = $Term->register($term, $lang, $ThID);
                        $TermConcept->register_term_label($ThID, $IDc, $idT, $tp);

                        $TermsTh = new \App\Models\Term\TermsTh();
                        $TermsTh->register($idT, $ThID, $IDc);
                    }
                }
            }

            /*************************** Notas */
            $tps = ['scopeNote'];
            foreach($tps as $tp) {
                if (!isset($Notes[$tp])) {
                    continue;
                }
                foreach($Notes[$tp] as $item) {
                    $lang = $Languages->convert($item['lang']);
                    if (isset($Langs[$lang])) {
                        $note = nbr_title($item['value'] ?? '');
                        $Note->register($IDc, $tp, $note, $lang, $ThID);
                        // register($IDC,$prop,$note,$lang,$th,$IDN=0)
                    }
                }

            }


            $RSP = [
                'status' => '200',
                'message' => 'Dados extraídos e registrados com sucesso.',
                'URL' => $URL,
                'data' => $Data['data']
            ];
        } else {
            $RSP = [
                'status' => '500',
                'message' => 'Erro ao extrair dados: ' . $Data['message']
            ];
        }
        return $RSP;
    }

    function extract($url)
    {
        /********** Identificar Tipo de Dado */
        $SourceLinkedData = new \App\Models\Linkeddata\Source_rdf();
        $type = $SourceLinkedData->getType($url);
        if ($type['status'] != '200') {
            return $type;
        }

        /************************************ Read Json */
        try {
            $Json = $this->readJson($type['url']);
            switch ($type['type']) {
                case 'loterre':
                    $Loterre = new \App\Models\Skos\Schemes\Loterre();
                    $Data = $Loterre->extract($Json);
                    break;
                case 'unesco':
                    $Unesco = new \App\Models\Skos\Schemes\Unesco();
                    $Data = $Unesco->extract($Json);
                    break;
                case 'usda.gov':
                    $Usda = new \App\Models\Skos\Schemes\Usda();
                    $Data = $Usda->extract($Json);
                    break;
                default:
                    return [
                        'status' => '500',
                        'message' => 'Tipo de dado não suportado: ' . $type['type']
                    ];
            }
        } catch (\Exception $e) {
            return [
                'status' => '500',
                'message' => 'XXX - Error fetching linked data source: ' . $e->getMessage()
            ];
        }


        // Logic to extract SKOS data based on the provided link and thesaID
        // This is a placeholder for the actual extraction logic
        $RSP['status'] = '200';
        $RSP['message'] = 'SKOS data extracted successfully.';
        $RSP['data'] = $Data;
        return $RSP;
    }

    function readJson(string $url, int $timeoutSeconds = 30): array
    {
        $NURL = trim($url);
        // Preferencial: cURL
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            if ($ch === false) {
                return ['status' => '500', 'message' => 'Falha ao inicializar cURL.'];
            }

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_TIMEOUT => $timeoutSeconds,                 // tempo total
                CURLOPT_CONNECTTIMEOUT => min(10, $timeoutSeconds), // tempo só para conectar
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'User-Agent: fetchJson/1.0 (+php)'
                ],
                //CURLOPT_SSL_VERIFYPEER => true,
                //CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0
            ]);


            $body = curl_exec($ch);
            if ($body === false) {
                $err = curl_error($ch);
                curl_close($ch);
                return ['status' => '500', 'message' => "Erro cURL: $err"];
            }

            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status < 200 || $status >= 300) {
                return ['status' => '500', 'message' => "HTTP $status ao acessar $url"];
            }

            if ($body === '' || $body === null) {
                return ['status' => '500', 'message' => 'Resposta vazia do servidor.'];
            }

            $data = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['status' => '500', 'message' => 'JSON inválido: ' . json_last_error_msg(), 'raw' => $body];
            }

            return [
                'status' => '200',
                'message' => 'Dados do JSON extraídos com sucesso.',
                'url' => $NURL,
                'data' => $data
            ];
        }

        // Fallback: file_get_contents com contexto e timeout
        $context = stream_context_create([
            'http' => [
                'method'  => 'GET',
                'timeout' => $timeoutSeconds,
                'header'  => "Accept: application/json\r\nUser-Agent: fetchJson/1.0 (+php)\r\n",
                'ignore_errors' => true, // permite ler corpo mesmo em 4xx/5xx
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ]
        ]);

        $body = @file_get_contents($url, false, $context);
        // Captura status HTTP (se disponível)
        $status = 0;
        if (isset($http_response_header[0]) && preg_match('#HTTP/\S+\s+(\d{3})#', $http_response_header[0], $m)) {
            $status = (int)$m[1];
        }

        if ($body === false) {
            return ['status' => '500', 'message' => 'Erro ao ler URL (timeout ou conexão falhou).'];
        }
        if ($status && ($status < 200 || $status >= 300)) {
            return ['status' => '500', 'message' => "HTTP $status ao acessar $url"];
        }

        $data = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['status' => '500', 'message' => 'JSON inválido: ' . json_last_error_msg(), 'raw' => $body];
        }
        return ['status' => '200', 'message' => 'Dados extraídos com sucesso.', 'url' => $NURL, 'data' => $data];
    }
}
