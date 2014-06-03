<?php

namespace Unistra\ProfetesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Unistra\Profetes\XQuery;

class XQueryController extends Controller
{

    /** /formations/composante/{id} */
    public function composanteAction($id)
    {
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/composante.xquery'));
        $xquery->addParameter('composante', strtoupper(str_replace('-', '_', $id)));
        $xml = $this->get('profetes_repository')->query($xquery);

        return $this->render('UnistraProfetesBundle:XQuery:composante.html.twig', array(
            'formations' => $xml,
            'composante' => $id,
            'xsl'        => __DIR__.'/../Resources/xsl/composante.xsl',
            'path'       => $this->generateUrl('_unistra_profetes_repertoire_fiche'),
        ));
    }

    /** /formations/type-diplome/{typeDeDiplome} */
    public function parTypeDeDiplomeAction($typeDeDiplome)
    {
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/par-type-de-diplome.xquery'));
        $xquery->addParameter('type-de-diplome', $typeDeDiplome);
        $xml = $this->get('profetes_repository')->query($xquery);

        return $this->render('UnistraProfetesBundle:XQuery:par-type-de-diplome.html.twig', array(
            'formations'    => $xml,
            'xsl'           => __DIR__.'/../Resources/xsl/par-type-de-diplome.xsl',
            'path'          => $this->generateUrl('_unistra_profetes_repertoire_fiche'),
            'typeDeDiplome' => $typeDeDiplome,
        ));
    }

    /** /formations/secteur-activite/{secteurActivite} */
    public function parSecteurActiviteAction($secteurActivite)
    {
        $xquery = new XQuery(file_get_contents(
            __DIR__.'/../Resources/xquery/par-secteur-activite.xquery'));
        $xquery->addParameter('secteur-activite', $secteurActivite);
        $xml = $this->get('profetes_repository')->query($xquery);

        return $this->render('UnistraProfetesBundle:XQuery:par-secteur-activite.html.twig', array(
            'formations'    => $xml,
            'xsl'           => __DIR__.'/../Resources/xsl/par-secteur-activite.xsl',
            'path'          => $this->generateUrl('_unistra_profetes_repertoire_fiche'),
            'secteurActivite'   => $secteurActivite,
        ));
    }

}
