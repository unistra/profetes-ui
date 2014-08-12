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
        $id = $this->normalizeId($input->getArgument('id'));
        $programId = new ProgramId($id);
        $cache = $this->getContainer()->get('unistra.profetes.cache.program_cache');
        $info = $cache->info($programId->getResourcePath());

        $output->writeln('<info>' . $id . '</info>');
        $output->writeln('  File : ' . $info['filename']);
        $output->writeln('  Date : ' . $info['updated']->setTimeZone($tz)->format('d/m/Y H:i:s P'));
        $output->writeln('  Size : ' . $info['size'] . ' o');
        $output->writeln('');
    }

    private function normalizeId($id)
    {
        $id = strtolower($id);
        $id = str_replace(['_', '.', '/'], '-', $id);

        if (preg_match("/^[a-z0-9]{3,}-\d+$/", $id)) {
            $id = 'fr-rne-0673021v-pr-' . $id;
        }

        return $id;
    }
}