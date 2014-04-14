<?php

namespace Unistra\ProfetesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FicheControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->diplome_id = 'fr-rne-0673021v-pr-mi203-231';
        $this->false_id = 'fr-rne-0673021v-pf-mi203-231';
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s', $this->diplome_id));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('html:contains("Licence Mathématiques")')->count() > 0);
        $html = $client->getResponse()->getContent();

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s.html', $this->diplome_id));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('html:contains("Licence Mathématiques")')->count() > 0);

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s', $this->false_id));
        $this->assertTrue($client->getResponse()->isNotFound());

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s.html', $this->diplome_id));
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

    public function testNoMorePdf() {
        $client = static::createClient();
        $crawler = $client->request('GET', sprintf('/formations/diplome/%s.pdf', $this->diplome_id));
        $this->assertTrue($client->getResponse()->isNotFound());

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s?format=pdf', $this->diplome_id));
        $this->assertEquals(410, $client->getResponse()->getStatusCode());
    }

    public function testXmlFormat() {
        $client = static::createClient();
        $crawler = $client->request('GET', sprintf('/formations/diplome/%s.xml', $this->diplome_id));
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
