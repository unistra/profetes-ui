<?php

namespace Unistra\ProfetesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class FicheController extends Controller
{
    public function indexAction($id)
    {
        $formation = $this->get('exist_db')->getResource($id);
        return $this->render('UnistraProfetesBundle:Fiche:index.html.twig', array(
            'formation' => $formation,
        ));
    }
}
