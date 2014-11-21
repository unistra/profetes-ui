<?php

namespace Unistra\Profetes\Tests;

use Unistra\Profetes\Program;

class ProgramTest extends \PHPUnit_Framework_TestCase
{
    private $xml;

    public function setUp()
    {
        $this->xml = file_get_contents(__DIR__.'/DataFixtures/program-cdm.xml');
    }
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage XML is not valid
     */
    public function testMustBeConstructedWithValidXml()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><root><child-node></root>';
        $program = new Program($xml);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage XML is not valid
     */
    public function testMustBeConstructedWithXmlString()
    {
        $program = new Program('Some stupid string');
    }

    public function testGetProgramNameFromCdm()
    {
        $program = new Program($this->xml);
        $this->assertEquals('Licence Anglais', $program->getProgramName());
    }

    public function testGetProgramKeywordsFromCdm()
    {
        $expected = ['licence', 'anglais', 'langue'];
        $program = new Program($this->xml);
        $this->assertEquals($expected, $program->getSearchWords());
    }

    public function testGetXmlReturnsTheDocumentXml()
    {
        $program = new Program($this->xml);
        $this->assertXmlStringEqualsXmlString($this->xml, $program->getXml());
    }
}
