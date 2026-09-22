<?php

namespace App\Libraries;

use XMLWriter;

class RdfXmlExporter
{
    private const RDF = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
    private const SKOS = 'http://www.w3.org/2004/02/skos/core#';
    private const RDFS = 'http://www.w3.org/2000/01/rdf-schema#';
    private const DC = 'http://purl.org/dc/terms/';

    public function render(
        array $scheme,
        array $concepts,
        array $labels,
        array $notes,
        array $broader,
        array $related,
        array $exactMatches,
        string $baseUrl
    ): string {
        $schemeUri = $baseUrl . '/th/' . (int) $scheme['id_th'];
        $conceptUri = static function (int $id) use ($baseUrl): string {
            return $baseUrl . '/v/' . $id;
        };
        $conceptIds = array_fill_keys(array_map(
            static function (array $concept): int {
                return (int) $concept['c_concept'];
            },
            $concepts
        ), true);

        $byConcept = [];
        foreach ($labels as $row) {
            $id = (int) $row['ct_concept'];
            if (isset($conceptIds[$id])) {
                $byConcept[$id]['labels'][] = $row;
            }
        }
        foreach ($notes as $row) {
            $id = (int) $row['nt_concept'];
            if (isset($conceptIds[$id])) {
                $byConcept[$id]['notes'][] = $row;
            }
        }
        foreach ($broader as $row) {
            $parent = (int) $row['b_concept_boader'];
            $child = (int) $row['b_concept_narrow'];
            if ($parent === $child || !isset($conceptIds[$parent], $conceptIds[$child])) {
                continue;
            }
            $byConcept[$child]['broader'][$parent] = true;
            $byConcept[$parent]['narrower'][$child] = true;
        }
        foreach ($related as $row) {
            $first = (int) $row['r_c1'];
            $second = (int) $row['r_c2'];
            if ($first === $second || !isset($conceptIds[$first], $conceptIds[$second])) {
                continue;
            }
            $byConcept[$first]['related'][$second] = true;
            $byConcept[$second]['related'][$first] = true;
        }
        foreach ($exactMatches as $row) {
            $id = (int) $row['em_concept'];
            $uri = trim((string) $row['em_link']);
            if (isset($conceptIds[$id]) && filter_var($uri, FILTER_VALIDATE_URL)) {
                $byConcept[$id]['exactMatch'][$uri] = true;
            }
        }

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('  ');
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElementNS('rdf', 'RDF', self::RDF);
        $xml->writeAttribute('xmlns:skos', self::SKOS);
        $xml->writeAttribute('xmlns:rdfs', self::RDFS);
        $xml->writeAttribute('xmlns:dc', self::DC);

        $xml->startElementNS('skos', 'ConceptScheme', self::SKOS);
        $xml->writeAttributeNS('rdf', 'about', self::RDF, $schemeUri);
        $this->literal($xml, 'rdfs', 'label', self::RDFS, (string) $scheme['th_name']);
        if (trim((string) ($scheme['th_description'] ?? '')) !== '') {
            $this->literal($xml, 'dc', 'description', self::DC, (string) $scheme['th_description']);
        }
        $xml->endElement();

        foreach ($concepts as $concept) {
            $id = (int) $concept['c_concept'];
            $properties = $byConcept[$id] ?? [];
            $xml->startElementNS('rdf', 'Description', self::RDF);
            $xml->writeAttributeNS('rdf', 'about', self::RDF, $conceptUri($id));
            $this->resource($xml, 'rdf', 'type', self::RDF, self::SKOS . 'Concept');
            $this->resource($xml, 'skos', 'inScheme', self::SKOS, $schemeUri);

            foreach ($properties['labels'] ?? [] as $label) {
                $property = (string) $label['p_name'];
                if (trim((string) $label['term_name']) !== '') {
                    $this->literal($xml, 'skos', $property, self::SKOS, (string) $label['term_name'], $this->language($label));
                }
            }
            $noteProperties = [
                'note' => 'note',
                'scopeNote' => 'scopeNote',
                'definition' => 'definition',
                'example' => 'example',
                'historyNote' => 'historyNote',
                'editorialNote' => 'editorialNote',
                'changeNote' => 'changeNote',
                'notation' => 'notation',
            ];
            foreach ($properties['notes'] ?? [] as $note) {
                $property = $noteProperties[(string) $note['p_name']] ?? null;
                if ($property !== null && trim((string) $note['nt_content']) !== '') {
                    $this->literal($xml, 'skos', $property, self::SKOS, (string) $note['nt_content'], $this->language($note));
                }
            }
            foreach (['broader', 'narrower', 'related'] as $relation) {
                foreach (array_keys($properties[$relation] ?? []) as $target) {
                    $this->resource($xml, 'skos', $relation, self::SKOS, $conceptUri((int) $target));
                }
            }
            foreach (array_keys($properties['exactMatch'] ?? []) as $uri) {
                $this->resource($xml, 'skos', 'exactMatch', self::SKOS, $uri);
            }
            foreach (['c_created' => 'created', 'c_updated' => 'modified'] as $column => $property) {
                $date = trim((string) ($concept[$column] ?? ''));
                if ($date !== '' && $date !== '0000-00-00' && strpos($date, '0000-00-00') !== 0) {
                    $value = str_replace(' ', 'T', $date);
                    $datatype = strpos($value, 'T') !== false ? 'dateTime' : 'date';
                    $this->literal($xml, 'dc', $property, self::DC, $value, '', 'http://www.w3.org/2001/XMLSchema#' . $datatype);
                }
            }
            $xml->endElement();
        }

        $xml->endElement();
        $xml->endDocument();

        return $xml->outputMemory();
    }

    private function language(array $row): string
    {
        $code = trim((string) (($row['lg_cod_short'] ?? '') ?: ($row['lg_code'] ?? '')));
        return preg_match('/^[a-zA-Z]{2,3}(?:-[a-zA-Z0-9]{2,8})*$/', $code) ? strtolower($code) : '';
    }

    private function literal(XMLWriter $xml, string $prefix, string $name, string $namespace, string $value, string $language = '', string $datatype = ''): void
    {
        $xml->startElementNS($prefix, $name, $namespace);
        if ($language !== '') {
            $xml->writeAttributeNS('xml', 'lang', 'http://www.w3.org/XML/1998/namespace', $language);
        }
        if ($datatype !== '') {
            $xml->writeAttributeNS('rdf', 'datatype', self::RDF, $datatype);
        }
        $xml->text($value);
        $xml->endElement();
    }

    private function resource(XMLWriter $xml, string $prefix, string $name, string $namespace, string $uri): void
    {
        $xml->startElementNS($prefix, $name, $namespace);
        $xml->writeAttributeNS('rdf', 'resource', self::RDF, $uri);
        $xml->endElement();
    }
}
