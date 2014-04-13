<?php

namespace Unistra\UtilBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Récupère la liste des types de diplômes de la base eXist (profetes)
 *
 * Les types de diplômes sont sélectionnés à l'aide d'une requête XQuery.
 */
class ListeTypesDiplomesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('unistra:profetes:diplomes:types')
            ->setDescription('Liste des types de diplômes présents dans la base')
            #->addArgument()
            #->addOption()
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $exist_db = $this->getContainer()->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            $this->getContainer()->get('kernel')->locateResource("@UnistraUtilBundle/Resources/xquery/types-de-diplomes.xquery")
        );
        $output->writeln($exist_db->getXQuery($xquery, array('withXmlProlog' => false, 'useCache' => false)));
    }

}
