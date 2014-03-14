<?php

namespace Unistra\ProfetesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ScenarioController extends Controller
{
    public function indexAction()
    {
        return $this->render('UnistraProfetesBundle:Scenario:index.html.twig');
    }

    public function listeTypesDiplomesAction()
    {
        $exist_db = $this->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $this->container->getParameter('unistra_profetes.xquery.path'), 'liste-types-de-diplomes.xquery')
        );
        $xml = $exist_db->getXQuery($xquery, array('withXmlProlog' => false));

        return new Response($xml);
    }

    public function disciplinesParTypeDeDiplomeAction($typeDeDiplome)
    {
        $exist_db = $this->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $this->container->getParameter('unistra_profetes.xquery.path'), 'liste-disciplines-par-type-de-diplome.xquery'),
            array('type-de-diplome' => $typeDeDiplome)
        );
        $xml = $exist_db->getXQuery($xquery, array('withXmlProlog' => false));

        return new Response($xml);
    }

    public function parDisciplineEtTypeDeDiplomeAction($discipline, $typeDeDiplome)
    {
        $exist_db = $this->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $this->container->getParameter('unistra_profetes.xquery.path'), 'par-type-de-diplome-et-discipline.xquery'),
            array('type-de-diplome' => $typeDeDiplome, 'discipline' => $discipline));
        $xml = $exist_db->getXQuery($xquery);

        return $this->render('UnistraProfetesBundle:Scenario:formations.html.twig', array(
            'formations'    => $xml,
            'xsl'           => $this->container->getParameter('unistra_profetes.xsl.path') . '/par-scenario.xsl',
            'path'          => $this->generateUrl('_unistra_profetes_repertoire_fiche'),
        ));
    }

    public function listeDisciplinesAction()
    {
        $exist_db = $this->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $this->container->getParameter('unistra_profetes.xquery.path'), 'liste-disciplines.xquery')
        );
        $xml = $exist_db->getXQuery($xquery, array('withXmlProlog' => false));

        return new Response($xml);
    }

    public function typesDiplomesParDisciplineAction($discipline)
    {
        $exist_db = $this->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $this->container->getParameter('unistra_profetes.xquery.path'), 'liste-types-de-diplomes-par-discipline.xquery'),
            array('discipline' => $discipline)
        );
        $xml = $exist_db->getXQuery($xquery, array('withXmlProlog' => false));

        return new Response($xml);
    }

    public function listeObjectifsProfessionnelsAction()
    {
        $exist_db = $this->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $this->container->getParameter('unistra_profetes.xquery.path'), 'liste-objectifs-professionnels.xquery')
        );
        $xml = $exist_db->getXQuery($xquery, array('withXmlProlog' => false));

        return new Response($xml);
    }

    public function typesDiplomesParObjProAction($objectifProfessionnel)
    {
        $exist_db = $this->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $this->container->getParameter('unistra_profetes.xquery.path'), 'liste-types-de-diplomes-par-objectif-professionnel.xquery'),
            array('objectif-professionnel' => $objectifProfessionnel)
        );
        $xml = $exist_db->getXQuery($xquery, array('withXmlProlog' => false));

        return new Response($xml);
    }

    public function parObjectifProfessionnelEtTypeDeDiplomeAction($objectifProfessionnel, $typeDeDiplome)
    {
        $exist_db = $this->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $this->container->getParameter('unistra_profetes.xquery.path'), 'par-type-de-diplome-et-objectif-professionnel.xquery'),
            array('type-de-diplome' => $typeDeDiplome, 'objectif-professionnel' => $objectifProfessionnel));
        $xml = $exist_db->getXQuery($xquery);

        return $this->render('UnistraProfetesBundle:Scenario:formations.html.twig', array(
            'formations'    => $xml,
            'xsl'           => $this->container->getParameter('unistra_profetes.xsl.path') . '/par-scenario.xsl',
            'path'          => $this->generateUrl('_unistra_profetes_repertoire_fiche'),
        ));
    }

}
