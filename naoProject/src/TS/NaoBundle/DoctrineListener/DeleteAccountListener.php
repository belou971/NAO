<?php 
// src/TS/NaoBundle/DoctrineListener/DeleteAccountListener.php

namespace TS\NaoBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use TS\NaoBundle\Email\Mailing;
use TS\NaoBundle\Entity\User;

class DeleteAccountListener
{
	/**
	 * @var Mailing
	 */
	private $mailer;

	public function __construct(Mailing $mailer)
	{
		$this->mailer = $mailer;
	}

	public function postRemove(LifecycleEventArgs $args)
	{
		$user = $args->getObject();

		if(!$user instanceof User) {
			return;
		}

		$this->mailer->deleteAccount($user);
	}
}