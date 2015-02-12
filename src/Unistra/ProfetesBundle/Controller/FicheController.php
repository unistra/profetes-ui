<?php

/*
 * Copyright Université de Strasbourg (2015)
 *
 * Daniel Bessey <daniel.bessey@unistra.fr>
 *
 * This software is a computer program whose purpose is to diplay course information
 * extracted from a Profetes database on a website.
 *
 * See LICENSE for more details
 */

namespace Unistra\ProfetesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpFoundation\Request;
use Unistra\Profetes\ProgramId;

/**
 * @Cache(public=true, maxage=86400)
 */
class FicheController extends Controller
{
    public function indexAction(Request $request, $id, $_format)
    {
        if ($request->get('format')) {
            throw new GoneHttpException('Gone');
        }
        try {
            $repository = $this->get('profetes_repository');
            $programId = new ProgramId($id);
            $program = $repository->getProgram($programId);
        } catch (\Exception $e) {
            if (404 == $e->getCode()) {
                throw $this->createNotFoundException();
            } else {
                throw new \Exception($e->getMessage());
            }
        }

        return $this->render(sprintf('UnistraProfetesBundle:Fiche:index.%s.twig', $_format), array(
            'program'   => $program,
            'xsl'       => __DIR__.'/../Resources/xsl/fiche.xsl',
        ));
    }

    /**
     * Action correspondant à une route factice
     *
     * La route _unistra_profetes_repertoire_fiche ne devrait pas être appelée
     * Elle sert à "calculer" le chemin à préfixer à l'id des fiches diplômes
     * qui est passé en paramètre au XSLT pour fabriquer les URL des fiches.
     *
     * Au cas où elle était appelée, elle renverrait une erreur 404.
     */
    public function repertoireFicheAction()
    {
        throw $this->createNotFoundException();
    }
}
