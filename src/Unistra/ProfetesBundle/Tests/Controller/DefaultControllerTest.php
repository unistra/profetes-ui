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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @group   redirections
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/formations/');
        $response = $client->getResponse();
        $this->assertTrue($response instanceof RedirectResponse);
        $this->assertNotEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(Response::HTTP_MOVED_PERMANENTLY, $response->getStatusCode());
        $this->assertRegExp(
            '/www\.unistra\.fr\/index\.php\?id=etudes$/',
            $response->headers->get('location')
        );
    }
}
