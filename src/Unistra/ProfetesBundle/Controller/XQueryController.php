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
die(var_dump($xml));
        return $this->render('UnistraProfetesBundle:XQuery:composante.html.twig', array(
            'formations' => $xml,
            'composante' => $id,
        ));
    }


    public function indexAction($id, $_format)
    {
        try {
            $formation = $this->get('exist_db')->getResource($id);
        } catch (\Exception $e) {
            if (404 == $e->getCode()) {
                throw $this->createNotFoundException();
            } else {
                throw new \Exception($e->getMessage());
            }
        }
        $html = $formation->transform(
            sprintf('%s/%s',
                $this->container->getParameter('unistra_profetes.xsl.path'),
                $this->container->getParameter('unistra_profetes.xsl.fiche')
            )
        );

        return $this->render(sprintf('UnistraProfetesBundle:Fiche:index.%s.twig', $_format), array(
            'formation' => $formation,
            'html'      => $html,
        ));
    }
}
