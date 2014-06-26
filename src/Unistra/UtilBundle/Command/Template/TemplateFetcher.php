<?php

namespace Unistra\UtilBundle\Command\Template;

use Buzz\Browser;
use Buzz\Client\Curl;

/**
 * Récupère les pages du site unistra et en fait des templates en applicant une
 * transformation à l'aide de feuille de style XSLT.
 *
 */
class TemplateFetcher
{
    private $fetchedHtml = array();
    private $checks = array();
    private $browser;
    private $lastModificationDate;

    public function __construct()
    {
        $client = new Curl();
        $client->setMaxRedirects(0);
        $this->browser = new Browser($client);
    }

    /**
     * Exécute la récupération et l'enregistrement
     *
     * @param string $pageToFetch  la page du site à récupérer
     * @param string $templateFile le nom du fichier sous lequel enregistrer le template
     * @param string $xslFile      le fichier XSL à utiliser pour la conversion
     *
     * @return boolean
     */
    public function fetch($pageToFetch, $templateFile, $xslFile)
    {
        $html = $this->fetchPage($pageToFetch);
        $this->checkPage($html);
        $templateContent = $this->xsltTransform($html, $xslFile);

        return $this->saveTemplate($templateContent, $templateFile);
    }

    /**
     * Définit les tests XPath à effectuer sur le document récupéré
     *
     * Pour garantir que la page récupérée n'est pas en erreur ou de manière
     * génrale est conforme à ce que l'on en attend, des tests peuvent être
     * exécutés pour le valider. Il s'agit d'un tableau d'expressions XPath qui
     * doivent retourner un ensemble de noeuds
     *
     * @param  array $checks tableau d'expressions XPath
     * @return void
     */
    public function setChecks($checks = null)
    {
        if (is_array($checks)) {
            $this->checks = $checks;
        }
    }

    public function getLastModification($format)
    {
        return $this->lastModificationDate->format($format);
    }

    /**
     * Page de base à récupérer
     *
     * @param string $url URL de la page
     * @return \DOMDocument le DOMDocument de la page
     * @throws \InvalidArgumentException if invalid $url
     * @throws \Exception if unable to convert $url to DOMDocument
     */
    private function fetchPage($url)
    {
        if (array_key_exists($url, $this->fetchedHtml)) {
            return $this->fetchedHtml[$url];
        }

        $response = $this->browser->get($url);
        if (!$response->isOk()) {
            throw new \InvalidArgumentException(sprintf('%s fetch not a 200 OK', $url));
        }
        if (!$response->toDomDocument()) {
            throw new \Exception('Unable to convert to DOMDocument');
        }
        $xml = $response->toDomDocument();

        $this->fetchedHtml[$url] = $xml;
        $this->checkLastModificationDate($xml);

        return $xml;
    }

    /**
     * Transformation XSLT pour obtenir les templates
     *
     * @param \DOMDocument $xml Le DOMDocument de la page du site
     * @param string      $xsl Le fichier XSL de transformation
     * @return string le template généré
     */
    private function xsltTransform(\DOMDocument $xml, $xsl)
    {
        $docXml = $xml;

        if (is_file($xsl) && is_readable($xsl)) {
            $xsl = file_get_contents($xsl);
        }
        $docXsl = new \DOMDocument();
        $docXsl->loadXML($xsl);

        $xsltProcessor = new \XSLTProcessor();
        $xsltProcessor->importStylesheet($docXsl);

        $template = $xsltProcessor->transformToXML($docXml);

        return $template;
    }

    /**
     * Enregistrement du fichier template
     *
     * @param string $template contenu du template
     * @param string $file     nom du fichier sous lequel enregistrer le template
     * @return boolean
     */
    private function saveTemplate($template, $file)
    {
        if (is_file($file) && file_get_contents($file) === $template) {
            return true;
        } else {
            $template = preg_replace('~\r\n?~', "\n", $template);
            file_put_contents($file, $template);
        }

        return true;
    }

    /**
     * Effectue les tests XPath sur le document
     *
     * @param \DOMDocument $domDocument
     * @return boolean
     * @throws \Exception if XPath test fails
     */
    private function checkPage(\DOMDocument $domDocument)
    {
        $successes = 0;
        $xpath = new \DOMXpath($domDocument);

        foreach ($this->checks as $check) {
            $nodeList = $xpath->query($check);
            if ($nodeList) {
                if ($nodeList->length) {
                    $successes += 1;
                } else {
                    throw new \Exception(sprintf('Le test XPath %s a échoué', $check));
                }
            }
        }

        return (count($this->checks) === $successes);
    }

    private function checkLastModificationDate(\DOMDocument $domDocument)
    {
        $lastModificationXPath = '/html/head/meta[@name="date"]/@content';
        $xpath = new \DOMXPath($domDocument);
        $resultNodes = $xpath->query($lastModificationXPath);
        $resultNode = $resultNodes->item(0);

        $this->lastModificationDate = new \DateTime($resultNode->nodeValue);
    }
}
