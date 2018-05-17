<?php 
// src/TS/NaoBundle/DoctrineListener/RegistrationListener.php

namespace TS\NaoBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use TS\NaoBundle\Email\Mailing;
use TS\NaoBundle\Entity\User;

class RegistrationListener
{
	/**
	 * @var Mailing
	 */
	private $mailer;

	public function __construct(Mailing $mailer)
	{
		$this->mailer = $mailer;
	}

	public function postPersist(LifecycleEventArgs $args)
	{
		$user = $args->getObject();

		if(!$user instanceof User) {
			return;
		}

		$this->mailer->confirmRegistration($user);
	}
}