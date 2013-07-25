<?php

namespace Unistra\ProfetesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UnistraProfetesBundle:Default:index.html.twig');
    }
}
