<?php

namespace Unistra\Profetes\Tests\eXist;

use Unistra\Profetes\eXist\eXistDB;

class eXistDBTest extends \PHPUnit_Framework_TestCase
{
    private $username = 'username';
    private $password = 'password';
    private $collection = 'collection-name';
    private $path = '%collection%/rest-of-path';
    private $soapReturn;

    public function setUp()
    {
        $soapReturn = new \stdClass();
        $soapReturn->connectReturn = '123';
        $soapReturn->getResourceReturn = 'result of getResource';
        $soapReturn->queryReturn = new \stdClass();
        $soapReturn->queryReturn->hits = 10;
        $this->soapReturn = $soapReturn;
    }

    public function testGetResource()
    {
        $soapClient = $this->geteXistDBMock(['connect', 'getResource']);
        $soapClient->expects($this->once())
            ->method('connect')
            ->willReturn($this->soapReturn);
        $soapClient->expects($this->once())
            ->method('getResource')
            ->willReturn($this->soapReturn);

        $db = new eXistDB($soapClient, $this->username, $this->password, $this->collection);
        $this->assertSame('result of getResource', $db->getResource($this->path));
    }

    public function testCollectionIsReplacedInResourcePath()
    {
        $getResourceParams = ['sessionId' => 123, 'path' => 'collection-name/rest-of-path', 'indent' => true, 'xinclude' => true];

        $soapClient = $this->geteXistDBMock(['connect', 'getResource']);
        $soapClient->expects($this->once())
            ->method('connect')
            ->willReturn($this->soapReturn);
        $soapClient->expects($this->once())
            ->method('getResource')
            ->with($getResourceParams)
            ->willReturn($this->soapReturn);

        $db = new eXistDB($soapClient, $this->username, $this->password, $this->collection);
        $db->getResource($this->path);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 404
     */
    public function testThrowsExceptionIfResourceNotFound()
    {
        $soapClient = $this->geteXistDBMock(['connect', 'getResource']);
        $soapClient->expects($this->once())
            ->method('connect')
            ->willReturn($this->soapReturn);
        $soapClient->expects($this->once())
            ->method('getResource')
            ->willThrowException(new \SoapFault(null, 'not found'));

        $db = new eXistDB($soapClient, $this->username, $this->password, $this->collection);
        $db->getResource($this->path);
    }

    /**
     * @expectedException   \Exception
     * @expectedExceptionMessage Unable to connect to soap server
     */
    public function testThrowsExceptionIfConnectionFails()
    {
        $username = $password = null;

        $soapClient = $this->geteXistDBMock(['connect']);
        $soapClient->expects($this->once())
            ->method('connect')
            ->willThrowException(new \SoapFault(null, null));

        $db = new eXistDB($soapClient, $username, $password, $this->collection);
        $db->getResource($this->path);
    }

    /**
     * @dataProvider xqueryResultProvider
     */
    public function testXQuery($withProlog, $resultOfQuery, $expectedReturnValue)
    {
        $xquery = 'XPath %collection% to query';
        $retrieveObject = new \stdClass();
        $retrieveObject->retrieveReturn = $resultOfQuery;
        $soapClient = $this->geteXistDBMock(['connect', 'query', 'retrieve']);
        $soapClient->expects($this->once())
            ->method('connect')
            ->willReturn($this->soapReturn);
        $soapClient->expects($this->once())
            ->method('query')
            ->with(['sessionId' => 123, 'xpath' => 'XPath collection-name to query'])
            ->willReturn($this->soapReturn);
        $soapClient->expects($this->once())
            ->method('retrieve')
            ->willReturn($retrieveObject);

        $db = new eXistDB($soapClient, $this->username, $this->password, $this->collection);
        $result = $db->xquery($xquery, $withProlog);
        $this->assertEquals($expectedReturnValue, $result);
    }

    public function xqueryResultProvider()
    {
        return [
            [true, 'Result of query', '<?xml version="1.0" encoding="utf-8"?>'."\nResult of query\n"],
            [true, ['Result1', 'Result2'], '<?xml version="1.0" encoding="utf-8"?>'."\n".'Result1'."\n".'Result2'."\n"],
            [false, 'Result of query', "Result of query\n"],
        ];
    }

    private function geteXistDBMock(array $methods)
    {
        $soapClient = $this->getMockBuilder('\SoapClient')
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();

        return $soapClient;
    }
}
