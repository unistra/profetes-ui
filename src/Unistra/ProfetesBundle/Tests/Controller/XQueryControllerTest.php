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

namespace Unistra\ProfetesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class XQueryControllerTest extends WebTestCase
{
    public function testComposante()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/composante/fr-rne-0673021v-or-drt');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('h3')->count());
        $this->assertGreaterThan(0, $crawler->filter('li')->count());

        $client->request('GET', '/formations/composante/FR_RNE_0673021V_OR_DRT');
        $this->assertTrue($client->getResponse()->isNotFound());

        $client->request('GET', '/formations/composante/droit');
        $this->assertTrue($client->getResponse()->isNotFound());

        $crawler = $client->request('GET', '/formations/composante/fr-rne-0673021v-or-abc');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertCount(0, $crawler->filter('li'));
    }

    public function testTypeDeDiplome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/formations/type-diplome/Licence');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('h1#page-title')->count());
        $this->assertEquals(1, $crawler->filter('div#content-mapping div h2')->count());
        $this->assertGreaterThan(0, $crawler->filter('div#content-mapping div ul li')->count());
    }

    public function testTypeDeDiplomeAccentue()
    {
        $client = static::createClient();

        $client->request('GET', '/formations/type-diplome/Diplôme d\'université');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testSecteurActivite()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/formations/secteur-activite/Chimie,%20matériaux,%20plasturgie');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('h1#page-title')->count());
        $this->assertEquals('Formations par secteur d\'activité', $crawler->filter('h1#page-title')->text());
        $this->assertEquals('Chimie, matériaux, plasturgie', $crawler->filter('div#content-mapping div h2')->text());

        $link = $crawler->filter('div#content-mapping div ul li a')->eq(1)->link();
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('nav#menu-onglets')->count());
    }

    public function testSecteurActiviteAccentue()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/secteur-activite/Technologies%20de%20l%27information,%20t%C3%A9l%C3%A9communications,%20r%C3%A9seaux');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
