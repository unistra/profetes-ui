<?php

namespace Unistra\ProfetesBundle\Test\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Unistra\ProfetesBundle\Command\ListeTypesDiplomesCommand;

class ListeTypesDiplomesCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new ListeTypesDiplomesCommand());

        $command = $application->find('unistra:profetes:diplomes:types');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();

        $this->assertNotEmpty($output);
        $this->assertContains('Licence', $output);
        $this->assertContains('Master', $output);
        $this->assertContains('Licence professionnelle', $output);
    }
}
