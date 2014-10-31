<?php

namespace Unistra\Profetes\Tests;

use Unistra\Profetes\Program;

class ProgramTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage XML is not valid
     */
    public function testMustBeConstructedWithValidXML()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><root><child-node></root>';
        $program = new Program($xml);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage XML is not valid
     */
    public function testMustBeConstructedWithXMLString()
    {
        $program = new Program('Some stupid string');
    }

    public function testGetProgramNameFromCDM()
    {
        $xml = file_get_contents(__DIR__.'/DataFixtures/program-cdm.xml');
        $program = new Program($xml);
        $this->assertEquals('Licence Anglais', $program->getProgramName());
    }

    public function testGetProgramKeywordsFromCDM()
    {
        $xml = file_get_contents(__DIR__.'/DataFixtures/program-cdm.xml');
        $expected = ['licence', 'anglais', 'langue',];
        $program = new Program($xml);
        $this->assertEquals($expected, $program->getSearchWords());
    }

    public function testGetXmlReturnsTheDocumentXml()
    {
        $xml = file_get_contents(__DIR__.'/DataFixtures/program-cdm.xml');
        $program = new Program($xml);
        $this->assertXmlStringEqualsXmlString($xml, $program->getXml());
    }
}
