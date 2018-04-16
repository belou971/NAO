<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 16/04/18
 * Time: 16:21
 */

namespace TS\NaoBundle\Enum;


/**
 * Class StateEnum
 * @package TS\NaoBundle\Enum
 */
class StateEnum
{
    const STANDBY    = "standby";
    const VALIDATE   = "validate";
    const INVALIDATE = "invalidate";
    const SUBMIT     = "submit";
    const DELETE     = "delete";

    static public function getValues() {
        $values[] = StateEnum::STANDBY;
        $values[] = StateEnum::VALIDATE;
        $values[] = StateEnum::INVALIDATE;
        $values[] = StateEnum::SUBMIT;
        $values[] = StateEnum::DELETE;

        return $values;
    }
}