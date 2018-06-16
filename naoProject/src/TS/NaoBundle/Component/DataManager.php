<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 12/05/18
 * Time: 18:55
 */
namespace TS\NaoBundle\Component;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use TS\NaoBundle\Enum\ProfilEnum;
use TS\NaoBundle\Enum\StateEnum;

class DataManager
{
    private static $instance;
    private $em;

    private function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public static function getInstance(EntityManager $entityManager) {

        if(is_null(self::$instance)) {
            self::$instance = new DataManager($entityManager) ;
        }

        return self::$instance;
    }

    public function get($action, $parameters, $json_format = true) {
        //initialize the output of the function
        $response = array("errors" => array(), "data" => array() );

        if(!ActionType::exists($action)) {
            $response["errors"] = array($action." is not an known action");
        }
        else if(!is_array($parameters)) {
            $response["errors"] = array("parameters type error. Parameters list must be an array");
        }
        else {
            $response = $this->doAction($action, $parameters);
        }

        if($json_format) {
            return json_encode($response, JSON_UNESCAPED_UNICODE);
        }

        return $response;
    }

    private function doAction($action, $parameters) {
        $response = array("errors" => array(), "data" => array() );

        if(ActionType::LOAD_SPECIMENS === $action) {
            $response = $this->loadSpecimens();
        }

        if(ActionType::SEARCH_SPECIMEN_BY_NAME === $action) {
            $response = $this->findSpecimenByNameAction($parameters);
        }

        if(ActionType::SEARCH_SPECIMEN_BY_CITY === $action) {
            $response = $this->findSpecimenByCityAction($parameters);
        }

        if(ActionType::SEARCH_SPECIMEN_BY_COORD === $action) {
            $response = $this->findSpecimenByCoordAction($parameters);
        }

        if (ActionType::ZOOM_MAX === $action) {
            $response = $this->getZoomMax($parameters);
        }

        if(ActionType::LAST_OBSERVATIONS === $action) {
            $response = $this->getLastObservations($parameters);
        }

        if (ActionType::READ_OBSERVATION === $action) {
            $response = $this->getObservation($parameters);
        }

        if (ActionType::VALIDATED_OBSERVATIONS === $action) {
            $response = $this->getValidatedObservations($parameters);
        }

        if (ActionType::REJECTED_OBSERVATIONS === $action) {
            $response = $this->getRejectedObservations($parameters);
        }

        if (ActionType::PENDING_OBSERVATIONS === $action) {
            $response = $this->getPendingObservations($parameters);
        }

        if (ActionType::UPDATE_OBSERVATION_STATUS === $action) {
            $response = $this->updateObservationStatus($parameters);
        }

        return $response;
    }


    private function loadSpecimens() {
        $response = array("errors" => array(), "data" => array(), "messages" => array() );

        $taxrefRepo = $this->em->getRepository('TSNaoBundle:TAXREF');
        if(is_null($taxrefRepo)) {
            $response["errors"] = array("Impossible d'accèder à la base de données");
        }

        try {
            $response["data"] = $taxrefRepo->getSpecimenNames();
        }
        catch (ORMException $e) {
            $response["errors"] = array($e->getMessage());
        }

        return $response;
    }

    private function findSpecimenByNameAction($parameters) {
        $response = array("errors" => array(), "data" => array(), "messages" => array());

        //Check whether there is a parameters
        if(!array_key_exists("name", $parameters)) {
            $response["errors"] = array("key name not found in parameters");
        }
        else {
            //Find in database all the observations with specimen name identified by the given name in parameter
            $observationRepo = $this->em->getRepository('TSNaoBundle:Observation');
            if(is_null($observationRepo)) {
                $response["errors"] = array("Impossible d'accèder à la table des observations");
            }

            try {
                $listObservations = $observationRepo->findByName($parameters["name"]);
                $nbObservations = count($listObservations);

                if(0 === $nbObservations) {
                    $response["messages"] = array("Aucune observation trouvé pour l'espèce " .$parameters["name"]);
                }
                else {
                    $response["messages"] = array($nbObservations." observation(s) trouvée(s) pour l'espèce " .$parameters["name"]);
                }
                $response["data"] = $listObservations;
            }
            catch (ORMException $e) {
                $response["errors"] = array($e->getMessage());
            }
            catch (\Exception $e) {
                $response["errors"] = array($e->getMessage());
            }
        }

        return $response;
    }

