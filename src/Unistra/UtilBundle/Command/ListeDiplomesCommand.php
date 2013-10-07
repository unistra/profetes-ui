<?php

namespace Unistra\UtilBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Unistra\ProfetesBundle\ExistDB\ExistDB;


class ListeDiplomesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('unistra:profetes:diplomes:liste')
            ->setDescription('Liste complète des codes des formations dans la base, au format texte')
            ->addArgument('prefix', InputArgument::OPTIONAL, 'Préfixe à ajouter aux codes RNE pour faire une URL')
            #->addArgument()
            #->addOption()
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument('prefix')) {
            $xqueryParams = array('prefix' => $input->getArgument('prefix'));
        } else {
            $xqueryParams = array('prefix' => '');
        }
        $exist_db = $this->getContainer()->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            $this->getContainer()->get('kernel')->locateResource("@UnistraUtilBundle/Resources/xquery/diplomes.xquery"),
            $xqueryParams
        );
        $output->writeln($exist_db->getXQuery($xquery, array('withXmlProlog' => false, 'useCache' => false)));
    }

}
