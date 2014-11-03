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

    public function testPageFormation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s', $this->diplomeId));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('html:contains("Licence Mathématiques")')->count() > 0);
    }

    public function testPageFormationWithHtmlSuffix()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s.html', $this->diplomeId));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('html:contains("Licence Mathématiques")')->count() > 0);
    }

    public function testPageFormationInXmlFormat()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', sprintf('/formations/diplome/%s.xml', $this->diplomeId));
        $this->assertTrue($client->getResponse()->headers->contains('content-type', 'text/xml; charset=UTF-8'));
        $this->assertSame(1, $crawler->filterXPath('cdm:CDM/cdm:program/cdm:programName')->count());
    }

    public function testPageFormationWithInvalidId()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s', $this->falseId));
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testPageFormationForNonExistentProgram()
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


    public function testCanNotAccessRepertoire()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/diplome/');
        $this->assertTrue($client->getResponse()->isNotFound());
    }
}
