<?php

namespace App\Libraries;

use DOMDocument;
use DOMElement;
use DOMText;

class RdfGraphSerializer
{
    private const RDF = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
    private const SKOS = 'http://www.w3.org/2004/02/skos/core#';
    private const RDFS = 'http://www.w3.org/2000/01/rdf-schema#';
    private const DC = 'http://purl.org/dc/terms/';

    private const PREFIXES = [
        'rdf' => self::RDF,
        'skos' => self::SKOS,
        'rdfs' => self::RDFS,
        'dc' => self::DC,
    ];

    public function onlyConcept(string $rdfXml, string $conceptUri): string
    {
        $document = new DOMDocument();
        if (!$document->loadXML($rdfXml, LIBXML_NONET)) {
            throw new \RuntimeException('Invalid RDF/XML');
        }

        $root = $document->documentElement;
        foreach (iterator_to_array($root->childNodes) as $node) {
            if ($node instanceof DOMText && trim($node->textContent) === '') {
                $root->removeChild($node);
            } elseif ($node instanceof DOMElement
                && $node->namespaceURI === self::RDF
                && $node->localName === 'Description'
                && $node->getAttributeNS(self::RDF, 'about') !== $conceptUri
            ) {
                $root->removeChild($node);
            }
        }

        $document->formatOutput = true;
        return $document->saveXML();
    }

    public function triples(string $rdfXml): array
    {
        $document = new DOMDocument();
        if (!$document->loadXML($rdfXml, LIBXML_NONET)) {
            throw new \RuntimeException('Invalid RDF/XML');
        }

        $triples = [];
        $root = $document->documentElement;
        foreach ($root->childNodes as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }
            $subject = $node->getAttributeNS(self::RDF, 'about');
            if ($subject === '') {
                continue;
            }
            if ($node->namespaceURI !== self::RDF || $node->localName !== 'Description') {
                $triples[] = [$subject, self::RDF . 'type', $node->namespaceURI . $node->localName, 'uri', '', ''];
            }
            foreach ($node->childNodes as $property) {
                if (!$property instanceof DOMElement) {
                    continue;
                }
                $predicate = $property->namespaceURI . $property->localName;
                $resource = $property->getAttributeNS(self::RDF, 'resource');
                if ($resource !== '') {
                    $triples[] = [$subject, $predicate, $resource, 'uri', '', ''];
                    continue;
                }
                $triples[] = [
                    $subject,
                    $predicate,
                    $property->textContent,
                    'literal',
                    $property->getAttributeNS('http://www.w3.org/XML/1998/namespace', 'lang'),
                    $property->getAttributeNS(self::RDF, 'datatype'),
                ];
            }
        }

        return $triples;
    }

    public function turtle(array $triples): string
    {
        $lines = [];
        foreach (self::PREFIXES as $prefix => $uri) {
            $lines[] = '@prefix ' . $prefix . ': <' . $uri . '> .';
        }
        $lines[] = '';

        foreach ($triples as [$subject, $predicate, $object, $kind, $language, $datatype]) {
            $value = $kind === 'uri'
                ? $this->term($object)
                : $this->quote($object)
                    . ($language !== '' ? '@' . $language : '')
                    . ($datatype !== '' ? '^^' . $this->term($datatype) : '');
            $lines[] = $this->term($subject) . ' ' . $this->term($predicate) . ' ' . $value . ' .';
        }

        return implode("\n", $lines) . "\n";
    }

    public function jsonLd(array $triples): string
    {
        $nodes = [];
        foreach ($triples as [$subject, $predicate, $object, $kind, $language, $datatype]) {
            if (!isset($nodes[$subject])) {
                $nodes[$subject] = ['@id' => $subject];
            }
            if ($predicate === self::RDF . 'type' && $kind === 'uri') {
                $nodes[$subject]['@type'][] = $object;
                continue;
            }
            $key = $this->compact($predicate);
            if ($kind === 'uri') {
                $value = ['@id' => $object];
            } elseif ($language !== '') {
                $value = ['@value' => $object, '@language' => $language];
            } elseif ($datatype !== '') {
                $value = ['@value' => $object, '@type' => $datatype];
            } else {
                $value = $object;
            }
            $nodes[$subject][$key][] = $value;
        }

        return $this->encodeJson(
            ['@context' => self::PREFIXES, '@graph' => array_values($nodes)],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        ) . "\n";
    }

    public function text(array $triples): string
    {
        $lines = [];
        $subject = null;
        foreach ($triples as [$current, $predicate, $object, $kind, $language, $datatype]) {
            if ($current !== $subject) {
                if ($subject !== null) {
                    $lines[] = '';
                }
                $subject = $current;
                $lines[] = $subject;
            }
            $label = $this->compact($predicate);
            if ($language !== '') {
                $label .= ' [' . $language . ']';
            }
            $lines[] = '  ' . $label . ': ' . $object;
        }

        return implode("\n", $lines) . "\n";
    }

    private function term(string $uri): string
    {
        foreach (self::PREFIXES as $prefix => $namespace) {
            if (strpos($uri, $namespace) === 0) {
                return $prefix . ':' . substr($uri, strlen($namespace));
            }
        }
        return '<' . $uri . '>';
    }

    private function compact(string $uri): string
    {
        foreach (self::PREFIXES as $prefix => $namespace) {
            if (strpos($uri, $namespace) === 0) {
                return $prefix . ':' . substr($uri, strlen($namespace));
            }
        }
        return $uri;
    }

    private function encodeJson($value, int $options): string
    {
        $json = json_encode($value, $options);
        if ($json === false) {
            throw new \RuntimeException('Failed to encode JSON: ' . json_last_error_msg());
        }
        return $json;
    }

    private function quote(string $value): string
    {
        return $this->encodeJson($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
