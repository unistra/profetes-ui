<?php

namespace Unistra\ProfetesBundle\Tests\Twig\Extension;

use Unistra\ProfetesBundle\Twig\Extension\XsltExtension;

class XsltExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testXsltTransform()
    {
        $xsltExtension = new XsltExtension();

        $xml = $this->getXml();
        $xsl = $this->getXsl();

        $result = $xsltExtension->xsltTransform($xml, $xsl);

        $this->assertXmlStringEqualsXmlString("<p>Test</p>", $result);
    }

    public function testXslTransformWithParameter()
    {
        $xsltExtension = new XsltExtension();

        $xml = $this->getXml();
        $xsl = $this->getXslWithParameter();

        $result = $xsltExtension->xsltTransform($xml, $xsl, ['test-param' => 'Param value',]);

        $this->assertXmlStringEqualsXmlString(
            "<p>TestParam value</p>",
            $result
        );
    }

    public function testXslTransformWithDefaultParameter()
    {
        $xsltExtension = new XsltExtension();

        $xml = $this->getXml();
        $xsl = $this->getXslWithParameter();

        $result = $xsltExtension->xsltTransform($xml, $xsl);

        $this->assertXmlStringEqualsXmlString(
            "<p>Testdefault-param-value</p>",
            $result
        );
    }

    public function testWithXslFile()
    {
        $xsltExtension = new XsltExtension();

        $xml = $this->getXml();
        $xsl = __DIR__.'/DataFixtures/template.xsl';

        $result = $xsltExtension->xsltTransform($xml, $xsl);

        $this->assertXmlStringEqualsXmlString(
            '<p>Test</p>',
            $result
        );
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

    protected function getXslWithParameter()
    {
        $xsl = '<?xml version="1.0" encoding="UTF-8"?>';
        $xsl .= '<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">';
        $xsl .= '<xsl:param name="test-param">default-param-value</xsl:param>';
        $xsl .= '<xsl:template match="/">';
        $xsl .= '<p><xsl:value-of select="/a/b" /><xsl:value-of select="$test-param"/></p>';
        $xsl .= '</xsl:template>';
        $xsl .= '</xsl:stylesheet>';

        return $xsl;
    }
}