    private function findSpecimenByCityAction($parameters) {
        $response = array("errors" => array(), "data" => array(), "contour" => array(), "messages" => array());

        foreach($parameters as $param) {
            if(array_key_exists("nom", $param)){
                if(false === isset($param["nom"])) {
                    $response = array("Ville non identifié");
                    return $response;
                }
            }

            if(array_key_exists("coordinates", $param)) {
                if (false === is_array($param["coordinates"])) {
                    if (count($param["coordinates"][0][0]) <= 0) {
                        $response = array("Les coordonnées de la ville ne sont pas valides");
                        return $response;
                    }
                }
            }
        }

        //Find in database all the observations with location(lat, lon) is contained in geometry in parameter
        $observationRepo = $this->em->getRepository('TSNaoBundle:Observation');
        if(is_null($observationRepo)) {
            $response["errors"] = array("Impossible d'accèder à la table des observations");
        }

        try {
            $geometry = $parameters["city_geometry"]["coordinates"][0];
            $response["contour"] = $geometry;
            //get min (lon, lat) and max (lon, lat)
            $min_lat_lon = $this->getMinLatLon($geometry);
            $max_lat_lon = $this->getMaxLatLon($geometry);

            $listObservations = $observationRepo->findByCity($min_lat_lon, $max_lat_lon);
            $nbObservations = count($listObservations);

            if(0 === $nbObservations) {
                $response["messages"] = array("Aucune observation trouvé pour la ville " .$parameters["city_properties"]["nom"]);
            }
            else {
                $response["messages"] = array($nbObservations." observation(s) trouvée(s) pour la ville " .$parameters["city_properties"]["nom"]);
            }
            $response["data"] = $listObservations;
        }
        catch (ORMException $e) {
            $response["errors"] = array($e->getMessage());
        }
        catch (\Exception $e) {
            $response["errors"] = array($e->getMessage());
        }

        return $response;
    }

    private function findSpecimenByCoordAction($parameters) {
        $response = array("errors" => array(), "data" => array(), "contour" => array(), "messages" => array());

        //Find in database all the observations with location(lat, lon) is contained in geometry in parameter
        $observationRepo = $this->em->getRepository('TSNaoBundle:Observation');
        if(is_null($observationRepo)) {
            $response["errors"] = array("Impossible d'accèder à la table des observations");
        }

        try {
            $geometry = $parameters["geometry"]["coordinates"][0];
            $response["contour"] = $geometry;
            //get min (lon, lat) and max (lon, lat)
            $min_lat_lon = $this->getMinLatLon($geometry);
            $max_lat_lon = $this->getMaxLatLon($geometry);

            $listObservations = $observationRepo->findByCity($min_lat_lon, $max_lat_lon);
            $nbObservations = count($listObservations);

            if(0 === $nbObservations) {
                $response["messages"] = array("Aucune observation trouvé autour de la position donnée");
            }
            else {
                $response["messages"] = array($nbObservations." observation(s) trouvée(s) autour de la position donnée");
            }
            $response["data"] = $listObservations;
        }
        catch (ORMException $e) {
            $response["errors"] = array($e->getMessage());
        }
        catch (\Exception $e) {
            $response["errors"] = array($e->getMessage());
        }

        return $response;
    }

