<?php

namespace TS\NaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TS\NaoBundle\Component\ActionType;
use TS\NaoBundle\Component\DataManager;
use TS\NaoBundle\Component\RequestManager;
use TS\NaoBundle\Enum\ProfilEnum;

class SearchController extends Controller
{
    public function indexAction()
    {
        return $this->render('@TSNao/Search/index.html.twig');
    }

    public function loadSpecimensAction() {
        $em = $this->getDoctrine()->getManager();

        $data = DataManager::getInstance($em)->get(ActionType::LOAD_SPECIMENS, array());
        $response = json_decode($data, true);

        $jsonResponse = new JsonResponse($response["data"]);
        $jsonResponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $jsonResponse;
    }

    public function findByNameAction(Request $request) {

        //step1 : tester la validité de la request
        $requestParams= array("method" => $request->getMethod(), "content"=>$request->getContent());
        $requestData = RequestManager::getInstance()->get(ActionType::SEARCH_SPECIMEN_BY_NAME, $requestParams);
        if(count($requestData["errors"]) > 0) {
            $response["errors"] = $requestData["errors"];
            return new Response(json_encode($response, JSON_UNESCAPED_UNICODE));
        }

        //step2 : //Récupérer les observations ayant le nom du specimen
        $em = $this->getDoctrine()->getManager();
        $specimenToFind = $requestData["input_data"]["specimen_name"];
        $parameters = array("name" => $specimenToFind);
        $response = DataManager::getInstance($em)->get(ActionType::SEARCH_SPECIMEN_BY_NAME, $parameters);

        return new Response($response);
    }

    public function findByCityAction(Request $request) {

        //step1 : tester la validité de la request
        $requestParams = array("method" => $request->getMethod(), "content" =>$request->getContent());
        $requestData = RequestManager::getInstance()->get(ActionType::SEARCH_SPECIMEN_BY_CITY, $requestParams);
        if(count($requestData["errors"]) > 0) {
            $response["errors"] = $requestData["errors"];
            return new Response(json_encode($response, JSON_UNESCAPED_UNICODE));
        }

        //step2 : //Récupérer les observations ayant le nom du specimen
        $em = $this->getDoctrine()->getManager();
        $data = $requestData["input_data"]['geo_properties']["features"][0];
        $parameters = array("city_properties" => $data["properties"], "city_geometry" => $data["geometry"]);
        $response   = DataManager::getInstance($em)->get(ActionType::SEARCH_SPECIMEN_BY_CITY, $parameters);

        return new Response($response);
    }

    public function findByCoordAction(Request $request) {

        //step1 : tester la validité de la request
        $requestParams= array("method" => $request->getMethod(), "content"=>$request->getContent());
        $requestData = RequestManager::getInstance()->get(ActionType::SEARCH_SPECIMEN_BY_COORD, $requestParams);
        if(count($requestData["errors"]) > 0) {
            $response["errors"] = $requestData["errors"];
            return new Response(json_encode($response, JSON_UNESCAPED_UNICODE));
        }

        //step2 : //Récupérer les observations a partir de coordonnées gps
        $em = $this->getDoctrine()->getManager();
        $parameters = $requestData["input_data"];
        $response   = DataManager::getInstance($em)->get(ActionType::SEARCH_SPECIMEN_BY_COORD, $parameters);

        return new Response($response);
    }

    public function getZoomMaxAction() {

        $user = $this->getUser();
        $logged = !is_null($user);
        $parameters = array("logged"=>$logged, "profil"=>"");
        if($logged) {
            $roles  = $user->getRoles();
            if(in_array(ProfilEnum::ADMIN, $roles)){
                $parameters["profil"] = ProfilEnum::ADMIN;
            } else if(in_array(ProfilEnum::NATURALIST, $roles)) {
                $parameters["profil"] = ProfilEnum::NATURALIST;
            } else {
                $parameters["profil"] = ProfilEnum::BIRD_FANCIER;
            }
        }

        $em = $this->getDoctrine()->getManager();
        $response = DataManager::getInstance($em)->get(ActionType::ZOOM_MAX, $parameters);

        return new Response($response);
    }
}
