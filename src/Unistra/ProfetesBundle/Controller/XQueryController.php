<?php

namespace Unistra\ProfetesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class XQueryController extends Controller
{

    public function composanteAction($id)
    {
        $formations = array();
        $exist_db = $this->get('exist_db');
        $xquery = $exist_db->loadXQueryFromFile(
            sprintf('%s/%s',
                $this->container->getParameter('unistra_profetes.xquery.path'),
                $this->container->getParameter('unistra_profetes.xquery.composante')),
            array('1' => $id));
        $xml = $exist_db->getXQuery($xquery);

        return $this->render('UnistraProfetesBundle:XQuery:composante.html.twig', array(
            'formations' => $xml,
            'composante' => $id,
        ));
    }

}
