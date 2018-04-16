<?php

namespace TS\NaoBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TSNaoBundle extends Bundle
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function boot()
    {
       if(!Type::hasType('ProfilType')){
            Type::addType('ProfilType', "TS\\NaoBundle\\Type\\ProfilType");
        }

        if(!Type::hasType('StateType')){
            Type::addType('StateType', "TS\\NaoBundle\\Type\\StateType");
        }

        $entityMgr = $this->container->get('doctrine.orm.entity_manager');
        if(!$entityMgr->getConnection()->getDatabasePlatform()->hasDoctrineTypeMappingFor('ProfilType')) {
            $entityMgr->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'ProfilType');
        }

        if(!$entityMgr->getConnection()->getDatabasePlatform()->hasDoctrineTypeMappingFor('StateType')) {
            $entityMgr->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'StateType');
        }
    }
}
