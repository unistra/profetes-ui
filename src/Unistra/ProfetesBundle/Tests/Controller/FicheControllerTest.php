<?php

namespace Unistra\ProfetesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FicheControllerTest extends WebTestCase
{
    private $diplomeId;
    private $falseId;
    private $idWithCaps;

    public function setUp()
    {
        $prefix = 'fr-rne-0673021v';
        $code = 'mi203-231';
        $this->diplomeId = $prefix . '-pr-' . $code;
        $this->falseId = $prefix . '-pf-'. $code;
        $this->idWithCaps = $prefix . '-pr-' . strtoupper($code);
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s', $this->diplomeId));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('html:contains("Licence Mathématiques")')->count() > 0);
        $html = $client->getResponse()->getContent();

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s.html', $this->diplomeId));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('html:contains("Licence Mathématiques")')->count() > 0);

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s', $this->falseId));
        $this->assertTrue($client->getResponse()->isNotFound());

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s.html', $this->diplomeId));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('html:contains("Licence Mathématiques")')->count() > 0);
    }

    public function testUnexistantProgram()
    {
        // id a un format correct mais la fiche n'existe pas
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/diplome/fr-rne-0673021v-pr-ab123-456');
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testNoCapsInDiplomeId()
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', sprintf('/formations/diplome/%s', $this->idWithCaps));
        $this->assertTrue($client->getResponse()->isNotFound(), $this->idWithCaps . ' has CAPS response should be not found');
    }

    public function testNoMorePdf()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', sprintf('/formations/diplome/%s.pdf', $this->diplomeId));
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testFormatQueryParameterReturnsHttpGoneCode()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', sprintf('/formations/diplome/%s?format=something', $this->diplomeId));
        $this->assertEquals(Response::HTTP_GONE, $client->getResponse()->getStatusCode());
    }

    public function testXmlFormat()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', sprintf('/formations/diplome/%s.xml', $this->diplomeId));
        $this->assertTrue($client->getResponse()->headers->contains('content-type', 'text/xml; charset=UTF-8'));
        #À partir de symfony 2.4...
        #$crawler->registerNamespace('cdm', 'http://cdm-fr.fr/2006/CDM-frSchema');
        #$this->assertTrue($crawler->filter('cdm|CDM > cdm|program > cdm|programName')->count() == 1);
        $this->assertRegExp('/<cdm:programID>.*<\/cdm:programID>/', $client->getResponse()->getContent());
    }

    public function testRepertoire()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/diplome/');
        $this->assertTrue($client->getResponse()->isNotFound());
    }
}
