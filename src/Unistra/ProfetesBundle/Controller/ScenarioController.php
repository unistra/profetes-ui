<?php

namespace Unistra\ProfetesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ScenarioController extends Controller
{
    public function indexAction()
    {
        return $this->render('UnistraProfetesBundle:Scenario:index.html.twig');
    }
}


