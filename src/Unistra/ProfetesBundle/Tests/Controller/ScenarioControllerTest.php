<?php

namespace Unistra\ProfetesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScenarioControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/recherche-assistee/');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains('Recherche assistée', $crawler->filter("h1#page-title")->text());
    }

    public function testListeTypesDiplomesAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/recherche-assistee/types-diplomes');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains('<option value="1">Licence</option>', $client->getResponse()->getContent());
        $this->assertContains('<option value="2">Master</option>', $client->getResponse()->getContent());
    }

    /**
     * @dataProvider disciplinesParTypeDeDiplomeProvider
     */
    public function testDisciplinesParTypeDeDiplomeAction($typeDeDiplomeId, $expectedValue)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', sprintf('/formations/recherche-assistee/t/%d/disciplines', $typeDeDiplomeId));

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains($expectedValue, $client->getResponse()->getContent());
    }

    public function disciplinesParTypeDeDiplomeProvider()
    {
        return [
            [1, '<option value="Histoire">Histoire</option>'],
            [1, '<option value="Urbanisme, architecture">Urbanisme, architecture</option>'],
            [2, '<option value="Philosophie, éthique">Philosophie, éthique</option>'],
            [2, '<option value="Communication, information, journalisme">Communication, information, journalisme</option>'],
        ];
    }

    /**
     * @dataProvider parTypeDeDiplomeEtDisciplineProvider
     */
    public function testParTypeDeDiplomeEtDisciplineAction($typeDeDiplomeId, $discipline)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', sprintf(
                '/formations/recherche-assistee/t/%d/d/%s',
                $typeDeDiplomeId, $discipline
            ));
        $this->assertTrue($crawler->filter("ul li")->count() >= 1);
    }

    public function parTypeDeDiplomeEtDisciplineProvider()
    {
        return [
            [2, "Histoire"],
            [1, "Philosophie, éthique"],
        ];
    }

    public function testListeDisciplinesAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/recherche-assistee/disciplines');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains('<option value="Théologie">Théologie</option>', $client->getResponse()->getContent());
        $this->assertTrue($crawler->filter("option")->count() > 0);
    }

    /**
     * @dataProvider typesDiplomesParDisciplineProvider
     */
    public function testTypesDiplomesParDiscipline($disciplineId, $expectedValue)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', sprintf('/formations/recherche-assistee/d/%s/types-diplomes', $disciplineId));

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains($expectedValue, $client->getResponse()->getContent());
    }

    public function typesDiplomesParDisciplineProvider()
    {
        return [
            ['Théologie', '<option value="3">Doctorat</option>'],
            ['Mathématiques et informatique', '<option value="2">Master</option>', ],
            ['Sciences de la terre et de l\'univers, environnement', '<option value="1">Licence</option>', ]
        ];
    }

    public function testListeObjectifsProfessionnelsAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/recherche-assistee/objectifs-professionnels');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains('<option value="Commerce, vente, distribution">Commerce, vente, distribution</option>', $client->getResponse()->getContent());
        $this->assertTrue($crawler->filter("option")->count() > 0);
    }

    /**
     * @dataProvider typesDiplomesParObjProProvider
     */
    public function testTypesDiplomesParObjProAction($objectifId, $expectedValue)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', sprintf('/formations/recherche-assistee/o/%s/types-diplomes', $objectifId));

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertContains($expectedValue, $client->getResponse()->getContent());
    }

    public function typesDiplomesParObjProProvider()
    {
        return [
            ['Chimie, matériaux, plasturgie', '<option value="1">Licence</option>'],
            ['Droit', '<option value="3">Doctorat</option>', ],
        ];
    }

    /**
     * @dataProvider objectifProfessionnelEtTypeDeDiplomeProvider
     */
    public function testParObjectifProfessionnelEtTypeDeDiplomeAction($objectifProfessionnel, $typeDeDiplome)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', sprintf('/formations/recherche-assistee/o/%s/t/%s', $objectifProfessionnel, $typeDeDiplome));

        $this->assertTrue($crawler->filter('ul li')->count() > 0);
    }

    public function objectifProfessionnelEtTypeDeDiplomeProvider()
    {
        return [
            ['Enseignement, formation, éducation', 2],
            ['Politique et société', 2, ]
        ];
    }
}
