<?php

namespace Unistra\UtilBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class TemplatesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('unistra:profetes:templates:fetch')
            ->setDescription('Récupère les pages sur le site Unistra afin d\'en faire des templates')
            #->addArgument()
            #->addOption()
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('La sortie de ma commande');
    }

}
