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

        $result = $this->renderView('TSNaoBundle:Observation:observation_content.html.twig', array("observation"=>$response["data"][0], "infos"=>$response["data"][1], "modal" => true));
        return new  JsonResponse(array("html" => $result), JSON_UNESCAPED_UNICODE);
    }

    public function readOnlyAction($id) {
        $parameters["id"] = $id;
        $em = $this->getDoctrine()->getManager();

        $response = $this->getObservation($parameters, $em);

        return $this->render('TSNaoBundle:Observation:observation.html.twig', array("observation"=>$response["data"][0], "infos"=>$response["data"][1], "modal" => false));
    }

    public function sidebarAction($max) {

        $em = $this->getDoctrine()->getManager();
        $parameters = array("nbObservations"=> $max);
        $json_response = DataManager::getInstance($em)->get(ActionType::LAST_OBSERVATIONS, $parameters);
        $response = json_decode($json_response, true);

        return $this->render('TSNaoBundle:sections:sidebar.html.twig', array("lastObservations" => $response["data"]));
    }

    private function getObservation($parameters, $manager)
    {
        $json_response = DataManager::getInstance($manager)->get(ActionType::READ_OBSERVATION, $parameters);
        $response = json_decode($json_response, true);

        return $response;
    }
}