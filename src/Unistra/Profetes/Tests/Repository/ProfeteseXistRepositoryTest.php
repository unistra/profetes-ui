<?php

namespace Unistra\Profetes\Test\Repository;

use Unistra\Profetes\Repository\ProfeteseXistRepository;

class ProfeteseXistRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetProgramFromDatabase()
    {
        $ttl = 100;
        $path = 'fr-rne-0673021v-pr-ab123-456';
        $xml = file_get_contents(__DIR__.'/../DataFixtures/program-cdm.xml');

        $eXistDB = $this->getExistDbMock();
        $eXistDB->expects($this->once())
            ->method('getResource')
            ->with($path)
            ->willReturn($xml);

        $cache = $this->getCacheMock();
        $cache->expects($this->once())
            ->method('fetch')
            ->with($path)
            ->willReturn(false);
        $cache->expects($this->once())
            ->method('save')
            ->with($path, $xml, $ttl);

        $programId = $this->getProgramIdMock($path);

        $repository = new ProfeteseXistRepository($eXistDB, $cache, $ttl, 0);
        $program = $repository->getProgram($programId);
        $this->assertInstanceOf('Unistra\Profetes\Program', $program);
        $this->assertXmlStringEqualsXmlString($xml, $program->getXml());
    }

    public function testGetProgramFromCache()
    {
        $ttl = 100;
        $path = 'fr-rne-0673021v-pr-ab123-456';
        $xml = file_get_contents(__DIR__.'/../DataFixtures/program-cdm.xml');

        $eXistDB = $this->getExistDbMock();
        $eXistDB->expects($this->never())
            ->method('getResource');

        $cache = $this->getCacheMock();
        $cache->expects($this->once())
            ->method('fetch')
            ->willReturn($xml);
        $cache->expects($this->never())
            ->method('save');

        $programId = $this->getProgramIdMock($path);

        $repository = new ProfeteseXistRepository($eXistDB, $cache, $ttl, 0);
        $program = $repository->getProgram($programId);
        $this->assertInstanceOf('Unistra\Profetes\Program', $program);
        $this->assertXmlStringEqualsXmlString($xml, $program->getXml());
    }

    public function testGetQueryFromDatabase()
    {
        $queryResult = 'query result';
        $ttl = 100;

        $eXistDB = $this->getExistDbMock();
        $eXistDB->expects($this->once())
            ->method('xquery')
            ->willReturn($queryResult);

        $cache = $this->getCacheMock();
        $cache->expects($this->once())
            ->method('fetch')
            ->willReturn(false);
        $cache->expects($this->once())
            ->method('save');

        $xquery = $this->getXQueryMock();

        $repository = new ProfeteseXistRepository($eXistDB, $cache, 0, $ttl);
        $result = $repository->query($xquery);

        $this->assertEquals($queryResult, $result);
    }

    public function testGetQueryFromCache()
    {
        $queryResult = 'query result';
        $ttl = 100;

        $eXistDB = $this->getExistDbMock();
        $eXistDB->expects($this->never())
            ->method('xquery');

        $cache = $this->getCacheMock();
        $cache->expects($this->once())
            ->method('fetch')
            ->willReturn($queryResult);
        $cache->expects($this->never())
            ->method('save');

        $xquery = $this->getXQueryMock();

        $repository = new ProfeteseXistRepository($eXistDB, $cache, 0, $ttl);
        $result = $repository->query($xquery);

        $this->assertEquals($queryResult, $result);
    }

    private function getExistDbMock()
    {
        $eXistDB = $this->getMockBuilder('Unistra\Profetes\eXist\eXistDB')
            ->disableOriginalConstructor()
            ->getMock();

        return $eXistDB;
    }

    private function getCacheMock()
    {
        $cache = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->setMethods(['fetch', 'save',])
            ->getMockForAbstractClass();

        return $cache;
    }

    private function getXQueryMock()
    {
        $xquery = $this->getMockBuilder('Unistra\Profetes\XQuery')
            ->disableOriginalConstructor()
            ->getMock();
        $xquery->expects($this->once())
            ->method('getXQuery');

        return $xquery;
    }

    private function getProgramIdMock($path)
    {
        $programId = $this->getMockBuilder('Unistra\Profetes\ProgramId')
            ->disableOriginalConstructor()
            ->getMock();
        $programId->expects($this->once())
            ->method('getResourcePath')
            ->will($this->returnValue($path));

        return $programId;
    }
}
