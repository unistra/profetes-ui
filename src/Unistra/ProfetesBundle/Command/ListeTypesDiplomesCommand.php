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

namespace Unistra\ProfetesBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Unistra\Profetes\XQuery;

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
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/Command/liste-types-de-diplomes.xquery'));
        $results = $this->getContainer()->get('profetes_repository')->query($xquery, false);
        $output->writeln($results);
    }
}
