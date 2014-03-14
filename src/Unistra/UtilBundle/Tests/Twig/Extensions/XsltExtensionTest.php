<?php

namespace Unistra\UtilBundle\Tests\Twig\Extensions;

use Unistra\UtilBundle\Twig\Extensions\XsltExtension;

class XsltExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testXsltTransform()
    {
        $xslExt = new XsltExtension();

        $this->assertRegExp('/st/', 'test');
        $this->assertRegExp('/<b>Test<\/b>/', $this->getXml());
        $this->assertRegExp('/<p>Test<\/p>/', $xslExt->xsltTransform(
            $this->getXml(),
            $this->getXsl()
        ));
    }

    protected function getXml()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<a><b>Test</b></a>';

        return $xml;
    }

    protected function getXsl()
    {
        $xsl = '<?xml version="1.0" encoding="UTF-8"?>';
        $xsl .= '<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">';
        $xsl .= '<xsl:template match="/">';
        $xsl .= '<p><xsl:value-of select="/a/b" /></p>';
        $xsl .= '</xsl:template>';
        $xsl .= '</xsl:stylesheet>';

        return $xsl;
    }

}