    private function getMaxLatLon($geometry) {

        $firstLoop = true;
        $lat = $lon = 0.0;
        foreach ($geometry as $coordinate) {
            if($firstLoop) {
                $lat = $coordinate[0];
                $lon = $coordinate[1];
                $firstLoop = false;
                continue;
            }

            if($lat<= $coordinate[0])
            {
               $lat = $coordinate[0];
            }
            if($lon<= $coordinate[1])
            {
                $lon = $coordinate[1];
            }
        }

        return array($lat, $lon);
    }

    private function getMinLatLon($geometry) {

        $firstLoop = true;
        $lat = $lon = 0.0;
        foreach ($geometry as $coordinate) {
            if($firstLoop) {
                $lat = $coordinate[0];
                $lon = $coordinate[1];
                $firstLoop = false;
                continue;
            }

            if($lat >= $coordinate[0])
            {
                $lat = $coordinate[0];
            }
            if($lon >= $coordinate[1])
            {
                $lon = $coordinate[1];
            }
        }

        return array($lat, $lon);
    }

    private function getZoomMax($parameters) {
        $response = array("errors" => array(), "data" => array(), "messages" => array() );

        $zoomMaxSetting = array();
        $zoomMaxSetting[ProfilEnum::BIRD_FANCIER] = 12;
        $zoomMaxSetting[ProfilEnum::NATURALIST]   = 15;
        $zoomMaxSetting[ProfilEnum::ADMIN]        = 18;

        if (!array_key_exists("profil", $parameters)) {
            $response["errors"] = array("Le paramètre 'profil' est introuvable");
        }

        if(true === $parameters['logged'] && true === $parameters['active']) {
            $profil = $parameters["profil"];
            $response["data"] = array("zoomMax" => $zoomMaxSetting[$profil]);
        }
        else {
            $response["data"] = array("zoomMax" => 10);
        }

        return $response;
    }

    private function getLastObservations($parameters) {
        $response = array("errors" => array(), "data" => array(), "messages" => array() );

        if(!array_key_exists("nbObservations", $parameters)) {
            $response["errors"] = array("le paramètre attendu [nbObservations] est absent");
        }

        //Find in database the last observations with a number of observations
        // in the result limited by the number gave in parameter
        $observationRepo = $this->em->getRepository('TSNaoBundle:Observation');
        if(is_null($observationRepo)) {
            $response["errors"] = array("Impossible d'accèder à la table des observations");
        }

        try {
            $listObservations = $observationRepo->findLastObservations($parameters["nbObservations"]);
            $nbObservations = count($listObservations);

            if(0 === $nbObservations) {
                $response["messages"] = array("Aucune observation enregistrée dans notre site");
            }

            $response["data"] = $listObservations;
        }
        catch (ORMException $e) {
            $response["errors"] = array($e->getMessage());
        }
        catch (\Exception $e) {
            $response["errors"] = array($e->getMessage());
        }

        return $response;
    }

    private function getObservation($parameters)
    {
        $response = array("errors" => array(), "data" => array(), "messages" => array() );

        if(!array_key_exists("id", $parameters)) {
            $response["errors"] = array("Indiquer un identifiant pour accéder à une observation");
        }

        //Find in database the observation identified by the given id in parameter
        $observationRepo = $this->em->getRepository('TSNaoBundle:Observation');
        if(is_null($observationRepo)) {
            $response["errors"] = array("Impossible d'accèder à la table des observations");
        }

        try {
            $listObservations = $observationRepo->getObservation($parameters["id"]);
            $nbObservations = count($listObservations);

            if(0 === $nbObservations) {
                $response["messages"] = array("cette observation est introuvable");
            }

            $observation = $listObservations[0][0];
            unset($listObservations[0][0]);
            array_unshift($listObservations, $observation);
            $response["data"] = $listObservations;
        }
        catch (ORMException $e) {
            $response["errors"] = array($e->getMessage());
        }
        catch (\Exception $e) {
            $response["errors"] = array($e->getMessage());
        }

        return $response;
    }

