<?php

namespace App\Controllers;

use App\Libraries\RdfXmlExporter;
use App\Libraries\RdfGraphSerializer;
use CodeIgniter\HTTP\ResponseInterface;

class ApiExport extends BaseController
{
    public function xml(int $id): ResponseInterface
    {
        return $this->download($id, 'xml');
    }

    public function download(int $id, string $format): ResponseInterface
    {
        if (!in_array($format, ['xml', 'turtle', 'json', 'txt'], true)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Format not found']);
        }

        $db = \Config\Database::connect();
        $requestedId = $id;
        $scope = (string) ($this->request->getGet('scope') ?? '');
        if (!in_array($scope, ['', 'concept', 'thesaurus'], true)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid scope']);
        }

        $scheme = $scope === 'concept' ? null : $db->table('thesa')->where('id_th', $id)->get()->getRowArray();
        $concept = null;
        if ($scope === 'concept' || ($scope === '' && $scheme === null)) {
            $concept = $db->table('thesa_concept')
                ->select('c_concept, c_th')
                ->where('c_concept', $id)
                ->where('c_ativo', 1)
                ->get()->getRowArray();
            if ($concept === null) {
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Concept not found']);
            }
            $id = (int) $concept['c_th'];
            $scheme = $db->table('thesa')->where('id_th', $id)->get()->getRowArray();
        }

        if ($scheme === null) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Thesaurus not found']);
        }

        if ((int) $scheme['th_status'] !== 1) {
            $apiKey = trim((string) (
                $this->request->getGet('apikey')
                ?? $this->request->getGet('APIKEY')
                ?? $this->request->getHeaderLine('X-API-Key')
            ));
            if ($apiKey !== '') {
                $keyOwner = $db->table('users')
                    ->select('id_us')
                    ->where('us_apikey', $apiKey)
                    ->get()->getRowArray();
                $user = (int) ($keyOwner['id_us'] ?? 0);
            } else {
                $user = (int) (session()->get('user_id') ?? 0);
            }
            $isOwner = $user > 0 && (int) $scheme['th_own'] === $user;
            $isCollaborator = $user > 0 && $db->table('thesa_users')
                ->where('th_us_th', $id)
                ->where('th_us_user', $user)
                ->countAllResults() > 0;
            if (!$isOwner && !$isCollaborator) {
                return $this->response->setStatusCode(403)->setJSON(['error' => 'Access denied']);
            }
        }

        $concepts = $db->table('thesa_concept')
            ->select('c_concept, c_created, c_updated')
            ->where('c_th', $id)
            ->where('c_ativo', 1)
            ->orderBy('c_concept')
            ->get()->getResultArray();

        $labels = $db->table('thesa_concept_term ct')
            ->select('ct.ct_concept, p.p_name, t.term_name, l.lg_cod_short, l.lg_code')
            ->join('thesa_concept c', 'c.c_concept = ct.ct_concept')
            ->join('thesa_property p', 'p.id_p = ct.ct_propriety')
            ->join('thesa_terms t', 't.id_term = ct.ct_literal')
            ->join('language l', 'l.id_lg = t.term_lang', 'left')
            ->where('c.c_th', $id)
            ->where('c.c_ativo', 1)
            ->where('ct.ct_th', $id)
            ->whereIn('p.p_name', ['prefLabel', 'altLabel', 'hiddenLabel'])
            ->orderBy('ct.ct_concept')
            ->orderBy('ct.id_ct')
            ->get()->getResultArray();

        $notes = $db->table('thesa_notes n')
            ->select('n.nt_concept, n.nt_content, p.p_name, l.lg_cod_short, l.lg_code')
            ->join('thesa_concept c', 'c.c_concept = n.nt_concept')
            ->join('thesa_property p', 'p.id_p = n.nt_prop')
            ->join('language l', 'l.id_lg = n.nt_lang', 'left')
            ->where('c.c_th', $id)
            ->where('c.c_ativo', 1)
            ->orderBy('n.nt_concept')
            ->orderBy('n.id_nt')
            ->get()->getResultArray();

        $broader = $db->table('thesa_broader')
            ->select('b_concept_boader, b_concept_narrow')
            ->where('b_th', $id)->get()->getResultArray();

        $related = $db->table('thesa_related')
            ->select('r_c1, r_c2')
            ->where('r_th', $id)->get()->getResultArray();

        $exactMatches = $db->table('thesa_exactmatch e')
            ->select('e.em_concept, e.em_link')
            ->join('thesa_concept c', 'c.c_concept = e.em_concept')
            ->where('c.c_th', $id)
            ->where('c.c_ativo', 1)
            ->get()->getResultArray();

        $xml = (new RdfXmlExporter())->render(
            $scheme,
            $concepts,
            $labels,
            $notes,
            $broader,
            $related,
            $exactMatches,
            rtrim(base_url(), '/')
        );

        if ($concept !== null) {
            $xml = (new RdfGraphSerializer())->onlyConcept($xml, rtrim(base_url(), '/') . '/v/' . $requestedId);
        }

        $types = [
            'xml' => 'application/rdf+xml',
            'turtle' => 'text/turtle',
            'json' => 'application/ld+json',
            'txt' => 'text/plain',
        ];
        $extensions = ['xml' => 'xml', 'turtle' => 'ttl', 'json' => 'jsonld', 'txt' => 'txt'];
        if ($format !== 'xml') {
            $serializer = new RdfGraphSerializer();
            $triples = $serializer->triples($xml);
            $xml = match ($format) {
                'turtle' => $serializer->turtle($triples),
                'json' => $serializer->jsonLd($triples),
                'txt' => $serializer->text($triples),
            };
        }

        return $this->response
            ->setContentType($types[$format], 'UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . ($concept === null ? 'thesa-' : 'concept-') . $requestedId . '.' . $extensions[$format] . '"')
            ->setBody($xml);
    }
}
