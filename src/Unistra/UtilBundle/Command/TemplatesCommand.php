<?php

namespace Unistra\UtilBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Unistra\UtilBundle\Command\Template\TemplateFetcher;

/**
 * Commande à utiliser en ligne de commande pour récupérer une ou plusieurs
 * pages du site unistra et en faire des templates.
 *
 * La(les) page(s) à utiliser, les feuilles de style XSL à appliquer, les tests
 * à effectuer et les templates à produire sont définis dans le fichier donné en
 * argument (au format Yaml)
 */
class TemplatesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('unistra:profetes:templates:fetch')
            ->setDescription('Récupère les pages sur le site Unistra afin d\'en faire des templates')
            ->addArgument('config', InputArgument::OPTIONAL, 'Yaml config file', 'templates.yml')
            ->addOption('silent', null, InputOption::VALUE_NONE, 'Si activée, cette option rendra la commande silencieuse')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fetcher = new TemplateFetcher();
        $configValues = $this->parseConfigFile(
            $this->getContainer()->getParameter('kernel.root_dir').'/config/'.$input->getArgument('config')
        );

        $fetcher->setChecks($configValues['checks']['xpath']);
        $fetcher->setChecksumsFile($configValues['checksumsFile']);

        foreach ($configValues['templates'] as $template) {
            if (!$input->getOption('silent')) {
                $output->writeln('<info>'.$template['output'].'</info>');
            }
            $fetched = $fetcher->fetch($template['url'], $template['output'], $template['xsl']);
            if (!$input->getOption('silent')) {
                if ($fetched) {
                    $output->writeln('    ➜ <info>file updated</info>');
                } else {
                    $output->writeln('    ➜ File not updated');
                }
            }
        }
    }

    protected function parseConfigFile($configFile)
    {
        $parser = new Parser();
        $values = '';
        if (!is_file($configFile)) {
            throw new \Exception(sprintf('%s n\'est pas un fichier', $configFile));
        }
        if (!is_readable($configFile)) {
            throw new \Exception(sprintf('%s ne peut pas être lu', $configFile));
        }
        $fileContents = file_get_contents($configFile);
        $fileContents = str_replace('%kernel.root_dir%',
            $this->getContainer()->getParameter('kernel.root_dir'),
            $fileContents
        );
        try {
            $values = $parser->parse($fileContents);
        } catch (ParseException $e) {
            printf('Impossible de parser le fichier %s à la config %s', $configFile, $e->getMessage());
        }

        return $values;
    }
}
