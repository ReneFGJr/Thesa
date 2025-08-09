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

    function extract($url)
    {
        $Term = new \App\Models\Term\Index();
        $TermTh = new \App\Models\Term\TermsTh();
        $Concept = new \App\Models\Thesa\Concepts\Index();

        $th = get('thesaID');
        if (!$th) {
            return [
                'status' => '500',
                'message' => 'Thesa ID não fornecido.'
            ];
        }
        $SourceLinkedData = new \App\Models\Linkeddata\Source_rdf();
        $type = $SourceLinkedData->getType($url);
        if ($type['status'] != '200') {
            return $type;
        }

        /************************************ Read Json */
        $Languages = new \App\Models\Thesa\Language();
        $lang = $Languages->getLanguage($th);
        $Langs = [];
        foreach ($lang as $item) {
            $Langs[$item['lg_code']] = $item['lg_code'];
        }

        try {
            $Json = $this->readJson($type['url']);
            switch ($type['type']) {
                case 'loterre':
                    $Loterre = new \App\Models\Skos\Schemes\Loterre();
                    $Data = $Loterre->extract($Json);

                    pre($Data);
                    $Terms = $Data['terms'] ?? [];

                    /********** Registras Termos */
                    foreach ($Terms['prefLabel'] as $item) {
                        //echo $Languages->convert($item);
                        $lang = $Languages->convert($item['lang']);
                        $term = nbr_title($item['value'] ?? '');

                        $idt = $Term->register($term, $lang, $th);
                        $TermTh->register($idt, $th, 0);
                        echo '==>'.$idt. ' == '.$term.'<br>';
                    }
                    /************ Criar Concepts */
                    break;
                default:
                    return [
                        'status' => '500',
                        'message' => 'Tipo de dado não suportado: ' . $type['type']
                    ];
            }
            pre($type);
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
        return $RSP;
    }

    function readJson(string $url, int $timeoutSeconds = 30): array
    {
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

            return ['status' => '200', 'message' => 'Dados extraídos com sucesso.', 'data' => $data];
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

        return ['status' => '200', 'message' => 'Dados extraídos com sucesso.', 'data' => $data];
    }
}
