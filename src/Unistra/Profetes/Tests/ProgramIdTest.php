<?php

namespace Unistra\Profetes\Tests;

use Unistra\Profetes\ProgramId;

/**
 * @author daniel
 */
class ProgramIdTest extends \PHPUnit_Framework_TestCase
{
    public function testGetResourcePath()
    {
        $programId = new ProgramId('fr-rne-0673021v-pr-ab123-456');

        $this->assertEquals('%collection%/WSDiplomeCDM-0673021V-FRAN-AB123-456.xml', $programId->getResourcePath());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider getInvalidProgramIds
     */
    public function testInvalidProgramId($id)
    {
        $programId = new ProgramId($id);
    }

    public function getInvalidProgramIds()
    {
        return array(
            array('fr-rne-0673021v-ab123-456'),
            array('not valid'),
            array('FR-RNE-0673021V-PR-AB123-456'),
        );
    }
}
