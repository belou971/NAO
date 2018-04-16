<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 16/04/18
 * Time: 16:16
 */

namespace TS\NaoBundle\Type;

use TS\NaoBundle\Enum\ProfilEnum;

class ProfilType extends EnumType
{
    protected $nameType = ProfilType::class;

    public function getValues()
    {
        return ProfilEnum::getValues();
    }
}