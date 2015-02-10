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
            array('fr-rne-0673021v-pr-AB123-456'),
            array('fr-rne-0673021v-pr-123-BA654'),
        );
    }

    public function testFromBestGuessReturnsInstanceOfProgramId()
    {
        $programId = ProgramId::fromBestGuess('ab123-456');
        $this->assertInstanceOf('Unistra\Profetes\ProgramId', $programId, 'is not an instance of Unistra\Profetes\ProgramId');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 404
     */
    public function testInvalidArgumentCanNotBeGuessed()
    {
        $programId = ProgramId::fromBestGuess('this can not be guessed');
    }

    /**
     * @dataProvider getBestGuessIds
     */
    public function testBestGuessWithWeirdCharacters($given, $expected)
    {
        $programId = ProgramId::fromBestGuess($given);
        $this->assertEquals($expected, $programId->getId());
    }

    public function getBestGuessIds()
    {
        return [
            ['ab123.456', 'fr-rne-0673021v-pr-ab123-456'],
            ['AB123.456', 'fr-rne-0673021v-pr-ab123-456'],
            ['123/76', 'fr-rne-0673021v-pr-123-76'],
            ['xy124_9', 'fr-rne-0673021v-pr-xy124-9'],
            ['FR_RNE_0673021V_PR_AB13_456', 'fr-rne-0673021v-pr-ab13-456'],
            ['fr_rne_0673021v_Pr-ab123-456', 'fr-rne-0673021v-pr-ab123-456'],
            ['FR_RNE_0673021v/pr-ab123.456', 'fr-rne-0673021v-pr-ab123-456'],
            ['fr-rne-0673021v-pr-ab123-456', 'fr-rne-0673021v-pr-ab123-456'],
        ];
    }
}
