<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 16/04/18
 * Time: 16:26
 */

namespace  TS\NaoBundle\Enum;


class ProfilEnum
{
    const BIRD_FANCIER = "bird_fancier";
    const NATURALIST   = "naturalist";
    const ADMIN        = "admin";

    static public function getValues() {
        $values[] = ProfilEnum::BIRD_FANCIER;
        $values[] = ProfilEnum::NATURALIST;
        $values[] = ProfilEnum::ADMIN;

        return $values;
    }
}