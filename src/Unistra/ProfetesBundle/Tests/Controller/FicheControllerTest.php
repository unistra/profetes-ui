<?php

namespace Unistra\ProfetesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FicheControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $diplome_id = 'fr-rne-0673021v-pr-mi203-231';
        $false_id   = 'fr-rne-0673021v-pf-mi203-231';

        $client = static::createClient();

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s', $diplome_id));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('html:contains("Licence Mathématiques")')->count() > 0);

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s', $false_id));
        $this->assertTrue($client->getResponse()->isNotFound());

        $crawler = $client->request('GET', sprintf('/formations/diplome/%s.html', $diplome_id));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($crawler->filter('html:contains("Licence Mathématiques")')->count() > 0);

    }
}