    private function getValidatedObservations($parameters)
    {
        $response = array("errors" => array(), "data" => array(), "messages" => array() );

        if(!array_key_exists("user_id", $parameters)) {
            $response["errors"] = array("l'utilisateur n'a pas été identifié");
        }

        //Find in database the validated observations of the user identified by the given user id in parameter
        $observationRepo = $this->em->getRepository('TSNaoBundle:Observation');
        if(is_null($observationRepo)) {
            $response["errors"] = array("Impossible d'accèder à la table des observations");
        }

        try {
            $criteria = array("user" => $parameters["user_id"], "state" => StateEnum::VALIDATE);
            $order_by = array("dtModification" => "desc");
            $listObservations = $observationRepo->findBy($criteria,$order_by);

            $response["data"] = $listObservations;
        }
        catch (ORMException $e) {
            $response["errors"] = array($e->getMessage());
        }
        catch (\Exception $e) {
            $response["errors"] = array($e->getMessage());
        }

        return $response;
    }

    private function getRejectedObservations($parameters)
    {
        $response = array("errors" => array(), "data" => array(), "messages" => array() );

        if(!array_key_exists("user_id", $parameters)) {
            $response["errors"] = array("l'utilisateur n'a pas été identifié");
        }

        //Find in database the validated observations of the user identified by the given user id in parameter
        $observationRepo = $this->em->getRepository('TSNaoBundle:Observation');
        if(is_null($observationRepo)) {
            $response["errors"] = array("Impossible d'accèder à la table des observations");
        }

        try {
            $criteria = array("user" => $parameters["user_id"], "state" => StateEnum::INVALIDATE);
            $order_by = array("dtModification" => "desc");
            $listObservations = $observationRepo->findBy($criteria,$order_by);

            $response["data"] = $listObservations;
        }
        catch (ORMException $e) {
            $response["errors"] = array($e->getMessage());
        }
        catch (\Exception $e) {
            $response["errors"] = array($e->getMessage());
        }

        return $response;
    }

    private function getPendingObservations($parameters)
    {
        $response = array("errors" => array(), "data" => array(), "messages" => array() );

        if(!array_key_exists("user_id", $parameters)) {
            $response["errors"] = array("l'utilisateur n'a pas été identifié");
        }

        //Find in database the validated observations of the user identified by the given user id in parameter
        $observationRepo = $this->em->getRepository('TSNaoBundle:Observation');
        if(is_null($observationRepo)) {
            $response["errors"] = array("Impossible d'accèder à la table des observations");
        }

        try {
            if($parameters["user_id"] === -1) {
                $criteria = array("state" => StateEnum::SUBMIT);
            }
            else {
                $criteria = array("user" => $parameters["user_id"], "state" => StateEnum::SUBMIT);
            }
            $order_by = array("dtModification" => "desc");
            $listObservations = $observationRepo->findBy($criteria,$order_by);

            $response["data"] = $listObservations;
        }
        catch (ORMException $e) {
            $response["errors"] = array($e->getMessage());
        }
        catch (\Exception $e) {
            $response["errors"] = array($e->getMessage());
        }

        return $response;
    }

    private function updateObservationStatus($parameters) {
        $response = array("errors" => array(), "data" => array(), "messages" => array() );

        //Update the status of an given observation
        $observationRepo = $this->em->getRepository('TSNaoBundle:Observation');
        if(is_null($observationRepo)) {
            $response["errors"] = array("Impossible d'accèder à la table des observations");
        }

        try {

            $criteria = array("id" => $parameters["observation_id"],
                              "state" => $parameters["observation_status"]);

            //$order_by = array("dtModification" => "desc");
            $nbModifiedRow = $observationRepo->updateStatus($criteria);
            if($nbModifiedRow === 1 ) {
                $response["data"] = array("hasChanged" =>true);
            }
            else {
                $response["data"]= array("hasChanged" =>false);
            }
        }
        catch (ORMException $e) {
            $response["errors"] = array($e->getMessage());
        }
        catch (\Exception $e) {
            $response["errors"] = array($e->getMessage());
        }

        return $response;
    }
}