<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 16/04/18
 * Time: 16:15
 */

namespace TS\NaoBundle\Type;

use TS\NaoBundle\Enum\StateEnum;
use TS\NaoBundle\Type\EnumType;

class StateType extends EnumType
{
    protected $nameType = StateType::class;

    public function getValues()
    {
        return StateEnum::getValues();
    }
}