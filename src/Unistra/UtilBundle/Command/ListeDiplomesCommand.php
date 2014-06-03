<?php

namespace Unistra\UtilBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Unistra\Profetes\XQuery;

/**
 * Récupère la liste de tous les diplômes de la base eXist (profetes) et en fait
 * une liste d'URLs des fiches diplômes.
 *
 * Les diplômes sont sélectionnés à l'aide d'une requête XQuery.
 */
class ListeDiplomesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('unistra:profetes:diplomes:liste')
            ->setDescription('Liste complète des codes des formations dans la base, au format texte')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $router = $this->getContainer()->get('router');
        $router->getContext()->setHost('www.unistra.fr');
        $xqueryParams = array('prefix' => $router->generate('_unistra_profetes_repertoire_fiche', array(), true));
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/diplomes.xquery'));
        $xquery->setParameters($xqueryParams);

        $result = $this->getContainer()->get('profetes_repository')->query($xquery, false);
        $output->writeln($result);
    }

}
