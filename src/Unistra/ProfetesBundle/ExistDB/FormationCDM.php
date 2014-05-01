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
    private $formationXml = '';

    /**
     * Le document au format DOMDocument
     *
     * @var \DOMDocument
     */
    private $formationDom;

    /**
     * Les paramètres XSLT
     *
     * @var array
     */
    private $xslParameters = array();

    public function __construct()
    {
        $this->formationDom = new \DOMDocument();
    }

    /**
     * Définit le document à partir du XML
     *
     * @param string $xml le XML
     */
    public function setXml($xml)
    {
        $this->formationXml = $xml;
        $this->formationDom->loadXML($xml);
    }

    /**
     * Retourn le document en XML
     *
     * @return string
     */
    public function getXml()
    {
        return $this->formationDom->saveXML();
    }

    /**
     * Retourne le document au format DOMDocument
     *
     * @return \DOMDocument
     */
    public function getDOMDocument()
    {
        return $this->formationDom;
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
        $this->xslDom = new \DOMDocument();
        $this->xslDom->loadXML($xsl);

        $xsltprocessor = new \XSLTProcessor;
        $xsltprocessor->importStyleSheet($this->xslDom);

        if (is_array($this->xslParameters)) {
            foreach ($this->xslParameters as $name => $value) {
                $xsltprocessor->setParameter('', $name, $value);
            }
        }

        return $xsltprocessor->transformToXML($this->formationDom);
    }

    /**
     * Définit un paramètre XSLT
     *
     * @param string $name  Nom du paramètre
     * @param string $value Valeur du paramètre
     */
    public function setXsltParameter($name, $value)
    {
        $this->xslParameters[$name] = $value;
    }

    /**
     * Extrait le titre du diplôme
     *
     * @param  string $language = 'fr-FR' langue du titre
     * @return string
     */
    public function getProgramName($language = 'fr-FR')
    {
        $xpath = new \DOMXPath($this->formationDom);
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
        $xpath = new \DOMXPath($this->formationDom);
        $xpath->registerNameSpace('cdm', 'http://cdm-fr.fr/2006/CDM-frSchema');
        $searchword = "//cdm:program/cdm:searchword";
        $nodes = $xpath->query($searchword);
        foreach ($nodes as $node) {
            $searchwords[] = $node->textContent;
        }

        return implode(', ', $searchwords);
    }
}
