<?php

namespace Unistra\ProfetesBundle\ExistDB;

/**
 * Une formation au format CDM
 *
 */
class FormationCDM
{
    /**
     * Le XML
     *
     * @var string
     */
    private $formation_xml = '';

    /**
     * Le document au format DOMDocument
     *
     * @var \DOMDocument
     */
    private $formation_dom;

    /**
     * Les paramètres XSLT
     *
     * @var array
     */
    private $xsl_parameters = array();

    public function __construct()
    {
        $this->formation_dom = new \DOMDocument();
    }

    /**
     * Définit le document à partir du XML
     *
     * @param string $xml le XML
     */
    public function setXml($xml)
    {
        $this->formation_xml = $xml;
        $this->formation_dom->loadXML($xml);
    }

    /**
     * Retourn le document en XML
     *
     * @return string
     */
    public function getXml()
    {
        return $this->formation_dom->saveXML();
    }

    /**
     * Retourne le document au format DOMDocument
     *
     * @return \DOMDocument
     */
    public function getDOMDocument()
    {
        return $this->formation_dom;
    }

    /**
     * Effectue une transformation XSLT sur le document
     *
     * @param  string $xsl la feuille de style XSLT à appliquer
     * @return string
     */
    public function transform($xsl)
    {
        if (is_file($xsl) && is_readable($xsl)) {
            $xsl = file_get_contents($xsl);
        }
        $this->xsl_dom = new \DOMDocument();
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

    /**
     * Définit un paramètre XSLT
     *
     * @param string $name  Nom du paramètre
     * @param string $value Valeur du paramètre
     */
    public function setXsltParameter($name, $value)
    {
        $this->xsl_parameters[$name] = $value;
    }

    /**
     * Extrait le titre du diplôme
     *
     * @param  string $language = 'fr-FR' langue du titre
     * @return string
     */
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

    /**
     * Extrait les mot-clés du diplôme
     *
     * @return string
     */
    public function getSearchword()
    {
        $searchwords = array();
        $xpath = new \DOMXPath($this->formation_dom);
        $xpath->registerNameSpace('cdm', 'http://cdm-fr.fr/2006/CDM-frSchema');
        $searchword = "//cdm:program/cdm:searchword";
        $nodes = $xpath->query($searchword);
        foreach ($nodes as $node) {
            $searchwords[] = $node->textContent;
        }

        return implode(', ', $searchwords);
    }
}
