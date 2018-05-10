<?php 
// src/TS/NaoBundle/DoctrineListener/RegistrationListener.php

namespace TS\NaoBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use TS\NaoBundle\Email\RegistrationConfirmation;
use TS\NaoBundle\Entity\User;

class RegistrationListener
{
	/**
	 * @var RegistrationConfirmation
	 */
	private $registration;

	public function __construct(RegistrationConfirmation $registration)
	{
		$this->registration = $registration;
	}

	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if(!$entity instanceof User) {
			return;
		}

		$this->registration->sendEmail($entity);
	}
}