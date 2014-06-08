<?php

namespace Unistra\Profetes\Tests;

use Unistra\Profetes\XQuery;

/**
 * @author daniel
 */
class XQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testAddParameter()
    {
        $xq = new XQuery('XQuery with {{{parameter}}} placeholder');
        $xq->addParameter('parameter', 'substituted');

        $this->assertEquals('XQuery with substituted placeholder', $xq->getXQuery());
    }

    public function testAddParameters()
    {
        $xq = new XQuery('XQuery with {{{param1}}}, {{{param2}}} and {{{param3}}}');
        $xq->addParameter('param1', 'value1');
        $xq->addParameters(array('param2' => 'value2', 'param3' => 'value3'));

        $this->assertEquals('XQuery with value1, value2 and value3', $xq->getXQuery());
    }

    public function testSetParameters()
    {
        $xq = new XQuery('XQuery with {{{param1}}} and {{{param2}}}');
        $xq->setParameters(array('param1' => 'value1', 'param2' => 'value2'));

        $this->assertEquals('XQuery with value1 and value2', $xq->getXQuery());
    }

    public function testParameterValuesAreEspaced()
    {
        $xq = new XQuery('XQuery with {{{parameter}}} placeholder');
        $xq->setParameters(array(
            'parameter' => 'I\'m reading "Harry Potter"!'
        ));
        $this->assertEquals('XQuery with I&#39;m reading &#34;Harry Potter&#34;! placeholder', $xq->getXQuery());
    }
}
