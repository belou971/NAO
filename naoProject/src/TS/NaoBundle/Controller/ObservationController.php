<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 23/05/18
 * Time: 17:36
 */
namespace TS\NaoBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use TS\NaoBundle\Component\ActionType;
use TS\NaoBundle\Component\DataManager;
use TS\NaoBundle\Component\RequestManager;
use TS\NaoBundle\Entity\Observation;
use TS\NaoBundle\Form\ObservationType;

class ObservationController extends Controller {

    public function readObservationAction(Request $request)
    {
        //step1 : tester la validité de la request
        $requestParams= array("method" => $request->getMethod(), "content"=>$request->getContent());
        $requestData = RequestManager::getInstance()->get(ActionType::READ_OBSERVATION, $requestParams);
        if(count($requestData["errors"]) > 0) {
            $response["errors"] = $requestData["errors"];
            return new Response(json_encode($response, JSON_UNESCAPED_UNICODE));
        }

        //step2 : //Récupérer l'observation associé à l'identifiant
        $em = $this->getDoctrine()->getManager();
        $parameters = $requestData["input_data"];

        $response = $this->getObservation($parameters, $em);
        $galeryDirectory = $this->getParameter('galery_relative_path');

        $result = $this->renderView('TSNaoBundle:Observation:observation_content.html.twig', array("galery" => $galeryDirectory, "observation"=>$response["data"][0], "infos"=>$response["data"][1], "modal" => true));
        return new  JsonResponse(array("html" => $result), JSON_UNESCAPED_UNICODE);
    }

    public function readOnlyAction($id) {
        $parameters["id"] = $id;
        $em = $this->getDoctrine()->getManager();

        $response = $this->getObservation($parameters, $em);

        $galeryDirectory = $this->getParameter('galery_relative_path');
        return $this->render('TSNaoBundle:Observation:observation.html.twig', array("galery" => $galeryDirectory,"observation"=>$response["data"][0], "infos"=>$response["data"][1], "modal" => false));
    }

    public function sidebarAction($max) {

        $em = $this->getDoctrine()->getManager();
        $parameters = array("nbObservations"=> $max);
        $json_response = DataManager::getInstance($em)->get(ActionType::LAST_OBSERVATIONS, $parameters);
        $response = json_decode($json_response, true);

        $galeryDirectory = $this->getParameter('galery_relative_path');
        return $this->render('TSNaoBundle:sections:sidebar.html.twig', array("lastObservations" => $response["data"], 'galery' => $galeryDirectory));
    }

    public function observationFormAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $observation = new Observation();

        $form = $this->createForm(ObservationType::class, $observation);

        if($request->isMethod('POST') )
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {

                $user = $this->getUser();
                $logged = !is_null($user);
                if($logged) {
                    $observation = $form->getData();
                    $observation->setUser($user);
                    $observation->updateStatusFromUserRole();

                    $images = $observation->getImages();
                    foreach ($images as $image) {
                        $image->setObservation($observation);
                    }

                    $em->persist($observation);
                    $em->flush();

                    return $this->redirectToRoute('ts_nao_dashboard');
                }

            }
        }

        return $this->render('TSNaoBundle:Observation:observationForm.html.twig', array('form' => $form->createView(), "modal" => false));
    }


    private function getObservation($parameters, $manager)
    {
        $json_response = DataManager::getInstance($manager)->get(ActionType::READ_OBSERVATION, $parameters);
        $response = json_decode($json_response, true);

        return $response;
    }
}