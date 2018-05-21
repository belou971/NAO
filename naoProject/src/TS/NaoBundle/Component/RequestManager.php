<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 12/05/18
 * Time: 22:08
 */
namespace TS\NaoBundle\Component;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;

class RequestManager
{
    private static $instance;

    private function __construct() {
    }

    public static function getInstance() {

        if(is_null(self::$instance)) {
            self::$instance = new RequestManager() ;
        }

        return self::$instance;
    }

    public function get($action, $parameters) {
        //initialize the output of the function
        $response = array("errors" => array(), "input_data" => array() );

        if(!ActionType::exists($action)) {
            $response["errors"] = array($action." is not an known action");
        }
        else if(!is_array($parameters)) {
            $response["errors"] = array("parameters type error. Parameters list must be an array");
        }
        else {
            $response = $this->doAction($action, $parameters);
        }
        return $response;
    }

    private function doAction($action, $parameters)
    {
        $response = array("errors" => array(), "input_data" => array());

        //Decode request content
        $content = json_decode($parameters["content"], true);
        if (!is_array($content)) {
            $response["errors"] = array("Une erreur dans la requete a été détectée");
            return $response;
        }

        if (!array_key_exists("method", $parameters)) {
            $response["errors"] = array("method type of the request not found in parameters");
        }
        else if (0 === strcasecmp("post", $parameters["method"])) {

            if (ActionType::SEARCH_SPECIMEN_BY_NAME === $action) {
                $response = $this->inputFindSpecimenByNameAction($content);
            }
            else if (ActionType::SEARCH_SPECIMEN_BY_CITY === $action) {
                $response = $this->inputFindSpecimenByCityAction($content);
            }
            else if (ActionType::SEARCH_SPECIMEN_BY_COORD === $action) {
                $response = $this->inputFindSpecimenByCoordAction($content);
            }
            else {
                $response["errors"] = array("The request method is not a post");
            }

        } else if(0 === strcasecmp("get", $parameters["method"])) {
            $response["errors"] = array("The request method is not a get");
        }
        else {
            $response["errors"] = array("There is not an action associated to your request");
        }

        return $response;
    }

    private function inputFindSpecimenByNameAction($parameters) {
        $response = array("errors" => array(), "input_data" => array() );

        //Verify the validation of the content request
        if(!array_key_exists("specimen_name", $parameters)) {
            $response["errors"] = array("Syntax error in content request");
            return $response;
        }

        $validator = Validation::createValidator();
        $errors = $validator->validate($parameters["specimen_name"],
            array(new NotBlank(array('message' => "Le nom de l'espèce est vide")),
                new NotNull(array('message' => "Veuiller saisir le nom d'espèce"))
            ));

        if(0 !== count($errors)) {
            $msgs = array();
            foreach($errors as $error) {
                $msgs[] = $error->getMessage();
            }
            $response["errors"] = $msgs;
            return $response;
        }

        $response["input_data"] = $parameters;

        return $response;
    }

    private function inputFindSpecimenByCityAction($parameters) {
        $response = array("errors" => array(), "input_data" => array() );

        if(!array_key_exists("city", $parameters)) {
            $response["errors"] = array("Syntax error in content request, 'city' property not found");
            return $response;
        }

        if(!array_key_exists("geo_properties", $parameters)) {
            $response["errors"] = array("Syntax error in content request, 'geo_properties' property not found");
            return $response;
        }

        foreach($parameters as $param) {
            if(is_array($param)) {
                if (count($param) <= 0) {
                    $response["errors"] = array("properties not found for city \'" . $parameters["city"] . "\'");
                    return $response;
                }
            } else {
                $validator = Validation::createValidator();
                $errors = $validator->validate($param,
                    array(new NotBlank(array('message' => "Le nom de la ville est vide")),
                          new NotNull(array('message' => "Veuiller saisir une ville"))
                    ));

                if(0 !== count($errors)) {
                    $msgs = array();
                    foreach($errors as $error) {
                        $msgs[] = $error->getMessage();
                    }
                    $response["errors"] = $msgs;
                    return $response;
                }
            }
        }

        $response["input_data"] = $parameters;

        return $response;
    }

    private function inputFindSpecimenByCoordAction($parameters) {
        $response = array("errors" => array(), "input_data" => array() );

        if(!array_key_exists("coord_properties", $parameters)) {
            $response["errors"] = array("Syntax error in content request, \'geo_properties\' property not found");
            return $response;
        }

        foreach ($parameters as $param) {
            if(is_array($param)) {
                if (count($param) <= 0) {
                    $response["errors"] = array("properties not found for city \'" . $parameters["city"] . "\'");
                    return $response;
                }
            }
        }

        $response["input_data"] = $parameters["coord_properties"]["features"][0];

        return $response;
    }
}