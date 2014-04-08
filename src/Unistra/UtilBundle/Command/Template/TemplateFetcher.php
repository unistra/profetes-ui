<?php

namespace Unistra\UtilBundle\Command\Template;

use Buzz\Browser;

class TemplateFetcher
{
    private $fetchedHtml = array();
    private $browser;

    public function __construct()
    {
        $this->browser = new Browser();
    }

    public function fetch($pageToFetch, $templateFile, $xslFile)
    {
        $html = $this->fetchPage($pageToFetch);
        if (!$this->checkPage($html)) {
            throw new \Exception('Echec des tests XPath de la page');
        }
        $templateContent = $this->xsltTransform($html, $xslFile);

        return $this->saveTemplate($templateContent, $templateFile);

    }

    private function fetchPage($url)
    {
        if (array_key_exists($url, $this->fetchedHtml)) {
            return $this->fetchedHtml[$url];
        }

        $response = $this->browser->get($url);
        if (!$response->isOk()) {
            throw new \Exception(sprintf('%s fetch not a 200 OK', $url));
        }
        if (!$response->toDomDocument()) {
            throw new \Exception('Unable to convert to DOMDocument');
        }
        $xml = $response->toDomDocument();

        $this->fetchedHtml[$url] = $xml;

        return $xml;
    }

    private function xsltTransform(\DOMDocument $xml, $xsl)
    {
        $template = '';

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

    private function saveTemplate($template, $file)
    {
        file_put_contents($file, $template);
        return true;
    }

    private function checkPage(\DOMDocument $domDocument)
    {
        $successes = 0;
        $xpath = new \DOMXpath($domDocument);
        $checks = array(
            '//h1[@id="page-title"]',
            '//div[@id="breadcrumb"]/ul/li',
            '//div[@id="main-content"]',
        );

        foreach($checks as $check) {
            $nodeList = $xpath->query($check);
            if ($nodeList) {
                if ($nodeList->length) {
                    $successes += 1;
                } else {
                    throw new \Exception(sprintf('La requête %s a échoué', $check));
                }
            }
        }

        return ($successes === count($checks));
    }
}
