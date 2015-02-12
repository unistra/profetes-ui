<?php

/*
 * Copyright Université de Strasbourg (2015)
 *
 * Daniel Bessey <daniel.bessey@unistra.fr>
 *
 * This software is a computer program whose purpose is to diplay course information
 * extracted from a Profetes database on a website.
 *
 * See LICENSE for more details
 */

namespace Unistra\Profetes;

class Program
{
    /**
     * The CDM namespace
     */
    const CDM_NAMESPACE = 'http://cdm-fr.fr/2006/CDM-frSchema';

    private $xml;

    private $domDocument;

    /**
     * A cdm:Program is built from its XML
     */
    public function __construct($xml)
    {
        $this->loadDomDocument($xml);
        $this->xml = $xml;
    }

    /**
     * @return string
     */
    public function getXml()
    {
        return $this->domDocument->saveXML();
    }

    /**
     * The content of the cdm:ProgramName in the given language
     *
     * @param  string $language
     * @return string
     */
    public function getProgramName($language = 'fr-FR')
    {
        $query = sprintf(
            "/cdm:CDM/cdm:program/cdm:programName/cdm:text[@language = '%s']/text()",
            $language
        );
        $programName = $this->executeXPath($query)->item(0)->nodeValue;

        return $programName;
    }

    /**
     * The content of the cdm:SearchWord (keywords) of the Program
     * @return array
     */
    public function getSearchWords()
    {
        $searchWords = array();
        $query = "//cdm:program/cdm:searchword";
        $nodes = $this->executeXPath($query);
        foreach ($nodes as $node) {
            $searchWords[] = $node->textContent;
        }

        return $searchWords;
    }

    private function executeXPath($query)
    {
        $xpath = new \DOMXPath($this->domDocument);
        $xpath->registerNamespace('cdm', self::CDM_NAMESPACE);

        return $xpath->query($query);
    }

    private function loadDomDocument($xml)
    {
        $this->domDocument = new \DOMDocument();
        if (!$this->domDocument->loadXML($xml, LIBXML_NOERROR)) {
            throw new \InvalidArgumentException("XML is not valid");
        }
    }
}
