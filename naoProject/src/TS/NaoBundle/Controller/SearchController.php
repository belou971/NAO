<?php

namespace TS\NaoBundle\Controller;

use Doctrine\DBAL\Exception\DatabaseObjectNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SearchController extends Controller
{
    public function indexAction()
    {
        return $this->render('@TSNao/Search/index.html.twig');
    }

    public function loadSpecimensAction() {
        $taxrefRepo = $this->getDoctrine()->getManager()->getRepository('TSNaoBundle:TAXREF');
        if(is_null($taxrefRepo)) {
           throw new NotFoundHttpException("Impossible d'accèder à la base de données");
        }

        $response = new JsonResponse($taxrefRepo->getSpecimenNames());
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $response;
    }
}
