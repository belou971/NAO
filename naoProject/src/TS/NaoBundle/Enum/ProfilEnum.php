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
    const BIRD_FANCIER = 'ROLE_BIRD_FANCIER';
    const NATURALIST   = 'ROLE_NATURALIST';
    const ADMIN        = 'ROLE_ADMIN';

    static public function getValues() {
        $values[] = ProfilEnum::BIRD_FANCIER;
        $values[] = ProfilEnum::NATURALIST;
        $values[] = ProfilEnum::ADMIN;

        return $values;
    }
}