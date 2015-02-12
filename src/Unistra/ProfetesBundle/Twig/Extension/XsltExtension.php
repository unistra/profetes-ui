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

namespace Unistra\ProfetesBundle\Twig\Extension;

class XsltExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('xslt_transform', array($this, 'xsltTransform')),
        );
    }

    /**
     * Convertit un string xml à l'aide d'une feuille de style XSLT
     *
     * @param  string $xml    le flux XML à transformer
     * @param  string $xslt   le flux ou fichier XSLT
     * @param  array  $params des paramètres à passer au processeur
     * @return string le flux transformé
     */
    public function xsltTransform($xml, $xslt, $params = array())
    {
        $out = '';
        $xmldoc = new \DOMDocument();
        $xsldoc = new \DOMDocument();
        $processor = new \XSLTProcessor();

        if (is_file($xslt) && is_readable($xslt)) {
            $xsldoc->load($xslt);
        } else {
            $xsldoc->loadXML($xslt);
        }
        $xmldoc->loadXML($xml);
        $processor->importStyleSheet($xsldoc);

        if (is_array($params) && count($params)) {
            foreach ($params as $name => $value) {
                $processor->setParameter('', $name, $value);
            }
        }

        $out = $processor->transformToXML($xmldoc);

        return $out;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'unistra_xslt_extension';
    }
}
