<?php

namespace Unistra\ProfetesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class XQueryController extends Controller
{

    public function composantesAction()
    {
        $xml = file_get_contents($this->container->getParameter('unistra_profetes.xquery.path') . '/composantes.xml');
        $xsl = $this->container->getParameter('unistra_profetes.xsl.path') . '/composantes.xsl';
        return $this->render('UnistraProfetesBundle:XQuery:composantes.html.twig', array(
            'xml'   => $xml,
            'xsl'   => $xsl,
            'path'  => $this->generateUrl('_unistra_profetes_repertoire_composante'),
        ));
    }

    public function composanteAction($id)
    {
        $exist_db = $this->get('exist_db');
        $xqueryPath = $this->container->getParameter('unistra_profetes.xquery.path');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s',
                $xqueryPath,
                $this->container->getParameter('unistra_profetes.xquery.composante')),
            array('composante' => $exist_db->getOriginalId($id)));
        $exist_db->setCacheDir($xqueryPath . '/cache');
        $xml = $exist_db->getXQuery($xquery);

        return $this->render('UnistraProfetesBundle:XQuery:composante.html.twig', array(
            'formations' => $xml,
            'composante' => $id,
            'xsl'        => $this->container->getParameter('unistra_profetes.xsl.path') . '/composante.xsl',
            'path'       => $this->generateUrl('_unistra_profetes_repertoire_fiche'),
        ));
    }

}
