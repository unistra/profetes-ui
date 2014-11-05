<?php

namespace Unistra\ProfetesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Unistra\Profetes\XQuery;

/**
 * @Cache(public=true, maxage=86400)
 */
class ScenarioController extends Controller
{
    public function indexAction()
    {
        return $this->render('UnistraProfetesBundle:Scenario:index.html.twig');
    }

    /** /formations/recherche-assistee/types-diplomes */
    public function listeTypesDiplomesAction()
    {
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/liste-types-de-diplomes.xquery'));
        $xml = $this->get('profetes_repository')->query($xquery, false);

        return new Response($xml);
    }

    /** /formations/recherche-assistee/t/{typeDeDiplome}/disciplines */
    public function disciplinesParTypeDeDiplomeAction($typeDeDiplome)
    {
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/liste-disciplines-par-type-de-diplome.xquery'));
        $xquery->addParameter('type-de-diplome', $typeDeDiplome);
        $xml = $this->get('profetes_repository')->query($xquery, false);

        return new Response($xml);
    }

    /** /formations/recherche-assistee/t/{typeDeDiplome}/d/{discipline} */
    public function parDisciplineEtTypeDeDiplomeAction($discipline, $typeDeDiplome)
    {
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/par-type-de-diplome-et-discipline.xquery'));
        $xquery->setParameters(array(
            'type-de-diplome' => $typeDeDiplome,
            'discipline' => $discipline));
        $xml = $this->get('profetes_repository')->query($xquery);

        return $this->render('UnistraProfetesBundle:Scenario:formations.html.twig', array(
            'formations'    => $xml,
            'xsl'           => __DIR__.'/../Resources/xsl/par-scenario.xsl',
            'path'          => $this->generateUrl('_unistra_profetes_repertoire_fiche'),
        ));
    }

    /** /formations/recherche-assistee/disciplines */
    public function listeDisciplinesAction()
    {
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/liste-disciplines.xquery'));
        $xml = $this->get('profetes_repository')->query($xquery, false);

        return new Response($xml);
    }

    /** /formations/recherche-assistee/d/{discipline}/types-diplomes */
    public function typesDiplomesParDisciplineAction($discipline)
    {
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/liste-types-de-diplomes-par-discipline.xquery'));
        $xquery->addParameter('discipline', $discipline);
        $xml = $this->get('profetes_repository')->query($xquery, false);

        return new Response($xml);
    }

    /** /formations/recherche-assistee/objectifs-professionnels */
    public function listeObjectifsProfessionnelsAction()
    {
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/liste-objectifs-professionnels.xquery'));
        $xml = $this->get('profetes_repository')->query($xquery, false);

        return new Response($xml);
    }

    /** /formations/recherche-assistee/o/{objectifProfessionnel}/types-diplomes */
    public function typesDiplomesParObjProAction($objectifProfessionnel)
    {
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/liste-types-de-diplomes-par-objectif-professionnel.xquery'));
        $xquery->addParameter('objectif-professionnel', $objectifProfessionnel);
        $xml = $this->get('profetes_repository')->query($xquery, false);

        return new Response($xml);
    }

    /** /formations/recherche-assistee/o/{objectifProfessionnel}/t/{typeDeDiplome} */
    public function parObjectifProfessionnelEtTypeDeDiplomeAction($objectifProfessionnel, $typeDeDiplome)
    {
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/par-type-de-diplome-et-objectif-professionnel.xquery'));
        $xquery->setParameters(array(
            'type-de-diplome' => $typeDeDiplome,
            'objectif-professionnel' => $objectifProfessionnel));
        $xml = $this->get('profetes_repository')->query($xquery);

        return $this->render('UnistraProfetesBundle:Scenario:formations.html.twig', array(
            'formations'    => $xml,
            'xsl'           => __DIR__.'/../Resources/xsl/par-scenario.xsl',
            'path'          => $this->generateUrl('_unistra_profetes_repertoire_fiche'),
        ));
    }

}
