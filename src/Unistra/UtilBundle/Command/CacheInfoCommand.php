<?php

namespace Unistra\UtilBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Unistra\Profetes\ProgramId;

class CacheInfoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('unistra:profetes:diplome:cache-info')
            ->setDescription('Récupération des informations de cache pour un diplome')
            ->addArgument('id', InputArgument::REQUIRED, 'L\'id du diplôme')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tz = new \DateTimeZone('Europe/Paris');
        $id = $input->getArgument('id');
        $programId = ProgramId::fromBestGuess($id);
        $cache = $this->getContainer()->get('unistra.profetes.cache.program_cache');
        $info = $cache->info($programId->getResourcePath());

        $output->writeln(sprintf('<info>%s</info>', $programId->getId()));
        $output->writeln('  <options=bold>File</options=bold> : '.$info['filename']);
        $output->writeln('  <options=bold>Date</options=bold> : '.$info['updated']->setTimeZone($tz)->format('d/m/Y H:i:s P'));
        $output->writeln('  <options=bold>Size</options=bold> : '.$info['size'].' o');
        $output->writeln('');
    }
}
