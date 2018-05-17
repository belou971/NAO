<?php 
// src/TS/NaoBundle/DataFixtures/ORM/LoadUser.php

namespace TS\NaoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TS\NaoBundle\Entity\User;
use TS\NaoBundle\Enum\ProfilEnum;

class LoadUser implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$admin = new User();
		$admin->setName('Mbenguia');
		$admin->setSurname('Husseini');
		$admin->setEmail('mbenguia.husseini@live.fr');
		$admin->setPassword('Jkl123');
		$admin->setRoles(ProfilEnum::ADMIN);
		$manager->persist($admin);

		$naturalist = new User();
		$naturalist->setName('Hagege');
		$naturalist->setSurname('Ornella');
		$naturalist->setEmail('kapooral.b@gmail.com');
		$naturalist->setPassword('Jkl123');
		$naturalist->setRoles(ProfilEnum::NATURALIST);
		$manager->persist($naturalist);

		$birdFancier = new User();
		$birdFancier->setName('Mbenguia');
		$birdFancier->setSurname('Ivy');
		$birdFancier->setEmail('oc.student.husseini@gmail.com');
		$birdFancier->setPassword('Jkl123');
		$birdFancier->setRoles(ProfilEnum::BIRD_FANCIER);
		$manager->persist($birdFancier);

		$manager->flush();
	}
}