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
        $xqueryPath = $this->container->getParameter('unistra_profetes.xquery.path');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $xqueryPath, 'liste-types-de-diplomes.xquery')
        );
        $exist_db->setCacheDir($xqueryPath . '/cache');
        $xml = $exist_db->getXQuery($xquery, array('withXmlProlog' => false));

        return new Response($xml);
    }


    public function disciplinesParTypeDeDiplomeAction($typeDeDiplome)
    {
        $exist_db = $this->get('exist_db');
        $xqueryPath = $this->container->getParameter('unistra_profetes.xquery.path');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $xqueryPath, 'liste-disciplines-par-type-de-diplome.xquery'),
            array('type-de-diplome' => $typeDeDiplome)
        );
        $exist_db->setCacheDir($xqueryPath . '/cache');
        $xml = $exist_db->getXQuery($xquery, array('withXmlProlog' => false));

        return new Response($xml);
    }

    public function parDisciplineEtTypeDeDiplomeAction($discipline, $typeDeDiplome)
    {
        $exist_db = $this->get('exist_db');
        $xqueryPath = $this->container->getParameter('unistra_profetes.xquery.path');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $xqueryPath, 'par-type-de-diplome-et-discipline.xquery'),
            array('type-de-diplome' => $typeDeDiplome, 'discipline' => $discipline));
        $exist_db->setCacheDir($xqueryPath . '/cache');
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
        $xqueryPath = $this->container->getParameter('unistra_profetes.xquery.path');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $xqueryPath, 'liste-disciplines.xquery')
        );
        $exist_db->setCacheDir($xqueryPath . '/cache');
        $xml = $exist_db->getXQuery($xquery, array('withXmlProlog' => false));

        return new Response($xml);
    }

    public function typesDiplomesParDisciplineAction($discipline)
    {
        $exist_db = $this->get('exist_db');
        $xqueryPath = $this->container->getParameter('unistra_profetes.xquery.path');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $xqueryPath, 'liste-types-de-diplomes-par-discipline.xquery'),
            array('discipline' => $discipline)
        );
        $exist_db->setCacheDir($xqueryPath . '/cache');
        $xml = $exist_db->getXQuery($xquery, array('withXmlProlog' => false));

        return new Response($xml);
    }

    public function listeObjectifsProfessionnelsAction()
    {
        $exist_db = $this->get('exist_db');
        $xqueryPath = $this->container->getParameter('unistra_profetes.xquery.path');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $xqueryPath, 'liste-objectifs-professionnels.xquery')
        );
        $exist_db->setCacheDir($xqueryPath . '/cache');
        $xml = $exist_db->getXQuery($xquery, array('withXmlProlog' => false));

        return new Response($xml);
    }

    public function typesDiplomesParObjProAction($objectifProfessionnel)
    {
        $exist_db = $this->get('exist_db');
        $xqueryPath = $this->container->getParameter('unistra_profetes.xquery.path');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $xqueryPath, 'liste-types-de-diplomes-par-objectif-professionnel.xquery'),
            array('objectif-professionnel' => $objectifProfessionnel)
        );
        $exist_db->setCacheDir($xqueryPath . '/cache');
        $xml = $exist_db->getXQuery($xquery, array('withXmlProlog' => false));

        return new Response($xml);
    }

    public function parObjectifProfessionnelEtTypeDeDiplomeAction($objectifProfessionnel, $typeDeDiplome)
    {
        $exist_db = $this->get('exist_db');
        $xqueryPath = $this->container->getParameter('unistra_profetes.xquery.path');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s', $xqueryPath, 'par-type-de-diplome-et-objectif-professionnel.xquery'),
            array('type-de-diplome' => $typeDeDiplome, 'objectif-professionnel' => $objectifProfessionnel));
        $exist_db->setCacheDir($xqueryPath . '/cache');
        $xml = $exist_db->getXQuery($xquery);

        return $this->render('UnistraProfetesBundle:Scenario:formations.html.twig', array(
            'formations'    => $xml,
            'xsl'           => $this->container->getParameter('unistra_profetes.xsl.path') . '/par-scenario.xsl',
            'path'          => $this->generateUrl('_unistra_profetes_repertoire_fiche'),
        ));
    }

}

