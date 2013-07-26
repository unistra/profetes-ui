<?php

namespace Unistra\ProfetesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class FicheController extends Controller
{
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
