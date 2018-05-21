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

    public function get($action, $parameters) {
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

        return json_encode($response, JSON_UNESCAPED_UNICODE);
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
}