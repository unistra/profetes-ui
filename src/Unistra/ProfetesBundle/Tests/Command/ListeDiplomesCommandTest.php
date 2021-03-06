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

namespace Unistra\ProfetesBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Unistra\ProfetesBundle\Command\ListeDiplomesCommand;

class ListeDiplomesCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new ListeDiplomesCommand());

        $command = $application->find('unistra:profetes:diplomes:liste');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();

        $this->assertStringStartsWith('http://www.unistra.fr/formations/diplome/fr-rne-06', $output);
        $this->assertRegExp(
            '#fr-rne-06\w+-pr-\w+-\w+#',
            $output
        );
    }
}
