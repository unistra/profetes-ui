<?php

namespace Unistra\ProfetesBundle\ExistDB;


class FormationCDM
{
    private $formation_xml = '';
    private $formation_dom;
    private $xsl_parameters = array();


    public function __construct()
    {
        $this->formation_dom = new \DOMDocument();
    }


    public function setXml($xml)
    {
        $this->formation_xml = $xml;
        $this->formation_dom->loadXML($xml);
    }


    public function getXml()
    {
        return $this->formation_dom->saveXML();
    }


    public function transform($xsl)
    {
        if (is_file($xsl) && is_readable($xsl))
        {
            $xsl = file_get_contents($xsl);
        }
        $this->xsl_dom = new DOMDocument();
        $this->xsl_dom->loadXML($xsl);

        $xsltprocessor = new \XSLTProcessor;
        $xsltprocessor->importStyleSheet($this->xsl_dom);

        if (is_array($this->xsl_parameters)) {
            foreach ($this->xsl_parameters as $name => $value) {
                $xsltprocessor->setParameter('', $name, $value);
            }
        }

        return $xsltprocessor->transformToXML($this->formation_dom);
    }


    public function setXsltParameter($name, $value)
    {
        $this->xsl_parameters[$name] = $value;
    }


    public function getProgramName($language = 'fr-FR')
    {
        $xpath = new \DOMXPath($this->formation_dom);
        $xpath->registerNameSpace('cdm', 'http://cdm-fr.fr/2006/CDM-frSchema');
        $title = sprintf(
            "/cdm:CDM/cdm:program/cdm:programName/cdm:text[@language = '%s']/text()",
            $language
            );
        return $xpath->query($title)->item(0)->nodeValue;
    }

}

