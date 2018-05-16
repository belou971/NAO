<?php 
// src/TS/NaoBundle/DoctrineListener/DeleteAccountListener.php

namespace TS\NaoBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use TS\NaoBundle\Entity\User;

class DeleteAccountListener
{
	/**
	 * @var \Swift_Mailer
	 */
	private $mailer;
	private $mailerUser;

	public function __construct(\Swift_Mailer $mailer, $mailerUser)
	{
		$this->mailer = $mailer;
		$this->mailerUser = $mailerUser;
	}

	public function sendEmail(User $user)
	{
		$message = new \Swift_Message('Suppression de compte', 'Votre compte a bien été supprimé.');
		$message->setFrom([$this->mailerUser => 'Nos Amis les Oiseaux'])->setTo($user->getEmail());
		$this->mailer->send($message);
	}

	public function postRemove(LifecycleEventArgs $args)
	{
		$user = $args->getObject();

		if(!$user instanceof User) {
			return;
		}

		$this->sendEmail($user);
	}
}