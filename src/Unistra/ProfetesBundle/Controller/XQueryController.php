<?php

namespace Unistra\ProfetesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class XQueryController extends Controller
{

    public function composanteAction($id)
    {
        $exist_db = $this->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s',
                $this->container->getParameter('unistra_profetes.xquery.path'),
                $this->container->getParameter('unistra_profetes.xquery.composante')),
            array('composante' => $exist_db->getOriginalId($id)));
        $xml = $exist_db->getXQuery($xquery);

        return $this->render('UnistraProfetesBundle:XQuery:composante.html.twig', array(
            'formations' => $xml,
            'composante' => $id,
            'xsl'        => $this->container->getParameter('unistra_profetes.xsl.path') . '/composante.xsl',
            'path'       => $this->generateUrl('_unistra_profetes_repertoire_fiche'),
        ));
    }


    public function parTypeDeDiplomeAction($typeDeDiplome)
    {
        $exist_db = $this->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s',
                $this->container->getParameter('unistra_profetes.xquery.path'),
                'par-type-de-diplome.xquery'),
            array('type-de-diplome' => $typeDeDiplome));
        $xml = $exist_db->getXQuery($xquery);

        return $this->render('UnistraProfetesBundle:XQuery:par-type-de-diplome.html.twig', array(
            'formations'    => $xml,
            'xsl'           => $this->container->getParameter('unistra_profetes.xsl.path') . '/par-type-de-diplome.xsl',
            'path'          => $this->generateUrl('_unistra_profetes_repertoire_fiche'),
            'typeDeDiplome' => $typeDeDiplome,
        ));
    }

    public function parSecteurActiviteAction($secteurActivite)
    {
        $exist_db = $this->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s',
                $this->container->getParameter('unistra_profetes.xquery.path'),
                'par-secteur-activite.xquery'),
            array('secteur-activite'    => $secteurActivite));
        $xml = $exist_db->getXQuery($xquery);

        return $this->render('UnistraProfetesBundle:XQuery:par-secteur-activite.html.twig', array(
            'formations'    => $xml,
            'xsl'           => $this->container->getParameter('unistra_profetes.xsl.path') . '/par-secteur-activite.xsl',
            'path'          => $this->generateUrl('_unistra_profetes_repertoire_fiche'),
            'secteurActivite'   => $secteurActivite,
        ));
    }

}
