<?php

namespace Unistra\ProfetesBundle\Tests\Controller;

use Unistra\ProfetesBundle\ExistDB\ExistDB;

class XQueryControllerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->wsdl = 'http://ofxml.u-strasbg.fr/exist/services/Query?wsdl';
        $this->xqueryFile = '/www/www.unistra.fr/src/Unistra/ProfetesBundle/Resources/xquery/composante.xquery';
        $this->existdb = new ExistDB($this->wsdl);
        $this->params = array(
            'composante' => 'FR_RNE_0673021V_OR_DRT',
        );
    }

    public function testXQueryBuilder()
    {
        $this->assertGreaterThan(5, strlen($this->existdb->loadXQueryFromFile($this->xqueryFile, $this->params)));
        $this->assertContains($this->params['composante'], $this->existdb->loadXQueryFromFile($this->xqueryFile, $this->params));
    }

    public function testXQueryExecute()
    {
        #$xquery = $this->existdb->loadXQueryFromFile($this->xqueryFile, $this->params);
        #$this->assertContains('schtroumpfs', $this->existdb->getXQuery($xquery));
    }

}
