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
            #->addArgument()
            #->addOption()
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $router = $this->getContainer()->get('router');
        $router->getContext()->setHost('www.unistra.fr');
        $xqueryParams = array('prefix' => $router->generate('_unistra_profetes_repertoire_fiche', array(), true));
        $exist_db = $this->getContainer()->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            $this->getContainer()->get('kernel')->locateResource("@UnistraUtilBundle/Resources/xquery/diplomes.xquery"),
            $xqueryParams
        );
        $output->writeln($exist_db->getXQuery($xquery, array('withXmlProlog' => false, 'useCache' => false)));
    }

}
