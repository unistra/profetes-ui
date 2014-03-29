<?php

namespace Unistra\ProfetesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/formations/');
        $this->assertTrue($client->getResponse() instanceof RedirectResponse);
        $this->assertRegExp('/www\.unistra\.fr/',
            $client->getResponse()->headers->get('location'));
    }
}
