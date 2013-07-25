<?php

namespace Unistra\ProfetesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class FicheController extends Controller
{
    public function indexAction($id)
    {
        $formation = $this->get('exist_db')->getResource($id);
        $html = $formation->transform(__DIR__ . '/../Resources/xsl/fiche.xsl');
        return $this->render('UnistraProfetesBundle:Fiche:index.html.twig', array(
            'formation' => $formation,
            'html'      => $html,
        ));
    }
}
